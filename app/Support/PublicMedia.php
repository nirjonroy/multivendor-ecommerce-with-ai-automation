<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicMedia
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');
        $targetDirectory = public_path('uploads/' . $directory);

        if (! File::isDirectory($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file->move($targetDirectory, $filename);

        return 'uploads/' . $directory . '/' . $filename;
    }

    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (Str::startsWith($path, 'uploads/')) {
            File::delete(public_path($path));
            return;
        }

        Storage::disk('public')->delete($path);
    }

    public static function url(?string $path, ?string $fallback = null): string
    {
        if (! $path) {
            return $fallback ? asset($fallback) : '';
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        if (Str::startsWith($path, 'uploads/')) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }
}
