<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Record an activity log entry.
     *
     * @param  int          $projectId
     * @param  string       $action     One of the predefined action constants.
     * @param  string       $description  Human-readable description.
     * @param  Model|null   $target       The related model (Task, Milestone, File, Comment…).
     * @param  int|null     $userId       Defaults to the authenticated user.
     */
    public function log(
        int $projectId,
        string $action,
        string $description,
        ?Model $target = null,
        ?int $userId = null,
    ): ActivityLog {
        return ActivityLog::create([
            'project_id' => $projectId,
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'description' => $description,
            'target_type' => $target ? $target->getMorphClass() : null,
            'target_id' => $target?->getKey(),
        ]);
    }

    // ──────────────────────────────────────────────
    //  Convenience Methods
    // ──────────────────────────────────────────────

    public function logTaskCreated($task): void
    {
        $this->log(
            $task->project_id,
            'task_created',
            "Task \"{$task->title}\" was created.",
            $task,
        );
    }

    public function logTaskStatusChanged($task, string $oldStatus, string $newStatus): void
    {
        $this->log(
            $task->project_id,
            'task_status_changed',
            "Task \"{$task->title}\" moved from {$oldStatus} to {$newStatus}.",
            $task,
        );
    }

    public function logTaskCompleted($task): void
    {
        $this->log(
            $task->project_id,
            'task_completed',
            "Task \"{$task->title}\" was completed.",
            $task,
        );
    }

    public function logFileUploaded($file): void
    {
        $this->log(
            $file->project_id,
            'file_uploaded',
            "File \"{$file->original_name}\" was uploaded.",
            $file,
        );
    }

    public function logCommentAdded($comment): void
    {
        $this->log(
            $comment->project_id,
            'comment_added',
            'A comment was added.',
            $comment,
        );
    }

    public function logMilestoneCompleted($milestone): void
    {
        $this->log(
            $milestone->project_id,
            'milestone_completed',
            "Milestone \"{$milestone->title}\" was completed.",
            $milestone,
        );
    }

    public function logClientApproved($task): void
    {
        $this->log(
            $task->project_id,
            'client_approved',
            "Client approved task \"{$task->title}\".",
            $task,
        );
    }

    public function logClientRevisionRequested($task): void
    {
        $this->log(
            $task->project_id,
            'client_revision_requested',
            "Client requested a revision for \"{$task->title}\".",
            $task,
        );
    }

    public function logFileDeleted($file): void
    {
        $this->log(
            $file->project_id,
            'file_deleted',
            "File was deleted.",
            $file,
        );
    }
}
