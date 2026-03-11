<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900">Settings</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Settings</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your account preferences, notifications, and security.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Navigation Sidebar --}}
            <div class="lg:col-span-1">
                <nav class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <a href="#profile"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-indigo-600 bg-indigo-50/50 border-l-2 border-indigo-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Profile Settings
                    </a>
                    <a href="#password"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 border-l-2 border-transparent hover:border-gray-300 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        Password
                    </a>
                    <a href="#notifications"
                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 border-l-2 border-transparent hover:border-gray-300 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        Notifications
                    </a>
                </nav>
            </div>

            {{-- Main Settings Content --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Profile Section --}}
                <div id="profile" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-base font-semibold text-gray-900">Profile Settings</h2>
                        <p class="text-xs text-gray-500 mt-1">Update your name, email, and profile picture.</p>
                    </div>
                    <div class="p-6">
                        <livewire:settings.update-profile />
                    </div>
                </div>

                {{-- Password Section --}}
                <div id="password" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-base font-semibold text-gray-900">Change Password</h2>
                        <p class="text-xs text-gray-500 mt-1">Ensure your account uses a secure password.</p>
                    </div>
                    <div class="p-6">
                        <livewire:settings.update-password />
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>