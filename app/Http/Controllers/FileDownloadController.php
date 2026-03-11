<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileDownloadController extends Controller
{
    /**
     * Download a file securely.
     */
    public function download(File $file)
    {
        $user = auth()->user();

        // 1. If user is a client, verify they are a member of the project
        if ($user->isClient()) {
            $project = $file->project;
            if (!$project || !$project->members()->where('user_id', $user->id)->exists() || $project->visibility === 'private') {
                abort(403, 'Unauthorized access to this file.');
            }
        }
        // 2. If user is staff, check they have access (admin or PM or team member on project)
        else {
            if (!$user->isSuperAdmin() && !$user->isProjectManager() && $user->role !== 'admin') {
                $isMember = $file->project->members()->where('user_id', $user->id)->exists();
                if (!$isMember) {
                    abort(403, 'You are not assigned to this project.');
                }
            }
        }

        // 3. Serve file
        $path = $file->image_path ?: $file->file_path;

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File not found on disk.');
        }

        $downloadName = $file->file_name ?: basename($path);

        return Storage::disk('public')->download($path, $downloadName);
    }
}
