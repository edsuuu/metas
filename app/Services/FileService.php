<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            
            // Check if it is an image to strip metadata
             if (str_starts_with($file->getMimeType(), 'image/') && class_exists(ImageManager::class)) {
                try {
                     $manager = new ImageManager(new Driver());
                    $image = $manager->read($file->getRealPath());
                    $image->save($file->getRealPath()); 
                } catch (\Exception $imgEx) {
                    Log::warning('Erro ao remover metadados: ' . $imgEx->getMessage(), ['exception' => $imgEx]);
                }
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

            // Strip metadata if it's an image
            try {
                if (class_exists(ImageManager::class)) {
                     // Check if it is a valid image before processing
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->buffer($contents);

                    if (str_starts_with($mime, 'image/')) {
                        $manager = new ImageManager(new Driver());
                        $image = $manager->read($contents);
                        // Re-encode strips EXIF by default
                        $contents = (string) $image->toJpeg(quality: 85); 
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Falha ao processar metadados da imagem: ' . $e->getMessage(), ['exception' => $e]);
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
