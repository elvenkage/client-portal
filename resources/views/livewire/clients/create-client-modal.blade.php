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
                    <h3 class="text-lg font-semibold text-gray-900">New Client</h3>
                    <button wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-1 hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <form wire:submit="save">
                    <div class="px-6 py-5 space-y-4">

                        {{-- Client Name --}}
                        <div>
                            <label for="client-name" class="block text-sm font-medium text-gray-700 mb-1.5">Client Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" id="client-name" placeholder="e.g. John Doe"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Company --}}
                        <div>
                            <label for="client-company"
                                class="block text-sm font-medium text-gray-700 mb-1.5">Company</label>
                            <input type="text" wire:model="company_name" id="client-company" placeholder="e.g. Acme Corp"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="client-email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" wire:model="email" id="client-email" placeholder="john@acme.com"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label for="client-phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                            <input type="text" wire:model="phone" id="client-phone" placeholder="+1 (555) 000-0000"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="client-notes" class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                            <textarea wire:model="notes" id="client-notes" rows="3" placeholder="Any additional notes..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                            @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                            <span wire:loading.remove wire:target="save">Create Client</span>
                            <span wire:loading wire:target="save">Creating...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>