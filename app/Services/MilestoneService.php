<?php

namespace App\Services;

use App\Models\Milestone;
use App\Models\Project;

class MilestoneService
{
    public function __construct(
        protected ProjectService $projectService,
        protected NotificationService $notificationService,
        protected ActivityLogService $activityLogService,
    ) {
    }

    /**
     * Create a new milestone.
     *
     * @param  array<string, mixed>  $data
     */
    public function createMilestone(array $data): Milestone
    {
        return Milestone::create($data);
    }

    /**
     * Complete a milestone (manual override by Project Manager).
     */
    public function completeMilestone(Milestone $milestone): Milestone
    {
        $milestone->update([
            'status' => 'completed',
            'manual_override' => true,
        ]);

        $this->notificationService->notifyMilestoneCompleted($milestone);
        $this->activityLogService->logMilestoneCompleted($milestone);

        // Recalculate parent project progress
        $this->projectService->calculateProgress($milestone->project);

        return $milestone;
    }

    /**
     * Calculate milestone progress based on its tasks.
     *
     * Returns progress percentage (0–100).
     * If all tasks are completed and manual_override is false, auto-completes the milestone.
     */
    public function calculateProgress(Milestone $milestone): int
    {
        $totalTasks = $milestone->tasks()->count();

        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $milestone->tasks()->where('status', 'completed')->count();
        $progress = (int) round(($completedTasks / $totalTasks) * 100);

        // Save progress explicitly
        $milestone->update(['progress' => $progress]);

        // Auto-complete milestone when all tasks are done (unless manually overridden)
        if ($progress === 100 && !$milestone->manual_override && $milestone->status !== 'completed') {
            $milestone->update(['status' => 'completed']);
            $this->notificationService->notifyMilestoneCompleted($milestone);
            $this->activityLogService->logMilestoneCompleted($milestone);
        }

        // Auto-set to in_progress if any task has started
        if ($progress > 0 && $progress < 100 && $milestone->status === 'pending') {
            $milestone->update(['status' => 'in_progress']);
        }

        return $progress;
    }
}
