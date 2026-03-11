<div>
    @if($isOpen)
        {{-- Overlay --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-transition
            @keydown.escape.window="$wire.closeModal()">

            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/40 transition-opacity" wire:click="closeModal"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-xl shadow-lg w-full max-w-lg overflow-hidden z-10">

                {{-- Header --}}
                <div class="px-6 pt-6 pb-0 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">New Project</h3>
                    <button wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-1 hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form wire:submit="save">
                    <div class="px-6 py-5 space-y-4 max-h-[65vh] overflow-y-auto">

                        {{-- Project Name --}}
                        <div>
                            <label for="project-name" class="block text-sm font-medium text-gray-700 mb-1.5">Project Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" id="project-name" placeholder="e.g. Website Redesign"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Client --}}
                        <div>
                            <label for="project-client"
                                class="block text-sm font-medium text-gray-700 mb-1.5">Client</label>
                            <select wire:model="client_id" id="project-client"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select a client...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Project Manager --}}
                        <div>
                            <label for="project-manager" class="block text-sm font-medium text-gray-700 mb-1.5">Project
                                Manager</label>
                            <select wire:model="project_manager_id" id="project-manager"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select manager...</option>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            @error('project_manager_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Dates row --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="project-start" class="block text-sm font-medium text-gray-700 mb-1.5">Start
                                    Date</label>
                                <input type="date" wire:model="start_date" id="project-start"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="project-deadline"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">Deadline</label>
                                <input type="date" wire:model="deadline" id="project-deadline"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @error('deadline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="project-desc"
                                class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea wire:model="description" id="project-desc" rows="3"
                                placeholder="Brief project description..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors shadow-sm"
                            wire:loading.attr="disabled" wire:loading.class="opacity-60">
                            <span wire:loading.remove wire:target="save">Create Project</span>
                            <span wire:loading wire:target="save">Creating...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>