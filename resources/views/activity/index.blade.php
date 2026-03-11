<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Activity</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header Actions --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Activity</h1>
            <div class="flex items-center gap-3">
                <select
                    class="border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option>All Projects</option>
                </select>
                <select
                    class="border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option>All Types</option>
                    <option>Tasks</option>
                    <option>Files</option>
                </select>
            </div>
        </div>

        {{-- Timeline Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="max-w-3xl">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse(\App\Models\ActivityLog::with('user', 'project')->latest('created_at')->get() as $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if (!$loop->last)
                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-100"
                                            aria-hidden="true"></span>
                                    @endif

                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                                @if(str_contains($activity->action, 'task')) bg-blue-50 @elseif(str_contains($activity->action, 'file')) bg-amber-50 @else bg-indigo-50 @endif">
                                                <svg class="h-4 w-4 @if(str_contains($activity->action, 'task')) text-blue-600 @elseif(str_contains($activity->action, 'file')) text-amber-600 @else text-indigo-600 @endif"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    @if(str_contains($activity->action, 'task'))
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    @elseif(str_contains($activity->action, 'file'))
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                                    @endif
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-900">
                                                    <span
                                                        class="font-medium text-gray-900">{{ $activity->user->name ?? 'System' }}</span>
                                                    {{ str_replace($activity->user->name ?? '', '', $activity->description) }}
                                                    @if($activity->project)
                                                        in <a href="{{ route('projects.show', $activity->project->id) }}"
                                                            class="font-medium text-indigo-600 hover:text-indigo-800">{{ $activity->project->name }}</a>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-xs text-gray-400">
                                                <time
                                                    datetime="{{ $activity->created_at->toIso8601String() }}">{{ $activity->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="py-16 text-center text-gray-400">
                                <svg class="mx-auto h-8 w-8 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                No activity recorded yet
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>