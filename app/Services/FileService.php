<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileService
{
    /**
     * Upload a file and attach it to a model.
     */
    public function upload(UploadedFile $file, Model $model, string $collection = 'default'): ?File
    {
        try {
            $disk = config('filesystems.default', 'public');
            if ($disk === 's3') {
                $disk = 'public'; // Force public if S3 logic is not fully checked or desired fallback
            }

            $path = $file->store("uploads/{$model->getTable()}/{$collection}", $disk);

            return File::query()->create([
                'uuid' => (string) Str::uuid(),
                'fileable_id' => $model->id,
                'fileable_type' => get_class($model),
                'path' => $path,
                'disk' => $disk,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao fazer upload de arquivo: ' . $e->getMessage(), ['exception' => $e]);
            throw $e; // Re-throw to be caught by Controller
        }
    }

    /**
     * Download a file from a URL and store it.
     */
    public function saveFromUrl(string $url, Model $model, string $collection = 'default'): ?File
    {
        try {
            $contents = @file_get_contents($url);
            if (!$contents) {
                return null;
            }

            $filename = 'avatar_' . Str::random(10) . '.jpg';
            $disk = config('filesystems.default', 'public');
            if ($disk === 's3') {
                $disk = 'public';
            }

            $path = "uploads/{$model->getTable()}/{$collection}/{$filename}";
            Storage::disk($disk)->put($path, $contents);

            return File::query()->create([
                'uuid' => (string) Str::uuid(),
                'fileable_id' => $model->id,
                'fileable_type' => get_class($model),
                'path' => $path,
                'disk' => $disk,
                'filename' => $filename,
                'mime_type' => 'image/jpeg',
                'size' => strlen($contents),
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar arquivo via URL: ' . $e->getMessage(), ['exception' => $e]);
            return null;
        }
    }

    /**
     * Delete a file and its physical representation.
     */
    public function delete(File $file): bool
    {
        try {
            if (Storage::disk($file->disk)->exists($file->path)) {
                Storage::disk($file->disk)->delete($file->path);
            }

            return $file->delete();
        } catch (\Exception $e) {
            Log::error('Erro ao deletar arquivo: ' . $e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
