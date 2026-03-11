<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
        </svg>
        Comments
        @if($comments->count())
            <span class="bg-gray-200 text-gray-600 py-0.5 px-2 rounded-full text-xs font-medium">
                {{ $comments->count() }}
            </span>
        @endif
    </h3>

    {{-- New Comment Form --}}
    <form wire:submit="addComment" class="mb-6">
        <div>
            <textarea
                wire:model="newComment"
                rows="3"
                placeholder="Write a comment..."
                class="block w-full rounded-lg border-0 py-2 px-3 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 resize-none"
            ></textarea>
            @error('newComment')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-3 flex items-center justify-end gap-3">
            <span wire:loading wire:target="addComment" class="text-sm text-gray-500 italic">Posting...</span>
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-1.5 rounded-md bg-blue-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 transition-colors"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                </svg>
                Post Comment
            </button>
        </div>
    </form>

    {{-- Comments List --}}
    @if($comments->isNotEmpty())
        <div class="space-y-3">
            @foreach($comments as $comment)
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-3 min-w-0">
                            {{-- User Avatar --}}
                            <span
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold ring-2 ring-white"
                                title="{{ $comment->user->name ?? 'Unknown' }}"
                            >
                                {{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}
                            </span>

                            <div class="min-w-0 flex-1">
                                {{-- Name + Timestamp --}}
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $comment->user->name ?? 'Unknown User' }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                {{-- Comment Body --}}
                                <p class="mt-1 text-sm text-gray-700 whitespace-pre-line break-words">{{ $comment->message }}</p>
                            </div>
                        </div>

                        {{-- Delete Button (author only) --}}
                        @if(auth()->id() === $comment->user_id)
                            <button
                                wire:click="deleteComment({{ $comment->id }})"
                                wire:confirm="Delete this comment?"
                                type="button"
                                class="flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors"
                                title="Delete comment"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">No comments yet. Be the first to comment!</p>
        </div>
    @endif
</div>
