<div>
    @if($isOpen)
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity" wire:click="closeModal"></div>

        {{-- Modal --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg" @keydown.escape.window="$wire.closeModal()">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Create New Task</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form wire:submit="save">
                    <div class="px-6 py-5 space-y-5">

                        {{-- Title --}}
                        <div>
                            <label for="title"
                                class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="title" id="title" placeholder="Task title..."
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description"
                                class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                Description
                            </label>
                            <textarea wire:model="description" id="description" rows="3" placeholder="Add a description..."
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Priority & Status (2-col) --}}
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Priority --}}
                            <div>
                                <label for="priority"
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    Priority
                                </label>
                                <select wire:model="priority" id="priority"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                                @error('priority') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status"
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    Status
                                </label>
                                <select wire:model="status" id="status"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="todo">To Do</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="review">Review</option>
                                    <option value="completed">Completed</option>
                                </select>
                                @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Assignee & Milestone (2-col) --}}
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Assignee --}}
                            <div>
                                <label for="assigned_to"
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    Assignee
                                </label>
                                <select wire:model="assigned_to" id="assigned_to"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Milestone --}}
                            <div>
                                <label for="milestone_id"
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                    Milestone
                                </label>
                                <select wire:model="milestone_id" id="milestone_id"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">None</option>
                                    @foreach($milestones as $milestone)
                                        <option value="{{ $milestone->id }}">{{ $milestone->title }}</option>
                                    @endforeach
                                </select>
                                @error('milestone_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Deadline --}}
                        <div>
                            <label for="deadline"
                                class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                                Deadline
                            </label>
                            <input type="date" wire:model="deadline" id="deadline"
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('deadline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div
                        class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            Create Task
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>