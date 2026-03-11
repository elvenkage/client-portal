<?php

namespace App\Livewire\Tasks;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Validation\ValidationException;

class TaskFiles extends Component
{
    use WithFileUploads;

    public $task;

    // UI state
    public $uploadType = 'image'; // 'image' or 'link'

    // Form fields
    public $image;
    public $externalLink;

    protected FileService $fileService;

    public function boot(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function mount(Task $task)
    {
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');

        $this->task = $task;
    }

    /**
     * Updated hook whenever $image changes (to provide quick validation feedback).
     */
    public function updatedImage()
    {
        $this->validateOnly('image', [
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
        ]);
    }

    public function setUploadType($type)
    {
        $this->uploadType = $type;
        $this->resetValidation();
        $this->image = null;
        $this->externalLink = '';
    }

    public function save()
    {
        if ($this->uploadType === 'image') {
            $this->validate([
                'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
            ], [
                'image.max' => 'The image must not exceed 5MB.',
                'image.mimes' => 'Only png, jpg, jpeg, or webp images are allowed.',
            ]);

            try {
                $this->fileService->storeImage($this->image, [
                    'project_id' => $this->task->project_id,
                    'milestone_id' => $this->task->milestone_id,
                    'task_id' => $this->task->id,
                    'uploaded_by' => auth()->id(),
                ]);
                $this->image = null; // reset
            } catch (ValidationException $e) {
                $this->addError('image', $e->getMessage());
                return;
            }
        } else {
            $this->validate([
                'externalLink' => 'required|url|max:2048',
            ]);

            $this->fileService->storeExternalLink($this->externalLink, [
                'project_id' => $this->task->project_id,
                'milestone_id' => $this->task->milestone_id,
                'task_id' => $this->task->id,
                'uploaded_by' => auth()->id(),
            ]);
            $this->externalLink = ''; // reset
        }

        $this->task->load('files.uploader'); // Reload files

        // Dispatch simple event for UI feedback if needed
        $this->dispatch('file-uploaded');
    }

    public function deleteFile($fileId)
    {
        $file = File::findOrFail($fileId);

        // Prevent users from deleting files from other tasks
        if ($file->task_id !== $this->task->id) {
            return;
        }

        $this->fileService->deleteFile($file);
        $this->task->load('files.uploader');
    }

    public function render()
    {
        return view('livewire.tasks.task-files', [
            'files' => $this->task->files()->with('uploader')->latest()->get(),
        ]);
    }
}
