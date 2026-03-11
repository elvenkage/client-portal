<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\File;
use App\Models\ActivityLog;

class ClientProjectView extends Component
{
    public $project;
    public $milestones;
    public $files;
    public $activities;
    public $tasksPendingReview;

    public function mount($projectId)
    {
        $user = auth()->user();

        // Load project scoped to the client's records
        $this->project = Project::where('id', $projectId)
            ->where('client_id', $user->client_id)
            ->where('visibility', '!=', 'private')
            ->firstOrFail();

        $this->loadData();
    }

    public function loadData()
    {
        // Milestones with task-based progress
        $this->milestones = Milestone::where('project_id', $this->project->id)
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => function ($q) {
                    $q->where('status', 'completed');
                }
            ])
            ->orderBy('deadline')
            ->get()
            ->map(function ($milestone) {
                $milestone->progress = $milestone->tasks_count > 0
                    ? (int) round(($milestone->completed_tasks_count / $milestone->tasks_count) * 100)
                    : 0;
                return $milestone;
            });

        // Files belonging to project (NOT internal task files only — project-level files)
        $this->files = File::where('project_id', $this->project->id)
            ->with('uploader')
            ->latest()
            ->limit(20)
            ->get();

        // Activity timeline (excluding internal task details)
        $this->activities = ActivityLog::where('project_id', $this->project->id)
            ->whereIn('action', [
                'milestone_completed',
                'file_uploaded',
                'task_completed',   // Client can see when deliverables are completed
            ])
            ->with('user')
            ->latest('created_at')
            ->limit(15)
            ->get();

        // Tasks pending client review
        $this->tasksPendingReview = \App\Models\Task::where('project_id', $this->project->id)
            ->where('status', 'review')
            ->where('review_stage', 'client_review')
            ->get();
    }

    public function approveTask($taskId, \App\Services\TaskService $taskService)
    {
        $task = \App\Models\Task::findOrFail($taskId);
        abort_if((int) $task->project_id !== (int) $this->project->id, 403);

        $taskService->clientApprove($task);
        $this->loadData();
    }

    public function requestRevision($taskId, \App\Services\TaskService $taskService)
    {
        $task = \App\Models\Task::findOrFail($taskId);
        abort_if((int) $task->project_id !== (int) $this->project->id, 403);

        $taskService->clientRequestRevision($task);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.client.client-project-view');
    }
}
