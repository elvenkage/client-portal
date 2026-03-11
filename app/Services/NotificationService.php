<?php

namespace App\Services;

use App\Models\Milestone;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send an in-app notification to a single user.
     */
    public function sendInApp(
        int $userId,
        ?int $projectId,
        string $type,
        string $title,
        ?string $message = null,
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'project_id' => $projectId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }

    /**
     * Send an email notification to a user.
     *
     * Uses a simple Laravel Mail::raw call. Can be swapped for a Mailable later.
     */
    public function sendEmail(User $user, string $subject, string $body): void
    {
        Mail::raw($body, function ($mail) use ($user, $subject) {
            $mail->to($user->email)->subject($subject);
        });
    }

    /**
     * Notify relevant users when a task is completed.
     */
    public function notifyTaskCompleted(Task $task): void
    {
        $project = $task->project;

        // Notify the project manager
        $this->sendInApp(
            userId: $project->project_manager_id,
            projectId: $project->id,
            type: 'task_completed',
            title: 'Task Completed',
            message: "Task \"{$task->title}\" has been completed.",
        );
    }

    /**
     * Notify a user when a task is assigned to them.
     */
    public function notifyTaskAssigned(Task $task): void
    {
        if (!$task->assigned_to) {
            return;
        }

        // Don't notify if the assigner is the same person
        if ($task->assigned_to === auth()->id()) {
            return;
        }

        $this->sendInApp(
            userId: $task->assigned_to,
            projectId: $task->project_id,
            type: 'task_assigned',
            title: 'Task Assigned',
            message: "You've been assigned to \"{$task->title}\".",
        );
    }

    /**
     * Notify relevant users when a file is uploaded.
     */
    public function notifyFileUploaded(Project $project, string $fileName): void
    {
        $this->sendInApp(
            userId: $project->project_manager_id,
            projectId: $project->id,
            type: 'file_uploaded',
            title: 'File Uploaded',
            message: "A new file \"{$fileName}\" has been uploaded.",
        );
    }

    /**
     * Notify relevant users when a comment is added.
     */
    public function notifyCommentAdded(Project $project, User $author, string $excerpt): void
    {
        $this->sendInApp(
            userId: $project->project_manager_id,
            projectId: $project->id,
            type: 'comment_added',
            title: 'New Comment',
            message: "{$author->name} commented: \"{$excerpt}\"",
        );
    }

    /**
     * Notify relevant users when a milestone is completed.
     */
    public function notifyMilestoneCompleted(Milestone $milestone): void
    {
        $project = $milestone->project;

        $this->sendInApp(
            userId: $project->project_manager_id,
            projectId: $project->id,
            type: 'milestone_completed',
            title: 'Milestone Completed',
            message: "Milestone \"{$milestone->title}\" has been completed.",
        );
    }
}
