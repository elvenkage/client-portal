<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Task;

class TaskModal extends Component
{
    public bool $isOpen = false;
    public ?Task $task = null;

    #[On('openTaskModal')]
    public function openTaskModal($taskId)
    {
        $this->task = Task::with([
            'assignee',
            'comments.user',
            'activities.user'
        ])->findOrFail($taskId);

        $this->isOpen = true;
    }

    public function render()
    {
        return view('livewire.tasks.task-modal');
    }
}
