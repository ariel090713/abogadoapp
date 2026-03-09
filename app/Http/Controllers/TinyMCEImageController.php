<?php

namespace App\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TinyMCEImageController extends Controller
{
    public function upload(Request $request, FileUploadService $fileService)
    {
        try {
            $request->validate([
                'file' => 'required|image|max:5120', // 5MB max
            ]);

            // Upload to public S3 bucket
            $fileData = $fileService->uploadPublic(
                $request->file('file'),
                'tinymce-images'
            );

            return response()->json([
                'location' => $fileData['url']
            ]);

        } catch (\Exception $e) {
            Log::error('TinyMCE image upload failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Image upload failed'
            ], 500);
        }
    }
}
