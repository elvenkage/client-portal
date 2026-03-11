<div class="max-w-7xl mx-auto px-8 py-8 space-y-8">

    {{-- Header with Breadcrumb --}}
    <div>
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
            <a href="{{ route('client.dashboard') }}" class="hover:text-indigo-600 transition-colors">My Projects</a>
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span class="text-gray-900 font-medium">{{ $project->name }}</span>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">{{ $project->name }}</h1>
        @if($project->description)
            <p class="mt-1 text-sm text-gray-500">{{ $project->description }}</p>
        @endif
    </div>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Status</p>
            @php
                $statusColors = [
                    'active'    => 'text-emerald-700',
                    'on_hold'   => 'text-amber-700',
                    'completed' => 'text-blue-700',
                    'cancelled' => 'text-red-700',
                ];
            @endphp
            <p class="mt-3 text-sm font-semibold uppercase {{ $statusColors[$project->status] ?? 'text-gray-700' }}">
                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Progress</p>
            <p class="mt-3 text-2xl font-semibold text-gray-900">{{ $project->progress }}<span class="text-base text-gray-400 font-medium">%</span></p>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                <div class="h-1.5 rounded-full {{ $project->progress === 100 ? 'bg-emerald-500' : 'bg-gray-900' }}"
                    style="width: {{ $project->progress }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Deadline</p>
            <p class="mt-3 text-lg font-semibold text-gray-900">
                {{ $project->deadline ? $project->deadline->format('M d, Y') : '—' }}
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Deliverables</p>
            <div class="mt-3 space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Milestones</span>
                    <span class="font-medium text-gray-900">{{ $milestones->count() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Files</span>
                    <span class="font-medium text-gray-900">{{ $files->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Review Section --}}
    @if($tasksPendingReview->count() > 0)
        <div class="bg-indigo-50 rounded-xl border border-indigo-100 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h2 class="text-lg font-semibold text-indigo-900">Action Required: Deliverables for Review</h2>
            </div>
            
            <div class="space-y-3">
                @foreach($tasksPendingReview as $task)
                    <div class="bg-white rounded-lg p-4 border border-indigo-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">{{ $task->title }}</h3>
                            @if($task->description)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $task->description }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="requestRevision({{ $task->id }})" wire:confirm="Request revisions for this task?"
                                class="inline-flex justify-center rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Request Revision
                            </button>
                            <button wire:click="approveTask({{ $task->id }})" wire:confirm="Approve this task? This cannot be undone."
                                class="inline-flex justify-center rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Approve
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Milestones + Activity (2-column) --}}
    <div class="grid grid-cols-3 gap-6">

        {{-- Milestones (2/3 width) --}}
        <div class="col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Milestones</h2>
            </div>
            <div class="p-6 space-y-4">
                @forelse($milestones as $milestone)
                    <div class="rounded-lg border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $milestone->title }}</h3>
                                @php
                                    $badgeColors = [
                                        'pending'     => 'bg-gray-100 text-gray-700',
                                        'in_progress' => 'bg-blue-50 text-blue-700',
                                        'completed'   => 'bg-emerald-50 text-emerald-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider {{ $badgeColors[$milestone->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                </span>
                            </div>
                            @if($milestone->deadline)
                                <span class="text-xs text-gray-500">Due {{ $milestone->deadline->format('M d, Y') }}</span>
                            @endif
                        </div>

                        @if($milestone->description)
                            <p class="mt-1.5 text-xs text-gray-500">{{ $milestone->description }}</p>
                        @endif

                        <div class="mt-3">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                <span>{{ $milestone->completed_tasks_count }}/{{ $milestone->tasks_count }} deliverables</span>
                                <span class="font-medium">{{ $milestone->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all duration-500
                                    {{ $milestone->progress === 100 ? 'bg-emerald-500' : 'bg-blue-500' }}"
                                    style="width: {{ $milestone->progress }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
                        <p class="text-sm text-gray-500">No milestones yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Activity Timeline (1/3 width) --}}
        <div class="col-span-1 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <div class="flex gap-3">
                            @php
                                $iconConfig = match($activity->action) {
                                    'task_completed'      => ['bg' => 'bg-emerald-100 text-emerald-600', 'icon' => '✓'],
                                    'file_uploaded'       => ['bg' => 'bg-indigo-100 text-indigo-600',   'icon' => '📎'],
                                    'milestone_completed' => ['bg' => 'bg-blue-100 text-blue-600',      'icon' => '🏁'],
                                    default               => ['bg' => 'bg-gray-100 text-gray-600',      'icon' => '•'],
                                };
                            @endphp
                            <span class="flex h-7 w-7 items-center justify-center rounded-full {{ $iconConfig['bg'] }} text-xs flex-shrink-0 mt-0.5">
                                {{ $iconConfig['icon'] }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-gray-700 leading-snug">{{ $activity->description }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">No activity yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Files --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Files</h2>
        </div>
        <div class="p-6 space-y-2">
            @forelse($files as $file)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        @if($file->image_path)
                            <div class="h-10 w-10 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ Storage::url($file->image_path) }}" alt="Preview" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ basename($file->image_path) }}</p>
                                <p class="text-xs text-gray-500">{{ $file->uploader->name ?? 'System' }} · {{ $file->created_at->diffForHumans() }}</p>
                            </div>
                        @elseif($file->file_path)
                            @php
                                $iconColors = [
                                    'pdf' => 'bg-red-50 text-red-600',
                                    'docx' => 'bg-blue-50 text-blue-600',
                                    'zip' => 'bg-amber-50 text-amber-600',
                                    'png' => 'bg-emerald-50 text-emerald-600',
                                    'jpg' => 'bg-emerald-50 text-emerald-600',
                                ];
                                $iconColor = $iconColors[$file->file_type] ?? 'bg-gray-50 text-gray-600';
                            @endphp
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $iconColor }} flex-shrink-0">
                                <span class="text-[10px] font-bold uppercase">{{ $file->file_type }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $file->file_name }}</p>
                                <p class="text-xs text-gray-500">{{ $file->uploader->name ?? 'System' }} · {{ $file->created_at->diffForHumans() }}</p>
                            </div>
                        @else
                            <svg class="h-5 w-5 text-gray-400 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.232 4.232a2.5 2.5 0 0 1 3.536 3.536l-1.225 1.224a.75.75 0 0 0 1.061 1.06l1.224-1.224a4 4 0 0 0-5.656-5.656l-3 3a4 4 0 0 0 .225 5.865.75.75 0 0 0 .977-1.138 2.5 2.5 0 0 1-.142-3.667l3-3Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M11.603 7.963a.75.75 0 0 0-.977 1.138 2.5 2.5 0 0 1 .142 3.667l-3 3a2.5 2.5 0 0 1-3.536-3.536l1.225-1.224a.75.75 0 0 0-1.061-1.06l-1.224 1.224a4 4 0 1 0 5.656 5.656l3-3a4 4 0 0 0-.225-5.865Z" clip-rule="evenodd" />
                            </svg>
                            <div class="min-w-0 flex-1">
                                <a href="{{ $file->external_link }}" target="_blank" rel="noopener noreferrer"
                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800 truncate block">
                                    {{ str($file->external_link)->limit(50) }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $file->uploader->name ?? 'System' }} · {{ $file->created_at->diffForHumans() }}</p>
                            </div>
                        @endif
                    </div>

                    @if($file->image_path)
                        <a href="{{ route('files.download', $file->id) }}"
                            class="text-xs font-medium text-indigo-600 hover:text-indigo-800 px-2 py-1 rounded hover:bg-indigo-50 transition-colors ml-4">
                            Download
                        </a>
                    @elseif($file->file_path)
                        <a href="{{ route('files.download', $file->id) }}"
                            class="text-xs font-medium text-indigo-600 hover:text-indigo-800 px-2 py-1 rounded hover:bg-indigo-50 transition-colors ml-4">
                            Download
                        </a>
                    @endif
                </div>
            @empty
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
                    <p class="text-sm text-gray-500">No files uploaded yet.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
