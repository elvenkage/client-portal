<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use App\Services\MilestoneService;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function __construct(
        protected MilestoneService $milestoneService,
    ) {}

    /**
     * Display milestones for a project.
     */
    public function index(Project $project)
    {
        $milestones = $project->milestones()
            ->with('tasks')
            ->latest()
            ->paginate(20);

        return view('milestones.index', compact('project', 'milestones'));
    }

    /**
     * Store a newly created milestone.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['project_id'] = $project->id;

        $milestone = $this->milestoneService->createMilestone($validated);

        return redirect()->route('projects.milestones.show', [$project, $milestone])
            ->with('success', 'Milestone created successfully.');
    }

    /**
     * Display the specified milestone.
     */
    public function show(Project $project, Milestone $milestone)
    {
        $milestone->load('tasks');

        return view('milestones.show', compact('project', 'milestone'));
    }

    /**
     * Update the specified milestone.
     */
    public function update(Request $request, Project $project, Milestone $milestone)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ]);

        $milestone->update($validated);

        return redirect()->route('projects.milestones.show', [$project, $milestone])
            ->with('success', 'Milestone updated successfully.');
    }

    /**
     * Soft-delete the specified milestone.
     */
    public function destroy(Project $project, Milestone $milestone)
    {
        $milestone->delete();

        return redirect()->route('projects.milestones.index', $project)
            ->with('success', 'Milestone deleted successfully.');
    }
}
