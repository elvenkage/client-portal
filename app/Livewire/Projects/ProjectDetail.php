<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;

class ProjectDetail extends Component
{
    public $project;

    public function mount($projectId)
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