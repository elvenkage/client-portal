<div wire:ignore>
    <div class="flex gap-6 overflow-x-auto pb-4 -mb-4" x-data="kanbanBoard()" x-init="initSortable()">

        @php
            $columns = [
                'todo' => 'To Do',
                'in_progress' => 'In Progress',
                'review' => 'Review',
                'completed' => 'Completed'
            ];
            $columnColors = [
                'todo' => 'bg-gray-400',
                'in_progress' => 'bg-blue-500',
                'review' => 'bg-amber-500',
                'completed' => 'bg-emerald-500'
            ];
        @endphp

        @foreach($columns as $status => $title)
            {{-- Column --}}
            <div class="min-w-[280px] w-80 flex-shrink-0 bg-gray-50 rounded-xl p-4 min-h-[500px] flex flex-col">

                {{-- Column Header --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full {{ $columnColors[$status] }}"></span>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $title }}</h3>
                    </div>
                    <span class="bg-gray-200 text-gray-600 py-0.5 px-2.5 rounded-full text-xs font-medium tabular-nums">
                        {{ isset($tasksByStatus[$status]) ? $tasksByStatus[$status]->count() : 0 }}
                    </span>
                </div>

                {{-- Sortable Task Cards Container --}}
                <div class="kanban-column flex flex-col gap-2.5 flex-1 rounded-lg min-h-[60px] transition-colors duration-150"
                    data-status="{{ $status }}">

                    @foreach($tasksByStatus[$status] ?? [] as $task)
                        {{-- Task Card --}}
                        <div class="kanban-task bg-white border border-gray-200 rounded-lg p-4 shadow-sm
                                                                    hover:shadow-md hover:border-gray-300
                                                                    cursor-grab active:cursor-grabbing
                                                                    transition-all duration-150 ease-in-out"
                            data-task-id="{{ $task->id }}" wire:click="$dispatch('openTaskModal', { taskId: {{ $task->id }} })">

                            {{-- Priority Badge --}}
                            @if($task->priority)
                                @php
                                    $priorityStyles = [
                                        'low' => 'bg-gray-100 text-gray-600',
                                        'medium' => 'bg-amber-50 text-amber-700',
                                        'high' => 'bg-red-50 text-red-700',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-md mb-2.5 {{ $priorityStyles[$task->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $task->priority }}
                                </span>
                            @endif

                            {{-- Task Title --}}
                            <h4 class="text-sm font-medium text-gray-900 leading-snug">{{ $task->title }}</h4>

                            {{-- Task Description (truncated) --}}
                            @if($task->description)
                                <p class="mt-1.5 text-xs text-gray-500 line-clamp-2">{{ $task->description }}</p>
                            @endif

                            {{-- Metadata Row --}}
                            <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3">
                                <div class="flex items-center gap-2">
                                    {{-- Task ID --}}
                                    <span class="text-[11px] font-mono font-medium text-gray-400">#{{ $task->id }}</span>

                                    {{-- Deadline --}}
                                    @if($task->deadline)
                                        <span
                                            class="flex items-center gap-1 text-[11px] text-gray-500 {{ $task->deadline->isPast() && $status !== 'completed' ? 'text-red-600 font-medium' : '' }}">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                            </svg>
                                            {{ $task->deadline->format('M d') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Assignee Avatar --}}
                                @if($task->assignee)
                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold ring-2 ring-white"
                                        title="{{ $task->assignee->name }}">
                                        {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    @endforeach

                    @if(!isset($tasksByStatus[$status]) || $tasksByStatus[$status]->count() === 0)
                        <div
                            class="kanban-empty flex-1 flex items-center justify-center border-2 border-dashed border-gray-200 rounded-lg text-gray-400 text-xs text-center p-6 min-h-[120px]">
                            Drop tasks here
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@script
<script>
    Alpine.data('kanbanBoard', () => ({
        sortables: [],

        initSortable() {
            this.$nextTick(() => {
                import('https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/+esm').then((module) => {
                    const Sortable = module.default;
                    const columns = this.$root.querySelectorAll('.kanban-column');

                    columns.forEach(column => {
                        const sortable = Sortable.create(column, {
                            group: 'kanban',
                            animation: 200,
                            easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
                            draggable: '.kanban-task',
                            ghostClass: 'kanban-ghost',
                            chosenClass: 'kanban-chosen',
                            dragClass: 'kanban-drag',
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            delay: 0,
                            delayOnTouchOnly: true,

                            onStart: (evt) => {
                                document.body.classList.add('is-dragging');
                                document.querySelectorAll('.kanban-empty').forEach(el => {
                                    el.style.display = 'none';
                                });
                            },

                            onEnd: (evt) => {
                                document.body.classList.remove('is-dragging');
                                document.querySelectorAll('.kanban-column').forEach(col => {
                                    const tasks = col.querySelectorAll('.kanban-task');
                                    const empty = col.querySelector('.kanban-empty');
                                    if (empty) {
                                        empty.style.display = tasks.length === 0 ? 'flex' : 'none';
                                    }
                                });

                                const taskId = evt.item.dataset.taskId;
                                const newStatus = evt.to.dataset.status;

                                if (taskId && newStatus) {
                                    @this.call('updateTaskStatus', parseInt(taskId), newStatus);
                                }
                            },
                        });

                        this.sortables.push(sortable);
                    });
                });
            });
        },
    }));
</script>
@endscript

@push('styles')
    <style>
        .kanban-ghost {
            opacity: 0.35;
            background: #e0e7ff !important;
            border: 2px dashed #818cf8 !important;
            border-radius: 0.5rem;
            box-shadow: none !important;
        }

        .kanban-chosen {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important;
            transform: rotate(1.5deg);
            border-color: #6366f1 !important;
            z-index: 50;
        }

        .kanban-drag {
            opacity: 0.95;
        }

        .is-dragging .kanban-column {
            background-color: rgba(238, 242, 255, 0.3);
            border: 2px dashed transparent;
            transition: all 0.2s ease;
        }

        .is-dragging .kanban-column:hover {
            background-color: rgba(238, 242, 255, 0.6);
            border-color: #c7d2fe;
        }
    </style>
@endpush