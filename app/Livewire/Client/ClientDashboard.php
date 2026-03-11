<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Project;

class ClientDashboard extends Component
{
    public $projects;

    public function mount()
    {
        $this->loadProjects();
    }

    public function loadProjects()
    {
        $user = auth()->user();

        $this->projects = Project::where('client_id', $user->client_id)
            ->where('visibility', '!=', 'private')
            ->withCount(['milestones', 'files'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.client.client-dashboard');
    }
}
