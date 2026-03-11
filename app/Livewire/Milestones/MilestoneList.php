<?php

namespace App\Livewire\Milestones;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Milestone;
use App\Services\MilestoneService;

class MilestoneList extends Component
{
    public $projectId;
    public $milestones;

    // Create-form fields
    public $showForm = false;
    public $title = '';
    public $description = '';
    public $deadline = '';

    protected MilestoneService $milestoneService;

    public function boot(MilestoneService $milestoneService)
    {
        $this->milestoneService = $milestoneService;
    }

    public function mount($projectId)
    {
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');

        $this->projectId = $projectId;
        $this->loadMilestones();
    }

    #[On('task-created')]
    #[On('milestone-updated')]
    public function loadMilestones()
    {
        $this->milestones = Milestone::where('project_id', $this->projectId)
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => function ($q) {
                    $q->where('status', 'completed');
                }
            ])
            ->orderBy('deadline')
            ->get()
            ->map(function ($milestone) {
                $milestone->progress = $milestone->tasks_count > 0
                    ? (int) round(($milestone->completed_tasks_count / $milestone->tasks_count) * 100)
                    : 0;
                return $milestone;
            });
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        $this->resetValidation();
        $this->resetFormFields();
    }

    public function resetFormFields()
    {
        $this->title = '';
        $this->description = '';
        $this->deadline = '';
    }

    public function save()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $validated['project_id'] = $this->projectId;

        $this->milestoneService->createMilestone($validated);

        $this->showForm = false;
        $this->resetFormFields();
        $this->loadMilestones();
    }

    public function completeMilestone($milestoneId)
    {
        $milestone = Milestone::findOrFail($milestoneId);
        $this->milestoneService->completeMilestone($milestone);
        $this->loadMilestones();
    }

    public function render()
    {
        return view('livewire.milestones.milestone-list');
    }
}
