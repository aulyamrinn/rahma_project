<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    /**
     * Serve uploaded photos from public disk.
     */
    public function showPhoto(string $path)
    {
        // Sanitize path to prevent directory traversal
        $path = str_replace(['..', '\\'], ['', '/'], $path);

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File foto bukti tidak ditemukan.');
        }

        $file = Storage::disk('public')->get($path);
        $type = Storage::disk('public')->mimeType($path) ?: 'image/jpeg';

        return response($file, 200)->header('Content-Type', $type);
    }
}

