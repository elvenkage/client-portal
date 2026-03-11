<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Task;
use App\Models\Comment;
use App\Services\NotificationService;

class TaskComments extends Component
{
    public $task;
    public string $newComment = '';

    protected NotificationService $notificationService;

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function mount(Task $task)
    {
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');

        $this->task = $task;
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:5000',
        ], [
            'newComment.required' => 'Please enter a comment.',
            'newComment.max' => 'Comment must not exceed 5,000 characters.',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'project_id' => $this->task->project_id,
            'task_id' => $this->task->id,
            'message' => $this->newComment,
        ]);

        // Notify the task assignee (if different from the commenter)
        if ($this->task->assigned_to && $this->task->assigned_to !== auth()->id()) {
            $excerpt = str($this->newComment)->limit(80);

            $this->notificationService->sendInApp(
                userId: $this->task->assigned_to,
                projectId: $this->task->project_id,
                type: 'task_comment',
                title: 'New Comment on Task',
                message: auth()->user()->name . " commented on \"{$this->task->title}\": \"{$excerpt}\"",
            );
        }

        $this->newComment = '';
        $this->task->load('comments.user');

        $this->dispatch('comment-added');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Only the author can delete their own comment
        if ($comment->user_id !== auth()->id()) {
            return;
        }

        // Only delete comments belonging to this task
        if ($comment->task_id !== $this->task->id) {
            return;
        }

        $comment->delete();
        $this->task->load('comments.user');
    }

    public function render()
    {
        return view('livewire.tasks.task-comments', [
            'comments' => $this->task->comments()
                ->with('user')
                ->latest()
                ->get(),
        ]);
    }
}
