<div class="max-w-7xl mx-auto px-8 py-8 space-y-8">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">My Projects</h1>
        <p class="mt-1 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}.</p>
    </div>

    {{-- Project Cards Grid --}}
    <div class="grid grid-cols-2 gap-6">
        @forelse($projects as $project)
            <a href="{{ route('client.projects.show', $project->id) }}"
               class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 hover:shadow-md hover:border-gray-300 transition-all group">

                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-900 text-white font-semibold text-sm">
                            {{ strtoupper(substr($project->name, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $project->name }}</h2>
                            @if($project->description)
                                <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $project->description }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Status Badge --}}
                    @php
                        $statusColors = [
                            'active'    => 'bg-emerald-50 text-emerald-700',
                            'on_hold'   => 'bg-amber-50 text-amber-700',
                            'completed' => 'bg-blue-50 text-blue-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                        <span class="font-medium">Progress</span>
                        <span class="font-semibold text-gray-900">{{ $project->progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all duration-500
                            {{ $project->progress === 100 ? 'bg-emerald-500' : 'bg-gray-900' }}"
                            style="width: {{ $project->progress }}%"></div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="flex items-center gap-5 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"/></svg>
                        {{ $project->milestones_count }} milestones
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        {{ $project->files_count }} files
                    </span>
                    @if($project->deadline)
                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                            Due {{ $project->deadline->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-2 border-2 border-dashed border-gray-200 rounded-xl p-12 text-center">
                <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/></svg>
                <p class="text-sm font-medium text-gray-900">No projects</p>
                <p class="text-xs text-gray-500 mt-1">You don't have any projects yet.</p>
            </div>
        @endforelse
    </div>

</div>
