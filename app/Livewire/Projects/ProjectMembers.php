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
    public $project;
    public $members;

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

        $projectService->assignTeamMember($this->project, $user);

        $this->selectedUserId = null;

        $this->loadTeam();
    }

    public function loadTeam()
    {
        $this->members = $this->project->members()->with('user')->get();
    }

    public function removeMember($memberId, ProjectService $projectService)
    {
        $member = $this->project->members()->with('user')->findOrFail($memberId);

        // Prevent removing the project manager from their own project
        if ($member->user_id === $this->project->project_manager_id) {
            return;
        }

        // Use the service to handle removal
        if ($member->user) {
            $projectService->removeTeamMember($this->project, $member->user);
            $this->loadTeam();
        }
    }

    public function render()
    {
        $currentMemberIds = $this->members->pluck('user_id')->toArray();
        $currentMemberIds[] = $this->project->project_manager_id; // Always include PM implicitly or explicitly

        $availableUsers = User::whereIn('role', ['team_member', 'project_manager', 'admin'])
            ->whereNotIn('id', $currentMemberIds)
            ->get();

        return view('livewire.projects.project-members', [
            'availableUsers' => $availableUsers,
        ]);
    }
}
