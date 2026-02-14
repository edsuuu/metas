<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show(string $uuid)
    {
        $file = File::query()->where('uuid', $uuid)->firstOrFail();

        if (!Storage::disk($file->disk)->exists($file->path)) {
            abort(404);
        }

        $content = Storage::disk($file->disk)->get($file->path);

        return response($content)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'inline; filename="' . $file->filename . '"');
    }
}
