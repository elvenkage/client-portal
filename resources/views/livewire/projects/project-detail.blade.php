<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- ═══════════ HEADER ═══════════ --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-900 text-white font-semibold text-sm shadow-sm">
                {{ strtoupper(substr($project->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">{{ $project->name }}</h1>
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-medium">Project Overview</p>
            </div>
        </div>

        <button wire:click="$dispatch('openCreateTaskModal')"
            class="bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-gray-800 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
            + New Task
        </button>
    </div>

    {{-- ═══════════ OVERVIEW CARDS ═══════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Status --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</p>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin' || auth()->user()->isProjectManager())
                    <select wire:change="updateProjectStatus($event.target.value)"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 font-semibold uppercase">
                        <option value="planning" {{ $project->status === 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="in_progress" {{ $project->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                @else
                    <p class="mt-2 text-sm font-semibold text-gray-900 uppercase">
                        {{ str_replace('_', ' ', $project->status) }}
                    </p>
                @endif
            </div>
            <div class="h-10 w-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Progress --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ $project->progress }}<span
                    class="text-base text-gray-400 font-medium">%</span></p>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                <div class="h-1.5 rounded-full {{ $project->progress === 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                    style="width: {{ $project->progress }}%"></div>
            </div>
        </div>

        {{-- Deadline --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Deadline</p>
                <p class="mt-2 text-lg font-semibold text-gray-900">
                    {{ $project->deadline ? $project->deadline->format('M d, Y') : '—' }}
                </p>
            </div>
            <div class="h-10 w-10 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5">
                    </path>
                </svg>
            </div>
        </div>

        {{-- Overview --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Overview</p>
            <div class="mt-3 space-y-2.5">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Milestones</span>
                    <span class="font-semibold text-gray-900 tabular-nums">{{ $project->milestones->count() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Tasks</span>
                    <span class="font-semibold text-gray-900 tabular-nums">{{ $project->tasks->count() }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══════════ PROJECT TEAM ═══════════ --}}
    <livewire:projects.project-members :projectId="$project->id" />

    {{-- ═══════════ MILESTONES + ACTIVITY ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Milestones --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-base font-semibold text-gray-900">Milestones</h2>
            </div>
            <div class="p-6 flex-1">
                @if($project->milestones->count() > 0)
                    <livewire:milestones.milestone-list :projectId="$project->id" />
                @else
                    <div class="text-center py-8 text-sm text-gray-400">No milestones yet</div>
                @endif
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-base font-semibold text-gray-900">Activity</h2>
            </div>
            <div class="p-6 flex-1">
                <livewire:activities.activity-timeline :projectId="$project->id" />
            </div>
        </div>

    </div>

    {{-- ═══════════ FILES / DELIVERABLES ═══════════ --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6">
            <livewire:files.upload-project-file :projectId="$project->id" />
        </div>
    </div>

    {{-- ═══════════ KANBAN TASK BOARD ═══════════ --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-base font-semibold text-gray-900">Task Board</h2>
        </div>
        <div class="p-6 bg-gray-50/30 rounded-b-xl">
            <livewire:tasks.task-board :projectId="$project->id" />
        </div>
    </div>

    {{-- Create Task Modal --}}
    <livewire:tasks.create-task-modal :projectId="$project->id" />

    {{-- Trello-style Task Detail Modal --}}
    <livewire:tasks.task-modal />

</div>