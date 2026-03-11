<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;

class ProjectMembers extends Component
{
    public $projectId;
    public $selectedUserId = '';
    public $selectedClientId = '';
    public $project;
    public $teamMembers;
    public $clientMembers;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->project = Project::findOrFail($projectId);
        $this->loadTeam();
    }

    public function addMember(ProjectService $projectService)
    {
        if (!$this->selectedUserId) {
            return;
        }

        $user = User::findOrFail($this->selectedUserId);
        $projectService->assignTeamMember($this->project, $user, 'team_member');
        $this->selectedUserId = null;
        $this->loadTeam();
    }

    public function addClient(ProjectService $projectService)
    {
        if (!$this->selectedClientId) {
            return;
        }

        $user = User::findOrFail($this->selectedClientId);
        $projectService->assignTeamMember($this->project, $user, 'client');
        $this->selectedClientId = null;
        $this->loadTeam();
    }

    public function loadTeam()
    {
        $this->teamMembers = $this->project->members()
            ->wherePivotIn('role', ['project_manager', 'team_member'])
            ->get();

        $this->clientMembers = $this->project->members()
            ->wherePivot('role', 'client')
            ->get();
    }

    public function removeMember($memberId, ProjectService $projectService)
    {
        $member = $this->project->members()->findOrFail($memberId);

        // Prevent removing the project manager from their own project
        if ($member->id === $this->project->project_manager_id) {
            return;
        }

        $projectService->removeTeamMember($this->project, $member);
        $this->loadTeam();
    }

    public function render()
    {
        $projectId = $this->project->id;

        $availableTeamMembers = User::whereIn('role', ['project_manager', 'team_member'])
            ->whereDoesntHave('projects', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })->get();

        $availableClients = User::where('role', 'client')
            ->whereDoesntHave('projects', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            })->get();
            
        $memberCount = \App\Models\ProjectMember::where('project_id', $projectId)
            ->whereIn('role', ['project_manager', 'team_member'])
            ->count();

        return view('livewire.projects.project-members', [
            'availableTeamMembers' => $availableTeamMembers,
            'availableClients' => $availableClients,
            'memberCount' => $memberCount,
        ]);
    }
}
