<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectStatusService
{
    public static function updateProjectStatus(Project $project): void
    {
        $totalTasks = $project->tasks()->count();

        $completedTasks = $project->tasks()
            ->where('status', 'completed')
            ->count();

        $activeTasks = $project->tasks()
            ->whereIn('status', ['in_progress', 'review'])
            ->count();

        if ($totalTasks === 0) {
            $status = 'planning';
        } elseif ($completedTasks === $totalTasks) {
            $status = 'completed';
        } elseif ($activeTasks > 0) {
            $status = 'in_progress';
        } else {
            $status = 'planning';
        }

        $progress = $totalTasks > 0
            ? (int) round(($completedTasks / $totalTasks) * 100)
            : 0;

        $oldStatus = $project->status;
        $statusChanged = $oldStatus !== $status;

        $project->status = $status;
        $project->progress = $progress;
        $project->save();

        Log::info('Project status recalculated', [
            'project_id' => $project->id,
            'status' => $status,
            'progress' => $progress,
        ]);

        if ($statusChanged) {
            app(ActivityLogService::class)->logProjectStatusChanged($project, $oldStatus, $status);
        }
    }
}
