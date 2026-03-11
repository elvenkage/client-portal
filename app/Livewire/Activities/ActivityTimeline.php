<?php

namespace App\Livewire\Activities;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\ActivityLog;

class ActivityTimeline extends Component
{
    use WithPagination;

    public $projectId;

    public function mount($projectId)
    {
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');

        $this->projectId = $projectId;
        $this->projectId = $projectId;
    }

    #[On('task-created')]
    #[On('milestone-updated')]
    public function render()
    {
        $activities = ActivityLog::where('project_id', $this->projectId)
            ->with('user')
            ->latest()
            ->paginate(5);

        return view('livewire.activities.activity-timeline', [
            'activities' => $activities
        ]);
    }
}
