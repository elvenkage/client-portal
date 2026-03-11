<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Project;

class ProjectDetail extends Component
{
    public $project;

    public function mount($projectId)
    {
        $this->loadProject($projectId);
    }

    #[On('project-updated')]
    public function refreshProject()
    {
        $this->loadProject($this->project->id);
    }

    public function updateProjectStatus($status)
    {
        $validStatuses = ['planning', 'in_progress', 'on_hold', 'completed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return;
        }

        $this->project->status = $status;
        $this->project->save();

        $this->loadProject($this->project->id);

        $this->dispatch('project-updated');
    }

    protected function loadProject($projectId)
    {
        $this->project = Project::with([
            'milestones',
            'tasks',
            'files',
        ])->findOrFail($projectId);
    }

    public function render()
    {
        return view('livewire.projects.project-detail')
            ->layout('layouts.app');
    }
}