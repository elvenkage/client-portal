<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $task->title }} | Client Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-900">

    <div class="min-h-screen">

        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <a href="{{ route('projects.show', $project->id) }}"
                        class="text-blue-600 hover:underline">{{ $project->name }}</a>
                    <span class="text-gray-400 mx-2">/</span>
                    {{ $task->title }}
                </h2>
            </div>
        </header>

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-3 gap-6">

                    <!-- Left Column: Task Details -->
                    <div class="col-span-2 space-y-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Description</h3>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($task->description ?: 'No description provided.')) !!}
                            </div>
                        </div>

                        <!-- Task Comments -->
                        <livewire:tasks.task-comments :task="$task" />
                    </div>

                    <!-- Right Column: Meta & Files -->
                    <div class="col-span-1 space-y-6">

                        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Status</h4>
                                <p class="mt-1 font-semibold text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Priority</h4>
                                <p class="mt-1 font-semibold text-gray-900">{{ ucfirst($task->priority) }}</p>
                            </div>

                            @if($task->deadline)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Deadline</h4>
                                    <p class="mt-1 text-gray-900">{{ $task->deadline->format('M d, Y') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Livewire File Upload Component -->
                        <livewire:tasks.task-files :task="$task" />

                    </div>

                </div>

            </div>
        </main>
    </div>

    @livewireScripts
</body>

</html>