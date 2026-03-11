<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Task;
use App\Services\TaskService;

class TaskBoard extends Component
{
    public $projectId;
    public $todo;
    public $inProgress;
    public $review;
    public $completed;

    protected TaskService $taskService;

    public function boot(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function mount($projectId)
    {
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');

        $this->projectId = $projectId;
        $this->loadTasks();
    }

    #[On('task-created')]
    public function loadTasks()
    {
        $allTasks = Task::with('assignee')
            ->where('project_id', $this->projectId)
            ->get();

        $this->todo = $allTasks->where('status', 'todo')->values();
        $this->inProgress = $allTasks->where('status', 'in_progress')->values();
        $this->review = $allTasks->where('status', 'review')->values();
        $this->completed = $allTasks->where('status', 'completed')->values();
    }

    public function startTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $this->taskService->updateStatus($task, Task::STATUS_IN_PROGRESS);
        $this->loadTasks();
    }

    public function submitForReview($taskId)
    {
        $task = Task::findOrFail($taskId);
        $this->taskService->submitForReview($task);
        $this->loadTasks();
    }

    public function completeTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $this->taskService->approveTask($task);
        $this->loadTasks();
    }

    public function updateTaskStatus($taskId, $status)
    {
        $validStatuses = ['todo', 'in_progress', 'review', 'completed'];

        if (!in_array($status, $validStatuses)) {
            return;
        }

        $task = Task::findOrFail($taskId);
        $oldStatus = $task->status;

        try {
            // Map Kanban drags to actual Workflow Methods
            if ($status === 'completed' && $oldStatus === 'review') {
                $this->taskService->approveTask($task);
            } elseif ($status === 'in_progress' && $oldStatus === 'review') {
                $this->taskService->requestRevision($task);
            } elseif ($status === 'review' && $oldStatus === 'in_progress') {
                $this->taskService->submitForReview($task);
            } else {
                // Fallback to strict setter for generic transitions (e.g. todo -> in_progress)
                $this->taskService->updateStatus($task, $status);
            }
        } catch (\InvalidArgumentException $e) {
            // Dispatch browser event to show error toast in UI
            $this->dispatch('workflow-error', message: $e->getMessage());
        }

        $this->loadTasks();
    }

    public function render()
    {
        return view('livewire.tasks.task-board', [
            'tasksByStatus' => [
                'todo' => $this->todo ?? collect(),
                'in_progress' => $this->inProgress ?? collect(),
                'review' => $this->review ?? collect(),
                'completed' => $this->completed ?? collect(),
            ]
        ]);
    }
}