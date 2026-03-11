<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Milestones</h2>
        <button wire:click="toggleForm"
            class="inline-flex items-center rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition-colors">
            @if($showForm) Cancel @else + New Milestone @endif
        </button>
    </div>

    <!-- Inline Create/Edit Form -->
    @if($showForm)
        <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label for="ms-title" class="block text-sm font-medium leading-6 text-gray-900">Title <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="title" id="ms-title"
                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    @error('title') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="ms-description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <textarea wire:model="description" id="ms-description" rows="2"
                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="ms-deadline" class="block text-sm font-medium leading-6 text-gray-900">Deadline</label>
                    <input type="date" wire:model="deadline" id="ms-deadline"
                        class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    @error('deadline') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="rounded-md bg-gray-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800">
                        {{ $editingMilestoneId ? 'Update Milestone' : 'Create Milestone' }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Milestone Cards -->
    @forelse($milestones as $milestone)
        <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $milestone->title }}</h3>

                        {{-- Status badge --}}
                        @php
                            $badgeColors = [
                                'pending'     => 'bg-gray-100 text-gray-700',
                                'in_progress' => 'bg-blue-100 text-blue-700',
                                'completed'   => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $badgeColors[$milestone->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                        </span>
                    </div>

                    @if($milestone->description)
                        <p class="mt-1 text-sm text-gray-500">{{ $milestone->description }}</p>
                    @endif

                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-500">
                        @if($milestone->deadline)
                            <span>
                                📅 {{ $milestone->deadline->format('M d, Y') }}
                                @if(!$milestone->isCompleted() && $milestone->deadline->isPast())
                                    <span class="text-red-500 font-medium">Overdue</span>
                                @endif
                            </span>
                        @endif
                        <span>{{ $milestone->completed_tasks_count }}/{{ $milestone->tasks_count }} tasks</span>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="ml-4 flex items-center gap-2">
                    @if($milestone->status !== 'completed')
                        <button wire:click="editMilestone({{ $milestone->id }})"
                            class="rounded-md bg-gray-50 px-2.5 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-100 transition-colors">
                            Edit
                        </button>
                        <button wire:click="completeMilestone({{ $milestone->id }})"
                            wire:confirm="Mark this milestone as complete?"
                            class="rounded-md bg-green-50 px-2.5 py-1.5 text-xs font-semibold text-green-700 shadow-sm hover:bg-green-100 transition-colors">
                            ✓ Complete
                        </button>
                    @endif
                    <button wire:click="deleteMilestone({{ $milestone->id }})"
                        wire:confirm="Delete this milestone? This cannot be undone."
                        class="rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-700 shadow-sm hover:bg-red-100 transition-colors">
                        Delete
                    </button>
                </div>
            </div>

            <!-- Progress bar -->
            <div class="mt-3">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ $milestone->progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500
                        {{ $milestone->progress === 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                        style="width: {{ $milestone->progress }}%">
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
            No milestones yet. Create one to start tracking progress.
        </div>
    @endforelse
</div>
