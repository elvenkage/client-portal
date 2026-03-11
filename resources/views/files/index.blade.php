<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Files</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header Actions --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Files</h1>
            <button
                class="bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                Upload File
            </button>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 font-medium">File Name</th>
                        <th class="px-6 py-3.5 font-medium">Project</th>
                        <th class="px-6 py-3.5 font-medium">Uploaded By</th>
                        <th class="px-6 py-3.5 font-medium">Date</th>
                        <th class="px-6 py-3.5 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse(\App\Models\File::with(['project', 'user'])->latest()->get() as $file)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </div>
                                    <span>{{ $file->name ?? 'Document.pdf' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-indigo-600 font-medium">{{ $file->project->name ?? 'General' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $file->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $file->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('files.download', $file->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                <svg class="mx-auto h-8 w-8 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                No files uploaded yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>