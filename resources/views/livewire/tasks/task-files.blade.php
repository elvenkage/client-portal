<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
        </svg>
        Attachments
    </h3>

    <!-- Upload Form -->
    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">

        <!-- Toggle Upload Type -->
        <div class="flex space-x-4 mb-4 border-b border-gray-200 pb-2">
            <button wire:click="setUploadType('image')" type="button"
                class="text-sm font-medium pb-2 -mb-[2px] border-b-2 transition-colors {{ $uploadType === 'image' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                🖼️ Upload Image
            </button>
            <button wire:click="setUploadType('link')" type="button"
                class="text-sm font-medium pb-2 -mb-[2px] border-b-2 transition-colors {{ $uploadType === 'link' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                🔗 Add External Link
            </button>
        </div>

        <form wire:submit="save" class="space-y-4">

            @if($uploadType === 'image')
                <div>
                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-3 text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or
                                    drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG, WEBP (Max 5MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" wire:model="image" class="hidden"
                                accept="image/png, image/jpeg, image/jpg, image/webp" />
                        </label>
                    </div>

                    <!-- Livewire Image Preview -->
                    @if ($image)
                        <div class="mt-4 flex items-center gap-4 p-2 border border-gray-200 rounded bg-white">
                            <img src="{{ $image->temporaryUrl() }}" class="h-16 w-16 object-cover rounded shadow-sm">
                            <div class="flex-1 text-sm text-gray-700">
                                <span
                                    class="font-medium truncate block max-w-[200px]">{{ $image->getClientOriginalName() }}</span>
                                <span class="text-xs text-gray-500">{{ round($image->getSize() / 1024, 1) }} KB</span>
                            </div>
                        </div>
                    @endif

                    <!-- Validation Error -->
                    @error('image')
                        <div class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            @else
                <!-- External Link -->
                <div>
                    <label for="externalLink" class="block text-sm font-medium leading-6 text-gray-900">Document URL</label>
                    <div class="mt-2">
                        <input type="url" wire:model="externalLink" id="externalLink"
                            placeholder="https://docs.google.com/..."
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                        <p class="mt-1 text-xs text-gray-500">E.g., Google Drive, Dropbox, Figma, OneDrive.</p>
                        @error('externalLink') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            <!-- Loading Indicator & Submit Button -->
            <div class="flex items-center justify-end gap-3 mt-4">
                <span wire:loading wire:target="image" class="text-sm text-gray-500 italic">Uploading...</span>
                <span wire:loading wire:target="save" class="text-sm text-gray-500 italic">Saving...</span>

                <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:opacity-50">
                    {{ $uploadType === 'image' ? 'Upload Image' : 'Save Link' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Attached Files List -->
    @if($files->isNotEmpty())
        <div class="space-y-4">
            <h4 class="text-sm font-medium text-gray-700">Files</h4>

            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                @foreach($files as $file)
                    <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm leading-6">
                        <div class="flex w-0 flex-1 items-center">

                            @if($file->image_path)
                                <!-- Image Preview Thumbnail -->
                                <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded overflow-hidden mr-4">
                                    <img src="{{ Storage::url($file->image_path) }}" alt="Preview"
                                        class="h-full w-full object-cover">
                                </div>
                                <div class="ml-2 flex min-w-0 flex-1 gap-2">
                                    <span class="truncate font-medium">{{ basename($file->image_path) }}</span>
                                    @if($file->uploader)
                                        <span class="flex-shrink-0 text-gray-400">by {{ $file->uploader->name }}</span>
                                    @endif
                                </div>
                            @else
                                <!-- External Link Icon -->
                                <svg class="h-5 w-5 flex-shrink-0 text-gray-400 mr-4 ml-2" viewBox="0 0 20 20" fill="currentColor"
                                    aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M12.232 4.232a2.5 2.5 0 0 1 3.536 3.536l-1.225 1.224a.75.75 0 0 0 1.061 1.06l1.224-1.224a4 4 0 0 0-5.656-5.656l-3 3a4 4 0 0 0 .225 5.865.75.75 0 0 0 .977-1.138 2.5 2.5 0 0 1-.142-3.667l3-3Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M11.603 7.963a.75.75 0 0 0-.977 1.138 2.5 2.5 0 0 1 .142 3.667l-3 3a2.5 2.5 0 0 1-3.536-3.536l1.225-1.224a.75.75 0 0 0-1.061-1.06l-1.224 1.224a4 4 0 1 0 5.656 5.656l3-3a4 4 0 0 0-.225-5.865Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="ml-2 flex min-w-0 flex-1 gap-2">
                                    <span class="truncate font-medium text-blue-600 hover:underline">
                                        <a href="{{ $file->external_link }}" target="_blank" rel="noopener noreferrer">
                                            {{ str($file->external_link)->limit(40) }}
                                        </a>
                                    </span>
                                    @if($file->uploader)
                                        <span class="flex-shrink-0 text-gray-400">by {{ $file->uploader->name }}</span>
                                    @endif
                                </div>
                            @endif

                        </div>

                        <!-- Actions (Download / Delete) -->
                        <div class="ml-4 flex flex-shrink-0 space-x-4">
                            @if($file->image_path)
                                <a href="{{ Storage::url($file->image_path) }}" download
                                    class="font-medium text-blue-600 hover:text-blue-500">
                                    Download
                                </a>
                                <span class="text-gray-200" aria-hidden="true">|</span>
                            @endif

                            <button wire:click="deleteFile({{ $file->id }})"
                                wire:confirm="Are you sure you want to delete this file?" type="button"
                                class="font-medium text-red-600 hover:text-red-500">
                                Delete
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>