<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Team</h1>
            <button wire:click="openModal"
                class="bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                + Invite User
            </button>
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users by name or email..."
                class="border-gray-200 rounded-lg text-sm flex-1 max-w-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <select wire:model.live="roleFilter"
                class="border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="project_manager">Project Manager</option>
                <option value="team_member">Team Member</option>
                <option value="client">Client</option>
            </select>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 font-medium">Name</th>
                        <th class="px-6 py-3.5 font-medium">Email</th>
                        <th class="px-6 py-3.5 font-medium">Role</th>
                        <th class="px-6 py-3.5 font-medium">Projects</th>
                        <th class="px-6 py-3.5 font-medium">Tasks</th>
                        <th class="px-6 py-3.5 font-medium">Joined</th>
                        <th class="px-6 py-3.5 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="capitalize text-xs font-medium px-2 py-1 rounded-md bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 tabular-nums">{{ $user->projects_count ?? 0 }}</td>
                            <td class="px-6 py-4 tabular-nums">{{ $user->tasks_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->created_at?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="editUser({{ $user->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                                @if($user->id !== auth()->id() && $user->role !== 'super_admin')
                                    <button wire:click="confirmDelete({{ $user->id }})"
                                        class="text-red-500 hover:text-red-700 text-sm font-medium ml-1">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- User Create/Edit Modal --}}
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" aria-hidden="true" wire:click="closeModal">
                </div>
                <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-xl w-full max-w-lg">
                    <form wire:submit="saveUser">
                        <div class="px-6 pt-6 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4" id="modal-title">
                                {{ $userId ? 'Edit User' : 'Invite New User' }}
                            </h3>

                            @if(!$userId)
                                <div class="bg-blue-50 border border-blue-100 text-blue-700 px-4 py-3 rounded-lg text-sm mb-4">
                                    The user will receive an email with a link to set their password.
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" wire:model="name" id="name"
                                        class="mt-1.5 block w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" wire:model="email" id="email"
                                        class="mt-1.5 block w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                                    <select wire:model="role" id="role"
                                        class="mt-1.5 block w-full border-gray-200 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <option value="client">Client</option>
                                        <option value="team_member">Team Member</option>
                                        <option value="project_manager">Project Manager</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    @error('role') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 shadow-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 shadow-sm">
                                {{ $userId ? 'Save Changes' : 'Send Invitation' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($isConfirmingDelete)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" aria-hidden="true" wire:click="closeModal">
                </div>
                <div class="relative bg-white rounded-xl text-left overflow-hidden shadow-xl w-full max-w-lg">
                    <div class="px-6 pt-6 pb-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-50">
                                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Delete User</h3>
                                <p class="mt-2 text-sm text-gray-500">Are you sure you want to delete this user? All of
                                    their data will be permanently removed. This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 shadow-sm">
                            Cancel
                        </button>
                        <button type="button" wire:click="deleteUser"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm">
                            Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>