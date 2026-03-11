<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Clients</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header Actions --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Clients</h1>
            <button @click="Livewire.dispatch('openCreateClientModal')"
                class="bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                + New Client
            </button>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 font-medium">Company</th>
                        <th class="px-6 py-3.5 font-medium">Contact Person</th>
                        <th class="px-6 py-3.5 font-medium">Email</th>
                        <th class="px-6 py-3.5 font-medium text-center">Projects</th>
                        <th class="px-6 py-3.5 font-medium">Last Activity</th>
                        <th class="px-6 py-3.5 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(\App\Models\Client::latest()->get() ?? [] as $client)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $client->name ?? 'Company Name' }}</td>
                            <td class="px-6 py-4">{{ $client->contact_person ?? 'John Doe' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $client->email ?? 'john@example.com' }}</td>
                            <td class="px-6 py-4 text-center tabular-nums">{{ $client->projects()->count() ?? 0 }}</td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $client->updated_at ? $client->updated_at->diffForHumans() : 'Just now' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                    </svg>
                                    <span class="text-sm">No clients yet</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Create Client Modal --}}
        <livewire:clients.create-client-modal />

    </div>
</x-app-layout>