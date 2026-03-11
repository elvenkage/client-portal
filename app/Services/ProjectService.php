<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    /**
     * Create a new project.
     *
     * @param  array<string, mixed>  $data
     */
    public function createProject(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $clientId = $data['client_user_id'] ?? null;
            unset($data['client_user_id']); // Ensure it doesn't reach the model

            $project = Project::create($data);

            // Automatically add the project manager as a member
            if (isset($data['project_manager_id'])) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $data['project_manager_id'],
                    'role' => 'project_manager',
                ]);
            }

            // Automatically add the assigned client as a member
            if (isset($clientId)) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $clientId,
                    'role' => 'client',
                ]);
            }

            return $project;
        });
    }

    /**
     * Assign a team member to a project.
     */
    public function assignTeamMember(Project $project, User $user, string $role = 'team_member'): ProjectMember
    {
        return ProjectMember::firstOrCreate(
            [
                'project_id' => $project->id,
                'user_id' => $user->id,
            ],
            [
                'role' => $role,
            ]
        );
    }

    /**
     * Remove a team member from a project.
     */
    public function removeTeamMember(Project $project, User $user): bool
    {
        return ProjectMember::where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->delete() > 0;
    }

}
