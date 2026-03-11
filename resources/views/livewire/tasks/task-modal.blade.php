<div>
    @if($isOpen && $task)
        {{-- STEP 1: Modal overlay --}}
        <div class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4 sm:p-0"
            x-data="{ show: @entangle('isOpen') }" x-show="show" x-transition.opacity @keydown.window.escape="show = false">

            {{-- STEP 2: Modal container --}}
            <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col h-full"
                x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.away="show = false">

                {{-- STEP 3: Modal Header --}}
                <div class="shrink-0 items-center justify-between border-b border-gray-200 bg-white px-6 py-4 flex z-10">
                    <div class="flex items-center gap-3 w-full pr-8">
                        <svg class="h-6 w-6 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                        <h2 class="text-xl font-bold text-gray-900 truncate">{{ $task->title }}</h2>
                    </div>

                    {{-- STEP 9: Close Modal --}}
                    <button wire:click="$set('isOpen', false)"
                        class="rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none transition-colors ml-4 shrink-0 absolute right-4 top-4">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                        </svg>
                    </button>
                </div>

                {{-- STEP 4: Modal Body Grid --}}
                <div class="flex-1 overflow-hidden">
                    <div class="h-full grid grid-cols-1 md:grid-cols-3">

                        {{-- STEP 5: LEFT PANEL --}}
                        <div class="md:col-span-2 overflow-y-auto p-6 space-y-6">

                            {{-- Status/Priority Info --}}
                            <div
                                class="flex flex-wrap items-center gap-4 text-sm p-4 rounded-lg border border-gray-100 shadow-sm bg-gray-50/50">
                                <div>
                                    <span class="text-gray-500 font-medium mr-2">Status:</span>
                                    <span
                                        class="inline-flex rounded-full bg-gray-200 px-2.5 py-0.5 font-bold text-gray-800 tracking-wide uppercase text-xs">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                </div>
                                @if($task->priority)
                                    <div>
                                        <span class="text-gray-500 font-medium mr-2">Priority:</span>
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 font-bold tracking-wide uppercase text-xs
                                                    {{ $task->priority === 'low' ? 'bg-gray-100 text-gray-600' : '' }}
                                                    {{ $task->priority === 'medium' ? 'bg-amber-100 text-amber-700' : '' }}
                                                    {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ $task->priority }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- STEP 8: Description --}}
                            <div>
                                <h3
                                    class="flex items-center gap-2 text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wider">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                    </svg>
                                    Description
                                </h3>
                                @if($task->description)
                                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                                        <p class="whitespace-pre-wrap text-[15px] text-gray-700 leading-relaxed">
                                            {{ $task->description }}</p>
                                    </div>
                                @else
                                    <div class="bg-gray-50 rounded-lg p-5 text-sm text-gray-500 italic border border-gray-200">
                                        No description provided.
                                    </div>
                                @endif
                            </div>

                            {{-- Members Row --}}
                            <div class="grid grid-cols-2 gap-6 pt-4">
                                {{-- Assignee --}}
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Assignee
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        @if($task->assignee)
                                            <div class="flex items-center gap-2 bg-white rounded-full pl-1.5 pr-4 py-1.5 shadow-sm border border-gray-200"
                                                title="{{ $task->assignee->name }}">
                                                <div
                                                    class="h-7 w-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold ring-2 ring-white">
                                                    {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                                </div>
                                                <span
                                                    class="text-sm font-medium text-gray-700">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 italic">Unassigned</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Due Date --}}
                                @if($task->deadline)
                                    <div>
                                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Due Date
                                        </h3>
                                        <div
                                            class="flex items-center gap-2 bg-white rounded-lg px-4 py-2 border border-gray-200 shadow-sm w-max {{ $task->deadline->isPast() && $task->status !== 'completed' ? 'border-red-200 bg-red-50 text-red-700' : 'text-gray-700' }}">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium">{{ $task->deadline->format('M d, Y h:i A') }}</span>
                                        </div>
                                        @if($task->deadline->isPast() && $task->status !== 'completed')
                                            <p class="text-xs text-red-600 mt-1.5 font-medium ml-1">Overdue</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- STEP 6: RIGHT PANEL & STEP 7: COMMENTS --}}
                        <div
                            class="md:col-span-1 border-t md:border-t-0 md:border-l border-gray-200 overflow-y-auto p-6 bg-gray-50/30">

                            {{-- Activity/Comments Header --}}
                            <div>
                                <h3
                                    class="flex items-center gap-2 text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                                    </svg>
                                    Activity & Comments
                                </h3>

                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                                    <livewire:tasks.task-comments :task-id="$task->id"
                                        wire:key="comments-{{ $task->id }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>