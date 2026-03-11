<?php

namespace App\Livewire\Files;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\File;
use App\Services\FileService;

class UploadProjectFile extends Component
{
    use WithFileUploads;

    public $projectId;
    public $file;
    public $showUpload = false;

    protected FileService $fileService;

    public function boot(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function mount($projectId)
    {
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');

        $this->projectId = $projectId;
    }

    public function toggleUpload()
    {
        $this->showUpload = !$this->showUpload;
        $this->file = null;
        $this->resetValidation();
    }

    public function updatedFile()
    {
        $this->validateOnly('file', [
            'file' => 'required|file|mimes:png,jpg,jpeg,pdf,docx,zip|max:20480',
        ]);
    }

    public function save()
    {
        $this->validate([
            'file' => 'required|file|mimes:png,jpg,jpeg,pdf,docx,zip|max:20480',
        ], [
            'file.max' => 'File must not exceed 20MB.',
            'file.mimes' => 'Allowed types: png, jpg, pdf, docx, zip.',
        ]);

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts('file-upload:' . auth()->id(), 10)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn('file-upload:' . auth()->id());
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => "Too many file uploads. Please try again in {$seconds} seconds.",
            ]);
        }

        \Illuminate\Support\Facades\RateLimiter::hit('file-upload:' . auth()->id(), 60);

        $this->fileService->storeGeneralFile($this->file, [
            'project_id' => $this->projectId,
            'uploaded_by' => auth()->id(),
        ]);

        $this->file = null;
        $this->showUpload = false;

        $this->dispatch('file-uploaded');
    }

    public function deleteFile($fileId)
    {
        $file = File::findOrFail($fileId);

        if ((int) $file->project_id !== (int) $this->projectId) {
            return;
        }

        $this->fileService->deleteFile($file);
    }

    public function render()
    {
        return view('livewire.files.upload-project-file', [
            'files' => File::where('project_id', $this->projectId)
                ->whereNotNull('file_path')
                ->with('uploader')
                ->latest()
                ->get(),
        ]);
    }
}
