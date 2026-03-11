<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Team Members</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-8 py-8 space-y-8">

        {{-- Header Actions --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Team Members</h1>
            <button
                class="bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                Invite Member
            </button>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-medium">Name</th>
                        <th class="px-6 py-4 font-medium">Role</th>
                        <th class="px-6 py-4 font-medium text-center">Projects</th>
                        <th class="px-6 py-4 font-medium text-center">Tasks</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(\App\Models\User::latest()->get() as $member)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span>{{ $member->name }}</span>
                                        <span class="text-xs text-gray-400 font-normal">{{ $member->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="capitalize text-xs font-medium px-2.5 py-1 rounded-md bg-gray-100 text-gray-700">
                                    {{ $member->role ?? 'Member' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">{{ $member->projects_count ?? 0 }}</td>
                            <td class="px-6 py-4 text-center">{{ $member->tasks_count ?? 0 }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No items yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>