<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Files</h2>
        <button wire:click="toggleUpload" class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-lg transition-colors
                   {{ $showUpload ? 'bg-gray-200 text-gray-700' : 'bg-gray-900 text-white hover:bg-gray-800' }}">
            @if($showUpload)
                Cancel
            @else
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                </svg>
                Upload File
            @endif
        </button>
    </div>

    {{-- Upload Form --}}
    @if($showUpload)
        <div class="mb-4 border border-gray-200 rounded-xl bg-gray-50 p-4">
            <form wire:submit="save" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                        Choose File <span class="text-gray-400 normal-case tracking-normal">(max 20MB — png, jpg, pdf, docx,
                            zip)</span>
                    </label>
                    <input type="file" wire:model="file"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:text-sm file:font-medium file:bg-white file:text-gray-700 file:shadow-sm hover:file:bg-gray-50 file:cursor-pointer file:border file:border-gray-200">
                    @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm hover:bg-gray-800 transition-colors whitespace-nowrap"
                    wire:loading.attr="disabled" wire:target="file,save">
                    <span wire:loading.remove wire:target="file,save">Upload</span>
                    <span wire:loading wire:target="file,save">Uploading...</span>
                </button>
            </form>
        </div>
    @endif

    {{-- File List --}}
    <div class="space-y-2">
        @forelse($files as $fileItem)
            <div
                class="bg-white border border-gray-200 rounded-lg p-3 flex items-center justify-between hover:border-gray-300 transition-colors">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    {{-- File Type Icon --}}
                    @php
                        $iconColors = [
                            'pdf' => 'bg-red-50 text-red-600',
                            'docx' => 'bg-blue-50 text-blue-600',
                            'zip' => 'bg-amber-50 text-amber-600',
                            'png' => 'bg-emerald-50 text-emerald-600',
                            'jpg' => 'bg-emerald-50 text-emerald-600',
                            'jpeg' => 'bg-emerald-50 text-emerald-600',
                        ];
                        $iconColor = $iconColors[$fileItem->file_type] ?? 'bg-gray-50 text-gray-600';
                    @endphp
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $iconColor }} flex-shrink-0">
                        <span class="text-[10px] font-bold uppercase">{{ $fileItem->file_type }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $fileItem->file_name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $fileItem->uploader->name ?? 'Unknown' }} · {{ $fileItem->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                    {{-- Download --}}
                    <a href="{{ route('files.download', $fileItem->id) }}"
                        class="text-indigo-600 hover:text-indigo-800 text-xs font-medium px-2 py-1 rounded hover:bg-indigo-50 transition-colors">
                        Download
                    </a>
                    {{-- Delete --}}
                    <button wire:click="deleteFile({{ $fileItem->id }})" wire:confirm="Delete this file?"
                        class="text-red-500 hover:text-red-700 text-xs font-medium px-2 py-1 rounded hover:bg-red-50 transition-colors">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center">
                <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <p class="text-sm text-gray-500">No files yet</p>
            </div>
        @endforelse
    </div>
</div>