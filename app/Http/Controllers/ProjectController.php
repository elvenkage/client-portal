<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
    ) {}

    /**
     * Display a listing of projects.
     */
    public function index()
    {
        $projects = Project::with(['client', 'projectManager'])
            ->latest()
            ->paginate(20);

        return view('projects.index', compact('projects'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_manager_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:planning,in_progress,completed,on_hold,cancelled',
            'visibility' => 'nullable|in:draft,active,completed,archived',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'show_team_to_client' => 'nullable|boolean',
        ]);

        $project = $this->projectService->createProject($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $project->load(['client', 'projectManager', 'milestones', 'members.user']);

        return view('projects.show', compact('project'));
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:planning,in_progress,completed,on_hold,cancelled',
            'visibility' => 'nullable|in:draft,active,completed,archived',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'show_team_to_client' => 'nullable|boolean',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Soft-delete the specified project.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
