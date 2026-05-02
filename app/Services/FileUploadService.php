<?php









// ============================================================
// app/Services/FileUploadService.php
// ============================================================
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    private string $disk = 'public';

    /**
     * Upload a file and return metadata.
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): array
    {
        $originalName = $file->getClientOriginalName();
        $extension    = strtolower($file->getClientOriginalExtension());
        $safeName     = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $uniqueName   = $safeName . '_' . Str::random(8) . '.' . $extension;

        $path = $file->storeAs(
            $folder . '/' . now()->format('Y/m'),
            $uniqueName,
            $this->disk
        );

        return [
            'path'          => $path,
            'original_name' => $originalName,
            'extension'     => $extension,
            'size'          => $file->getSize(),
            'mime'          => $file->getMimeType(),
            'url'           => Storage::disk($this->disk)->url($path),
        ];
    }

    /**
     * Delete a file from storage.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }
}
