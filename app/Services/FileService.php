<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FileService
{
    public function __construct(
        protected ActivityLogService $activityLogService,
    ) {
    }
    /**
     * Allowed image extensions for server-side storage.
     */
    protected array $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

    /**
     * Maximum image size in kilobytes.
     */
    protected int $maxSizeKb = 5120; // 5 MB

    /**
     * Validate that an uploaded file is an allowed image type.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateImage(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $this->allowedExtensions, true)) {
            throw ValidationException::withMessages([
                'file' => 'Only image files are allowed: ' . implode(', ', $this->allowedExtensions) . '.',
            ]);
        }

        if ($file->getSize() > $this->maxSizeKb * 1024) {
            throw ValidationException::withMessages([
                'file' => "Image must not exceed {$this->maxSizeKb} KB.",
            ]);
        }
    }

    /**
     * Store an image file on the server and create a File record.
     *
     * @param  array<string, mixed>  $meta  project_id, milestone_id, task_id, uploaded_by
     */
    public function storeImage(UploadedFile $uploadedFile, array $meta): File
    {
        $this->validateImage($uploadedFile);

        $path = $uploadedFile->store('project-files', 'public');

        $file = File::create(array_merge($meta, [
            'image_path' => $path,
        ]));

        $this->activityLogService->logFileUploaded($file);

        return $file;
    }

    /**
     * Store a general file (pdf, docx, zip, images, etc.) on the server.
     *
     * @param  array<string, mixed>  $meta  project_id, task_id (optional), uploaded_by
     */
    public function storeGeneralFile(UploadedFile $uploadedFile, array $meta): File
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = strtolower($uploadedFile->getClientOriginalExtension());
        $path = $uploadedFile->store('project-files', 'public');

        $file = File::create(array_merge($meta, [
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $extension,
        ]));

        $this->activityLogService->logFileUploaded($file);

        return $file;
    }

    /**
     * Store an external link (Google Drive, Dropbox, OneDrive, etc.) as a File record.
     *
     * @param  array<string, mixed>  $meta  project_id, milestone_id, task_id, uploaded_by
     */
    public function storeExternalLink(string $url, array $meta): File
    {
        $file = File::create(array_merge($meta, [
            'external_link' => $url,
        ]));

        $this->activityLogService->logFileUploaded($file);

        return $file;
    }

    /**
     * Delete a file record (soft delete) and remove the image from storage if applicable.
     */
    public function deleteFile(File $file): bool
    {
        if ($file->image_path && Storage::disk('public')->exists($file->image_path)) {
            Storage::disk('public')->delete($file->image_path);
        }

        $this->activityLogService->logFileDeleted($file);

        return $file->delete();
    }
}
