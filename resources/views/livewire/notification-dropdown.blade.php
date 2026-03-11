<div class="relative" x-data="{ open: @entangle('isOpen') }" @click.outside="open = false; $wire.closeDropdown()">

    {{-- Bell Button --}}
    <button @click="open = !open; $wire.toggleDropdown()" class="relative text-gray-400 hover:text-gray-600 transition-colors">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>

        {{-- Unread Badge --}}
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         x-cloak
         class="absolute right-0 top-full mt-2 w-80 bg-white rounded-xl border border-gray-200 shadow-lg z-50 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[11px] font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                    Mark all read
                </button>
            @endif
        </div>

        {{-- Notification List --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
            @forelse($notifications as $notification)
                <div wire:click="markAsRead({{ $notification->id }})"
                     class="px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer flex gap-3 {{ !$notification->is_read ? 'bg-indigo-50/40' : '' }}">

                    {{-- Icon --}}
                    @php
                        $iconConfig = match($notification->type) {
                            'task_assigned'       => ['bg' => 'bg-blue-100 text-blue-600',    'icon' => '👤'],
                            'task_completed'      => ['bg' => 'bg-emerald-100 text-emerald-600', 'icon' => '✓'],
                            'file_uploaded'       => ['bg' => 'bg-indigo-100 text-indigo-600',   'icon' => '📎'],
                            'milestone_completed' => ['bg' => 'bg-amber-100 text-amber-600',    'icon' => '🏁'],
                            'comment_added'       => ['bg' => 'bg-purple-100 text-purple-600',  'icon' => '💬'],
                            default               => ['bg' => 'bg-gray-100 text-gray-600',      'icon' => '•'],
                        };
                    @endphp
                    <span class="flex h-7 w-7 items-center justify-center rounded-full {{ $iconConfig['bg'] }} text-xs flex-shrink-0 mt-0.5">
                        {{ $iconConfig['icon'] }}
                    </span>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="text-sm font-medium text-gray-900 leading-snug">{{ $notification->title }}</p>
                            @if(!$notification->is_read)
                                <span class="flex h-2 w-2 rounded-full bg-indigo-500 flex-shrink-0 mt-1.5"></span>
                            @endif
                        </div>
                        @if($notification->message)
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notification->message }}</p>
                        @endif
                        <p class="text-[11px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-7 w-7 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    <p class="text-xs text-gray-500">No notifications yet</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
