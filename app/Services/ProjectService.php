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
            $project = Project::create($data);

            // Automatically add the project manager as a member
            if (isset($data['project_manager_id'])) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $data['project_manager_id'],
                    'role' => 'project_manager',
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

    /**
     * Recalculate and persist project progress based on task completion.
     *
     * Formula: completed_tasks / total_tasks × 100
     */
    public function calculateProgress(Project $project): int
    {
        $totalTasks = $project->tasks()->count();

        if ($totalTasks === 0) {
            $project->update(['progress' => 0]);
            return 0;
        }

        $completedTasks = $project->tasks()->where('status', 'completed')->count();
        $progress = (int) round(($completedTasks / $totalTasks) * 100);

        $project->update(['progress' => $progress]);

        return $progress;
    }
}
