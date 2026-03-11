<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    /**
     * Display tasks for a project.
     */
    public function index(Project $project)
    {
        $tasks = $project->tasks()
            ->with(['assignee', 'milestone'])
            ->latest()
            ->paginate(20);

        return view('tasks.index', compact('project', 'tasks'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:todo,in_progress,review,completed',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'client_review_required' => 'nullable|boolean',
            'client_review_deadline' => 'nullable|date',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['project_id'] = $project->id;

        $task = $this->taskService->createTask($validated);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show(Project $project, Task $task)
    {
        $task->load(['assignee', 'milestone', 'subtasks', 'comments.user', 'files']);

        return view('tasks.show', compact('project', 'task'));
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $validated = $request->validate([
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:todo,in_progress,review,completed',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'client_review_required' => 'nullable|boolean',
            'client_review_deadline' => 'nullable|date',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ]);

        $task->update($validated);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Soft-delete the specified task.
     */
    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return redirect()->route('projects.tasks.index', $project)
            ->with('success', 'Task deleted successfully.');
    }

    // ──────────────────────────────────────────────
    //  Review Workflow Actions
    // ──────────────────────────────────────────────

    /**
     * Team member submits task for review.
     */
    public function submitForReview(Project $project, Task $task)
    {
        $this->taskService->submitForReview($task);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task submitted for review.');
    }

    /**
     * Project Manager approves a task.
     */
    public function approveTask(Project $project, Task $task)
    {
        $this->taskService->approveTask($task);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task approved.');
    }

    /**
     * Project Manager requests revision.
     */
    public function requestRevision(Project $project, Task $task)
    {
        $this->taskService->requestRevision($task);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task sent back for revision.');
    }

    /**
     * Project Manager sends task to client review.
     */
    public function sendToClientReview(Project $project, Task $task)
    {
        $this->taskService->sendToClientReview($task);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task sent to client for review.');
    }

    /**
     * Client approves a task.
     */
    public function clientApprove(Project $project, Task $task)
    {
        $this->taskService->clientApprove($task);

        return redirect()->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task approved by client.');
    }
}
