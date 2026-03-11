<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Dashboard</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        @php
            $totalProjects = \App\Models\Project::count();
            $activeProjects = \App\Models\Project::where('status', 'active')->count();
            $totalTasks = \App\Models\Task::count();
            $completedTasks = \App\Models\Task::where('status', 'completed')->count();
        @endphp

        {{-- SECTION 1 — STATS GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Projects --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Projects</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProjects }}</p>
                </div>
                <div
                    class="h-12 w-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
            </div>

            {{-- Active Projects --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Active Projects</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $activeProjects }}</p>
                </div>
                <div
                    class="h-12 w-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>

            {{-- Total Tasks --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Tasks</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalTasks }}</p>
                </div>
                <div
                    class="h-12 w-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                </div>
            </div>

            {{-- Completed Tasks --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Completed Tasks</h3>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $completedTasks }}</p>
                </div>
                <div
                    class="h-12 w-12 bg-green-50 rounded-lg flex items-center justify-center text-green-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- SECTION 2 — CONTENT GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- SECTION 3 — RECENT PROJECTS CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Recent Projects</h2>
                    <a href="{{ route('projects.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View all</a>
                </div>
                <div class="p-6 flex-1">
                    <div class="space-y-6">
                        @forelse(\App\Models\Project::latest()->limit(5)->get() as $project)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-10 w-10 flex-shrink-0 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                        {{ strtoupper(substr($project->name, 0, 2)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <a href="{{ route('projects.show', $project->id) }}"
                                            class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                            {{ $project->name }}
                                        </a>
                                        <div class="flex items-center mt-1">
                                            <span
                                                class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 capitalize">
                                                {{ str_replace('_', ' ', $project->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end w-24">
                                    <span class="text-xs font-medium text-gray-700 mb-1.5">{{ $project->progress }}%</span>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-indigo-500 h-1.5 rounded-full"
                                            style="width: {{ $project->progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-500">No projects found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- SECTION 4 — RECENT ACTIVITY CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Recent Activity</h2>
                    <a href="{{ route('activity.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View all</a>
                </div>
                <div class="p-6 flex-1">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse(\App\Models\ActivityLog::with('user')->latest('created_at')->limit(5)->get() as $activity)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-100"
                                                aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center ring-8 ring-white">
                                                    @if($activity->user)
                                                        <span
                                                            class="text-xs font-bold text-indigo-600">{{ strtoupper(substr($activity->user->name, 0, 1)) }}</span>
                                                    @else
                                                        <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-xs text-gray-500">
                                                    <time
                                                        datetime="{{ $activity->created_at->toIso8601String() }}">{{ $activity->created_at->diffForHumans() }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-sm text-gray-500">No recent activity.</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>