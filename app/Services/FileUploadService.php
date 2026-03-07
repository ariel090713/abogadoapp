<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file to the public S3 bucket
     * Use for: profile photos, public documents
     */
    public function uploadPublic(UploadedFile $file, string $directory = 'uploads'): array
    {
        try {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $encryptedName = Str::random(32) . '_' . $timestamp . '.' . $extension;
            $fullPath = $directory . '/' . $encryptedName;
            
            \Log::info('Starting public file upload', [
                'original_name' => $originalName,
                'encrypted_name' => $encryptedName,
                'directory' => $directory,
                'full_path' => $fullPath,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
            
            $disk = Storage::disk('s3-public');
            $bucket = config('filesystems.disks.s3-public.bucket');
            
            \Log::info('S3 Public disk configuration', [
                'bucket' => $bucket,
                'region' => config('filesystems.disks.s3-public.region'),
            ]);
            
            // Upload using put() with stream and explicit options (no ACL)
            $stream = fopen($file->getRealPath(), 'r');
            $success = $disk->put($fullPath, $stream, [
                'ContentType' => $file->getMimeType(),
                'CacheControl' => 'max-age=31536000',
                // Explicitly no ACL
            ]);
            
            if (is_resource($stream)) {
                fclose($stream);
            }
            
            if (!$success) {
                \Log::error('Failed to upload file to S3 public bucket');
                throw new \Exception('Failed to upload file to S3');
            }
            
            $url = $disk->url($fullPath);
            
            \Log::info('Public file upload successful', [
                'path' => $fullPath,
                'url' => $url,
                'original_name' => $originalName,
            ]);
            
            return [
                'path' => $fullPath,
                'url' => $url,
                'original_name' => $originalName,
                'encrypted_name' => $encryptedName,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
            
        } catch (\Aws\S3\Exception\S3Exception $e) {
            \Log::error('AWS S3 Exception during public file upload', [
                'error' => $e->getMessage(),
                'aws_error_code' => $e->getAwsErrorCode(),
                'aws_error_type' => $e->getAwsErrorType(),
                'status_code' => $e->getStatusCode(),
                'file' => $file->getClientOriginalName() ?? 'unknown',
            ]);
            
            throw new \Exception('AWS S3 Error: ' . $e->getAwsErrorMessage());
            
        } catch (\Exception $e) {
            \Log::error('Public file upload exception', [
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'file' => $file->getClientOriginalName() ?? 'unknown',
            ]);
            
            throw new \Exception('File upload failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Upload a file to the private S3 bucket
     * Use for: IBP cards, legal documents, sensitive files
     */
    public function uploadPrivate(UploadedFile $file, string $directory = 'documents'): array
    {
        try {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('YmdHis');
            $encryptedName = Str::random(32) . '_' . $timestamp . '.' . $extension;
            
            \Log::info('Starting private file upload', [
                'original_name' => $originalName,
                'encrypted_name' => $encryptedName,
                'directory' => $directory,
                'size' => $file->getSize(),
            ]);
            
            $path = Storage::disk('s3-private')->putFileAs(
                $directory,
                $file,
                $encryptedName
            );
            
            if (!$path) {
                throw new \Exception('Failed to upload file to S3');
            }
            
            \Log::info('Private file upload successful', [
                'path' => $path,
                'original_name' => $originalName,
            ]);
            
            return [
                'path' => $path,
                'original_name' => $originalName,
                'encrypted_name' => $encryptedName,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
            
        } catch (\Exception $e) {
            \Log::error('Private file upload failed', [
                'error' => $e->getMessage(),
                'original_name' => $file->getClientOriginalName() ?? 'unknown',
                'directory' => $directory,
            ]);
            
            throw new \Exception('File upload failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get a temporary signed URL for private files (expires in 1 hour)
     */
    public function getPrivateUrl(string $path, int $expiresInMinutes = 60): string
    {
        return Storage::disk('s3-private')->temporaryUrl(
            $path,
            now()->addMinutes($expiresInMinutes)
        );
    }
    
    /**
     * Delete a file from public bucket
     */
    public function deletePublic(string $path): bool
    {
        return Storage::disk('s3-public')->delete($path);
    }
    
    /**
     * Delete a file from private bucket
     */
    public function deletePrivate(string $path): bool
    {
        return Storage::disk('s3-private')->delete($path);
    }
    
    /**
     * Check if file exists in public bucket
     */
    public function existsPublic(string $path): bool
    {
        return Storage::disk('s3-public')->exists($path);
    }
    
    /**
     * Check if file exists in private bucket
     */
    public function existsPrivate(string $path): bool
    {
        return Storage::disk('s3-private')->exists($path);
    }
}
