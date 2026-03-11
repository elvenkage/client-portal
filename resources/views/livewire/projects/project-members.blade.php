<div class="space-y-6">

    {{-- ═══════════ PROJECT TEAM ═══════════ --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-base font-semibold text-gray-900">Project Team</h2>
            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                {{ $memberCount }} Members
            </span>
        </div>

        {{-- Add Member Form --}}
        @if(auth()->user()->isProjectManager() || auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin')
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <form wire:submit.prevent="addMember" class="flex gap-3">
                    <select wire:model="selectedUserId"
                        class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">Select user to add...</option>
                        @foreach($availableTeamMembers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ str_replace('_', ' ', $user->role) }})</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50"
                        wire:loading.attr="disabled">
                        Add
                    </button>
                </form>
            </div>
        @endif

        {{-- Team Members List --}}
        <ul role="list" class="divide-y divide-gray-100">
            {{-- PM (always shown at top) --}}
            <li class="flex items-center justify-between gap-x-6 py-3 px-6 hover:bg-gray-50">
                <div class="flex min-w-0 gap-x-4">
                    <div
                        class="h-8 w-8 flex-shrink-0 bg-indigo-100 text-indigo-700 flex items-center justify-center rounded-full font-bold text-xs uppercase">
                        {{ substr($project->projectManager->name ?? 'PM', 0, 2) }}
                    </div>
                    <div class="min-w-0 flex-auto">
                        <p class="text-sm font-semibold leading-6 text-gray-900">
                            {{ $project->projectManager->name ?? 'Unknown' }}</p>
                        <p class="mt-1 truncate text-xs leading-5 text-gray-500">Project Manager</p>
                    </div>
                </div>
            </li>

            {{-- Other Team Members --}}
            @foreach($teamMembers as $member)
                @if($member->id !== $project->project_manager_id)
                    <li class="flex items-center justify-between gap-x-6 py-3 px-6 hover:bg-gray-50 transition-colors">
                        <div class="flex min-w-0 gap-x-4">
                            <div
                                class="h-8 w-8 flex-shrink-0 bg-gray-100 text-gray-600 flex items-center justify-center rounded-full font-bold text-xs uppercase">
                                {{ substr($member->name, 0, 2) }}
                            </div>
                            <div class="min-w-0 flex-auto">
                                <p class="text-sm font-semibold leading-6 text-gray-900">{{ $member->name }}</p>
                                <p class="mt-1 text-xs leading-5 text-gray-500 capitalize">
                                    {{ str_replace('_', ' ', $member->pivot->role) }}</p>
                            </div>
                        </div>

                        @if(auth()->user()->isProjectManager() || auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin')
                            <button wire:click="removeMember({{ $member->id }})" wire:confirm="Remove this member from the project?"
                                class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Remove
                            </button>
                        @endif
                    </li>
                @endif
            @endforeach

            @if($teamMembers->count() === 0 || ($teamMembers->count() === 1 && $teamMembers->first()->id === $project->project_manager_id))
                {{-- Only PM, no additional team members --}}
            @endif
        </ul>
    </div>

    {{-- ═══════════ PROJECT CLIENTS ═══════════ --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-base font-semibold text-gray-900">Project Clients</h2>
            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                {{ $clientMembers->count() }} {{ Str::plural('Client', $clientMembers->count()) }}
            </span>
        </div>

        {{-- Add Client Form --}}
        @if(auth()->user()->isProjectManager() || auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin')
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <form wire:submit.prevent="addClient" class="flex gap-3">
                    <select wire:model="selectedClientId"
                        class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">Select client to add...</option>
                        @foreach($availableClients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50"
                        wire:loading.attr="disabled">
                        Add
                    </button>
                </form>
            </div>
        @endif

        <ul role="list" class="divide-y divide-gray-100">
            @forelse($clientMembers as $client)
                <li class="flex items-center justify-between gap-x-6 py-3 px-6 hover:bg-gray-50 transition-colors">
                    <div class="flex min-w-0 gap-x-4">
                        <div
                            class="h-8 w-8 flex-shrink-0 bg-indigo-50 text-indigo-600 flex items-center justify-center rounded-full font-bold text-xs uppercase">
                            {{ substr($client->name, 0, 2) }}
                        </div>
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ $client->name }}</p>
                            <p class="mt-1 text-xs leading-5 text-gray-500">{{ $client->email ?? 'No email' }}</p>
                        </div>
                    </div>

                    @if(auth()->user()->isProjectManager() || auth()->user()->isSuperAdmin() || auth()->user()->role === 'admin')
                        <button wire:click="removeMember({{ $client->id }})" wire:confirm="Remove this client from the project?"
                            class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Remove
                        </button>
                    @endif
                </li>
            @empty
                <li class="py-6 text-center text-sm text-gray-500">
                    No clients assigned to this project.
                </li>
            @endforelse
        </ul>
    </div>

</div>