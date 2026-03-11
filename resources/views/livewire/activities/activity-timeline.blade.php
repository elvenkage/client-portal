<div>


    @if($activities->isEmpty())
        <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">
            No activity yet.
        </div>
    @else
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @foreach($activities as $index => $activity)
                    <li>
                        <div class="relative pb-8">
                            {{-- Connector line --}}
                            @if(!$loop->last)
                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif

                            <div class="relative flex space-x-3">
                                {{-- Icon --}}
                                @php
                                    $iconConfig = match ($activity->action) {
                                        'task_created' => ['bg' => 'bg-blue-500', 'icon' => '+'],
                                        'task_status_changed' => ['bg' => 'bg-yellow-500', 'icon' => '↻'],
                                        'task_completed' => ['bg' => 'bg-green-500', 'icon' => '✓'],
                                        'file_uploaded' => ['bg' => 'bg-purple-500', 'icon' => '📎'],
                                        'comment_added' => ['bg' => 'bg-indigo-500', 'icon' => '💬'],
                                        'milestone_completed' => ['bg' => 'bg-emerald-600', 'icon' => '🏁'],
                                        default => ['bg' => 'bg-gray-400', 'icon' => '•'],
                                    };
                                @endphp
                                <div>
                                    <span
                                        class="flex h-8 w-8 items-center justify-center rounded-full {{ $iconConfig['bg'] }} text-white text-sm ring-4 ring-white">
                                        {{ $iconConfig['icon'] }}
                                    </span>
                                </div>

                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            {{ $activity->description }}
                                            @if($activity->user)
                                                <span class="text-gray-500">by <span
                                                        class="font-medium text-gray-900">{{ $activity->user->name }}</span></span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-xs text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    @endif
</div>