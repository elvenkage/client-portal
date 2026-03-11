<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Projects</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header Actions --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Projects</h1>
            <button @click="Livewire.dispatch('openCreateProjectModal')"
                class="bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                + New Project
            </button>
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-3">
            <input type="text" placeholder="Search projects..."
                class="border-gray-200 rounded-lg text-sm flex-1 max-w-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <select
                class="border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option>All Statuses</option>
                <option>Active</option>
                <option>Completed</option>
            </select>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 font-medium">Project</th>
                        <th class="px-6 py-3.5 font-medium">Manager</th>
                        <th class="px-6 py-3.5 font-medium">Status</th>
                        <th class="px-6 py-3.5 font-medium">Progress</th>
                        <th class="px-6 py-3.5 font-medium">Deadline</th>
                        <th class="px-6 py-3.5 font-medium">Tasks</th>
                        <th class="px-6 py-3.5 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(\App\Models\Project::with(['projectManager', 'tasks'])->latest()->get() as $project)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="{{ route('projects.show', $project->id) }}"
                                    class="font-medium text-gray-900 hover:text-indigo-600 transition-colors">{{ $project->name }}</a>
                            </td>
                            <td class="px-6 py-4">{{ $project->projectManager->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 capitalize">
                                    {{ str_replace('_', ' ', $project->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-20 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-indigo-500 h-1.5 rounded-full"
                                            style="width: {{ $project->progress }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs font-medium text-gray-700 tabular-nums">{{ $project->progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $project->deadline ? $project->deadline->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 tabular-nums">{{ $project->tasks->count() }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('projects.show', $project->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                    </svg>
                                    <span class="text-sm">No projects yet</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Create Project Modal --}}
        <livewire:projects.create-project-modal />

    </div>
</x-app-layout>