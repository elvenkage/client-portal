<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Task;
use App\Models\User;
use App\Models\Milestone;
use App\Services\TaskService;

class CreateTaskModal extends Component
{
    public $projectId;
    public $isOpen = false;

    // Form fields
    public $title = '';
    public $description = '';
    public $priority = 'medium';
    public $status = 'todo';
    public $deadline = '';
    public $assigned_to = '';
    public $milestone_id = '';

    protected TaskService $taskService;

    public function boot(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    #[On('openCreateTaskModal')]
    public function openModal()
    {
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');

        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->priority = 'medium';
        $this->status = 'todo';
        $this->deadline = '';
        $this->assigned_to = '';
        $this->milestone_id = '';
    }

    public function save()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,review,completed',
            'deadline' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'milestone_id' => 'nullable|exists:milestones,id',
        ]);

        $validated['project_id'] = $this->projectId;

        // Clean empty strings to null
        $validated['assigned_to'] = $validated['assigned_to'] ?: null;
        $validated['milestone_id'] = $validated['milestone_id'] ?: null;

        $this->taskService->createTask($validated);

        $this->closeModal();

        $this->dispatch('task-created');
    }

    public function render()
    {
        return view('livewire.tasks.create-task-modal', [
            'users' => User::where('role', '!=', 'client')->orderBy('name')->get(),
            'milestones' => Milestone::where('project_id', $this->projectId)
                ->orderBy('title')
                ->get(),
        ]);
    }
}
