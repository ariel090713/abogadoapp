<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LawyerProfile;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class LawyerDocumentController extends Controller
{
    public function __construct(
        private FileUploadService $fileService
    ) {}
    
    public function view(LawyerProfile $lawyer, string $type)
    {
        // Validate type
        if (!in_array($type, ['ibp', 'supporting'])) {
            abort(404, 'Invalid document type');
        }
        
        // Get the document path
        $path = $type === 'ibp' 
            ? $lawyer->ibp_card_path 
            : $lawyer->supporting_document_path;
        
        if (!$path) {
            abort(404, 'Document not found');
        }
        
        // Generate temporary signed URL (expires in 1 hour)
        $url = $this->fileService->getPrivateUrl($path, 60);
        
        // Log access
        \Log::info('Admin viewed lawyer document', [
            'admin_id' => auth()->id(),
            'lawyer_id' => $lawyer->id,
            'document_type' => $type,
        ]);
        
        // Redirect to the signed URL
        return redirect($url);
    }
}
