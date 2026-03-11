<?php

namespace App\Services;

use App\Models\Task;
use Carbon\Carbon;
use InvalidArgumentException;

class TaskService
{
    public function __construct(
        protected ProjectService $projectService,
        protected MilestoneService $milestoneService,
        protected NotificationService $notificationService,
        protected ActivityLogService $activityLogService,
    ) {
    }

    // ──────────────────────────────────────────────
    //  CRUD
    // ──────────────────────────────────────────────

    /**
     * Create a new task.
     *
     * @param  array<string, mixed>  $data
     */
    public function createTask(array $data): Task
    {
        $data['status'] ??= Task::STATUS_TODO;
        $data['review_stage'] ??= Task::REVIEW_NONE;

        $task = Task::create($data);

        $this->syncProgress($task);

        $this->activityLogService->logTaskCreated($task);

        return $task;
    }

    public function updateStatus(Task $task, string $status): Task
    {
        $oldStatus = $task->status;

        // Define allowable transitions
        $transitions = [
            Task::STATUS_TODO => [Task::STATUS_IN_PROGRESS],
            Task::STATUS_IN_PROGRESS => [Task::STATUS_TODO, Task::STATUS_REVIEW],
                // Review/Completed generally shouldn't transition via generic updateStatus
                // because they have dedicated methods (submitForReview, approveTask),
                // but for safety in Kanban dragging, we handle allowable paths:
            Task::STATUS_REVIEW => [Task::STATUS_IN_PROGRESS],
            Task::STATUS_COMPLETED => [Task::STATUS_IN_PROGRESS],
        ];

        if (!in_array($status, $transitions[$oldStatus] ?? [])) {
            throw new InvalidArgumentException(
                "Invalid status transition from {$oldStatus} to {$status}."
            );
        }

        $task->update(['status' => $status]);

        $this->syncProgress($task);

        $this->activityLogService->logTaskStatusChanged($task, $oldStatus, $status);

        return $task;
    }

    // ──────────────────────────────────────────────
    //  Workflow: Submit → PM Review
    // ──────────────────────────────────────────────

    /**
     * Team member submits a task for review.
     *
     * Flow: in_progress → review (pm_review)
     *
     * @throws InvalidArgumentException if task is not in_progress
     */
    public function submitForReview(Task $task): Task
    {
        if (!$task->isReviewable()) {
            throw new InvalidArgumentException(
                "Task #{$task->id} cannot be submitted for review — current status is \"{$task->status}\"."
            );
        }

        $task->update([
            'status' => Task::STATUS_REVIEW,
            'review_stage' => Task::REVIEW_PM,
        ]);

        // Notify the project manager
        $this->notificationService->sendInApp(
            userId: $task->project->project_manager_id,
            projectId: $task->project_id,
            type: 'task_submitted_for_review',
            title: 'Task Submitted for Review',
            message: "Task \"{$task->title}\" has been submitted for your review.",
        );

        return $task;
    }

    // ──────────────────────────────────────────────
    //  Workflow: PM Approve
    // ──────────────────────────────────────────────

    /**
     * Project Manager approves a task.
     *
     * If client review is required → moves to client_review.
     * Otherwise → completes the task.
     *
     * @throws InvalidArgumentException if task is not in pm_review
     */
    public function approveTask(Task $task): Task
    {
        if (!$task->isPmReview()) {
            throw new InvalidArgumentException(
                "Task #{$task->id} is not awaiting PM review."
            );
        }

        if ($task->client_review_required) {
            return $this->sendToClientReview($task);
        }

        return $this->completeTask($task);
    }

    // ──────────────────────────────────────────────
    //  Workflow: PM Request Revision
    // ──────────────────────────────────────────────

    /**
     * Project Manager requests a revision.
     *
     * Puts the task back to in_progress with review_stage none.
     *
     * @throws InvalidArgumentException if task is not in review
     */
    public function requestRevision(Task $task): Task
    {
        if ($task->status !== Task::STATUS_REVIEW) {
            throw new InvalidArgumentException(
                "Task #{$task->id} is not in review — cannot request revision."
            );
        }

        $task->update([
            'status' => Task::STATUS_IN_PROGRESS,
            'review_stage' => Task::REVIEW_NONE,
        ]);

        // Notify the assignee
        if ($task->assigned_to) {
            $this->notificationService->sendInApp(
                userId: $task->assigned_to,
                projectId: $task->project_id,
                type: 'revision_requested',
                title: 'Revision Requested',
                message: "Task \"{$task->title}\" has been sent back for revision.",
            );
        }

        return $task;
    }

    // ──────────────────────────────────────────────
    //  Workflow: PM → Client Review
    // ──────────────────────────────────────────────

    public function sendToClientReview(Task $task): Task
    {
        if ($task->status !== Task::STATUS_REVIEW) {
            throw new InvalidArgumentException(
                "Task #{$task->id} is not in review — cannot send to client."
            );
        }

        $task->update([
            'review_stage' => Task::REVIEW_CLIENT,
        ]);

        $clientUsers = $task->project->members()->where('role', 'client')->get();

        if ($clientUsers->isNotEmpty()) {
            foreach ($clientUsers as $client) {
                $this->notificationService->sendInApp(
                    userId: $client->id,
                    projectId: $task->project_id,
                    type: 'task_sent_to_client_review',
                    title: 'Action Required: Task Review',
                    message: "Task \"{$task->title}\" is ready for your review.",
                );
            }
        }

        return $task;
    }

    // ──────────────────────────────────────────────
    //  Workflow: Client Approve
    // ──────────────────────────────────────────────

    public function clientApprove(Task $task): Task
    {
        if (!$task->isClientReview()) {
            throw new InvalidArgumentException(
                "Task #{$task->id} is not awaiting client review."
            );
        }

        $this->activityLogService->logClientApproved($task);

        return $this->completeTask($task);
    }

    /**
     * Client requests a revision.
     *
     * Puts the task back to in_progress and notifies the PM.
     *
     * @throws InvalidArgumentException if task is not in client_review
     */
    public function clientRequestRevision(Task $task): Task
    {
        if (!$task->isClientReview()) {
            throw new InvalidArgumentException(
                "Task #{$task->id} is not awaiting client review — cannot request revision."
            );
        }

        $task->update([
            'status' => Task::STATUS_IN_PROGRESS,
            'review_stage' => Task::REVIEW_NONE,
        ]);

        $this->activityLogService->logClientRevisionRequested($task);

        // Notify the project manager that the client rejected the task
        $this->notificationService->sendInApp(
            userId: $task->project->project_manager_id,
            projectId: $task->project_id,
            type: 'client_revision_requested',
            title: 'Client Revision Requested',
            message: "The client has requested a revision for \"{$task->title}\".",
        );

        return $task;
    }

    // ──────────────────────────────────────────────
    //  Workflow: Auto-complete expired client reviews
    // ──────────────────────────────────────────────

    /**
     * Auto-complete all tasks whose client review deadline has expired.
     *
     * Intended to be called by a scheduled Artisan command (e.g. daily).
     *
     * @return int  Number of tasks auto-completed.
     */
    public function autoCompleteExpiredReviews(): int
    {
        $expiredTasks = Task::where('status', Task::STATUS_REVIEW)
            ->where('review_stage', Task::REVIEW_CLIENT)
            ->whereNotNull('client_review_deadline')
            ->where('client_review_deadline', '<=', Carbon::now())
            ->get();

        foreach ($expiredTasks as $task) {
            $this->completeTask($task);
        }

        return $expiredTasks->count();
    }

    // ──────────────────────────────────────────────
    //  Internal: Complete Task
    // ──────────────────────────────────────────────

    /**
     * Mark a task as completed, reset review stage, recalculate progress,
     * and fire notifications.
     */
    protected function completeTask(Task $task): Task
    {
        $task->update([
            'status' => Task::STATUS_COMPLETED,
            'review_stage' => Task::REVIEW_NONE,
        ]);

        $this->syncProgress($task);

        $this->notificationService->notifyTaskCompleted($task);

        $this->activityLogService->logTaskCompleted($task);

        return $task;
    }

    // ──────────────────────────────────────────────
    //  Internal: Recalculate progress
    // ──────────────────────────────────────────────

    /**
     * Recalculate progress for the parent project and milestone.
     */
    protected function syncProgress(Task $task): void
    {
        ProjectStatusService::updateProjectStatus($task->project);

        if ($task->milestone_id) {
            $this->milestoneService->calculateProgress($task->milestone);
        }
    }
}
