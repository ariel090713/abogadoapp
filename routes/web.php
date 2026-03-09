<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Livewire\LawyerProfile;
use App\Livewire\LawyerSearch;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Static Pages (Livewire Components)
Route::get('/legal-guides', \App\Livewire\Resources\LegalGuidesIndex::class)->name('resources.legal-guides');
Route::get('/legal-guides/{slug}', \App\Livewire\Resources\LegalGuideView::class)->name('resources.legal-guides.view');
Route::get('/news', \App\Livewire\Resources\NewsIndex::class)->name('resources.news');
Route::get('/news/{slug}', \App\Livewire\Resources\NewsView::class)->name('resources.news.view');
Route::get('/blogs', \App\Livewire\Resources\BlogsIndex::class)->name('resources.blogs');
Route::get('/blogs/{slug}', \App\Livewire\Resources\BlogView::class)->name('resources.blogs.view');
Route::get('/events', \App\Livewire\Resources\EventsIndex::class)->name('resources.events');
Route::get('/events/{slug}', \App\Livewire\Resources\EventView::class)->name('resources.events.view');
Route::get('/galleries', \App\Livewire\Resources\GalleriesIndex::class)->name('resources.galleries');
Route::get('/galleries/{slug}', \App\Livewire\Resources\GalleryView::class)->name('resources.galleries.view');
Route::get('/downloadables', \App\Livewire\Resources\DownloadablesIndex::class)->name('resources.downloadables');
Route::get('/about', \App\Livewire\AboutUs::class)->name('about');
Route::get('/contact', \App\Livewire\ContactUs::class)->name('contact');

// Document Routes (Public - Browse without login)
Route::get('/documents', \App\Livewire\BrowseDocuments::class)->name('documents.browse');

Route::get('/lawyers', LawyerSearch::class)->name('lawyers.search');
Route::get('/lawyers/{username}', LawyerProfile::class)->name('lawyers.show');

// Social Authentication Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/email/verified', function () {
    return view('pages::auth.verification-success');
})->middleware('auth')->name('verification.success');

// Onboarding Routes (requires email verification)
Route::middleware(['auth', 'verified', 'onboarding.redirect'])->group(function () {
    Route::get('/onboarding', \App\Livewire\Onboarding\Start::class)->name('onboarding.start');
    Route::get('/onboarding/client', \App\Livewire\Onboarding\ClientOnboarding::class)->name('onboarding.client');
    Route::get('/onboarding/lawyer', \App\Livewire\Onboarding\LawyerOnboarding::class)->name('onboarding.lawyer');
    Route::get('/onboarding/success', function () {
        // Check if user has a fresh onboarding completion (within last 5 minutes)
        $user = auth()->user();
        $completedAt = $user->onboarding_completed_at;
        
        // If onboarding was completed more than 5 minutes ago, redirect to dashboard
        if (!$completedAt || $completedAt->diffInMinutes(now()) > 5) {
            return redirect()->route('dashboard');
        }
        
        return view('pages.onboarding.success');
    })->name('onboarding.success');
});

// Dashboard Routes (requires auth, verification, and completed onboarding)
Route::middleware(['auth', 'verified', \App\Http\Middleware\EnsureOnboardingCompleted::class])->group(function () {
    // Main dashboard - redirects based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isLawyer()) {
            return redirect()->route('lawyer.dashboard');
        } else {
            return redirect()->route('client.dashboard');
        }
    })->name('dashboard');

    // Client Routes
    Route::prefix('client')->name('client.')->middleware('client')->group(function () {
        Route::get('/dashboard', \App\Livewire\Client\Dashboard::class)->name('dashboard');
        Route::get('/profile', \App\Livewire\Client\Profile::class)->name('profile');
        Route::get('/profile/security', \App\Livewire\Client\ProfileSecurity::class)->name('profile.security');
        Route::get('/profile/notifications', \App\Livewire\Client\ProfileNotifications::class)->name('profile.notifications');
        Route::get('/consultation-threads', \App\Livewire\Client\Cases::class)->name('cases');
        Route::get('/consultation-threads/{id}', \App\Livewire\Client\CaseDetails::class)->name('consultation-thread.details');
        Route::get('/consultations', \App\Livewire\Client\Consultations::class)->name('consultations');
        Route::get('/consultations/{id}', \App\Livewire\Client\ConsultationDetails::class)->name('consultation.details');
        Route::get('/consultations/{consultation}/chat', \App\Livewire\ConsultationChat::class)->name('consultation.chat');
        Route::get('/consultations/{consultation}/video', \App\Livewire\ConsultationVideo::class)->name('consultation.video');
        Route::get('/messages', function () { return 'Client Messages'; })->name('messages');
        Route::get('/documents', \App\Livewire\Client\MyDocuments::class)->name('documents');
        Route::get('/documents/{id}', \App\Livewire\Client\DocumentDetails::class)->name('document.details');
        Route::get('/transactions', \App\Livewire\Client\Transactions::class)->name('transactions');
        Route::get('/transactions/{id}', \App\Livewire\Client\TransactionDetails::class)->name('transactions.details');
        Route::get('/review/consultation/{consultationId}', \App\Livewire\Client\LeaveReview::class)->name('review.consultation');
        Route::get('/review/document/{documentRequestId}', \App\Livewire\Client\LeaveReview::class)->name('review.document');
    });

    // Lawyer Routes
    Route::prefix('lawyer')->name('lawyer.')->middleware('lawyer')->group(function () {
        Route::get('/dashboard', \App\Livewire\Lawyer\Dashboard::class)->name('dashboard');
        Route::get('/profile', \App\Livewire\Lawyer\Profile::class)->name('profile');
        Route::get('/profile/professional', \App\Livewire\Lawyer\ProfileProfessional::class)->name('profile.professional');
        Route::get('/profile/services', \App\Livewire\Lawyer\ProfileServices::class)->name('profile.services');
        Route::get('/profile/security', \App\Livewire\Lawyer\ProfileSecurity::class)->name('profile.security');
        Route::get('/profile/notifications', \App\Livewire\Lawyer\ProfileNotifications::class)->name('profile.notifications');
        Route::get('/consultation-threads', \App\Livewire\Lawyer\Cases::class)->name('cases');
        Route::get('/consultation-threads/{consultation}/book-service', \App\Livewire\Lawyer\BookService::class)->name('book-service');
        Route::get('/consultation-threads/{id}', \App\Livewire\Lawyer\CaseDetails::class)->name('consultation-thread.details');
        Route::get('/consultations', \App\Livewire\Lawyer\Consultations::class)->name('consultations');
        Route::get('/consultations/{id}', \App\Livewire\Lawyer\ConsultationDetails::class)->name('consultation.details');
        Route::get('/consultations/{consultation}/chat', \App\Livewire\ConsultationChat::class)->name('consultation.chat');
        Route::get('/consultations/{consultation}/video', \App\Livewire\ConsultationVideo::class)->name('consultation.video');
        Route::get('/schedule', \App\Livewire\Lawyer\Schedule::class)->name('schedule');
        Route::get('/documents', \App\Livewire\Lawyer\Documents::class)->name('documents');
        Route::get('/documents/create', \App\Livewire\Lawyer\CreateDocument::class)->name('documents.create');
        Route::get('/documents/{id}/edit', \App\Livewire\Lawyer\EditDocument::class)->name('documents.edit');
        Route::get('/document-requests', \App\Livewire\Lawyer\DocumentRequests::class)->name('document-requests');
        Route::get('/document-requests/{id}', \App\Livewire\Lawyer\DocumentRequestDetails::class)->name('document-request.details');
        Route::get('/transactions', \App\Livewire\Lawyer\Transactions::class)->name('transactions');
        Route::get('/transactions/{id}', \App\Livewire\Lawyer\TransactionDetails::class)->name('transactions.details');
        Route::get('/earnings', function () { return 'Lawyer Earnings'; })->name('earnings');
        Route::get('/clients', function () { return 'Lawyer Clients'; })->name('clients');
        Route::get('/messages', function () { return 'Lawyer Messages'; })->name('messages');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/newsletter', \App\Livewire\Admin\NewsletterBlast::class)->name('newsletter');
        Route::get('/email-list', \App\Livewire\Admin\EmailList::class)->name('email-list');
        Route::post('/upload-image', [\App\Http\Controllers\Admin\ImageUploadController::class, 'upload'])->name('upload-image');
        Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users');
        Route::get('/lawyers', \App\Livewire\Admin\LawyerVerification::class)->name('lawyers');
        Route::get('/lawyer/{lawyer}/document/{type}', [\App\Http\Controllers\Admin\LawyerDocumentController::class, 'view'])->name('lawyer.document');
        Route::get('/lawyer/{lawyer}/review', function () { return 'Admin Lawyer Review'; })->name('lawyer.review');
        Route::get('/consultations', \App\Livewire\Admin\Consultations::class)->name('consultations');
        Route::get('/consultation/{id}', \App\Livewire\Admin\ConsultationDetails::class)->name('consultation.details');
        Route::get('/transactions', \App\Livewire\Admin\Transactions::class)->name('transactions');
        Route::get('/refunds', \App\Livewire\Admin\RefundManagement::class)->name('refunds');
        Route::get('/payouts', \App\Livewire\Admin\Payouts::class)->name('payouts');
        Route::get('/reports', \App\Livewire\Admin\Reports::class)->name('reports');
        Route::get('/content', \App\Livewire\Admin\ContentManagement::class)->name('content');
        Route::get('/specializations', \App\Livewire\Admin\SpecializationManagement::class)->name('specializations');
        Route::get('/settings', \App\Livewire\Admin\AISettings::class)->name('settings');
    });

    // Booking Routes
    Route::get('/book/{lawyer}', \App\Livewire\BookConsultation::class)->name('consultation.book');
    
    // Document Request (Clients only - must be logged in)
    Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsClient::class])->group(function () {
        Route::get('/documents/{id}/request', \App\Livewire\RequestDocument::class)->name('documents.request');
    });
    
    // Payment Routes - Using PayMongo Checkout
    Route::get('/consultation/{consultation}/payment', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/consultation/{consultation}/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/consultation/{consultation}/payment/failed', [\App\Http\Controllers\PaymentController::class, 'failed'])->name('payment.failed');
    
    // Document Payment Routes
    Route::get('/document/{request}/payment', [\App\Http\Controllers\PaymentController::class, 'documentCheckout'])->name('document.payment');
    Route::get('/document/{request}/payment/success', [\App\Http\Controllers\PaymentController::class, 'documentSuccess'])->name('document.payment.success');
    Route::get('/document/{request}/payment/failed', [\App\Http\Controllers\PaymentController::class, 'documentFailed'])->name('document.payment.failed');
});

// PayMongo Webhook (outside auth middleware - public endpoint)
Route::post('/paymongo/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('paymongo.webhook');

// Twilio Webhook (outside auth middleware - public endpoint)
Route::post('/twilio/webhook/room-status', function (Illuminate\Http\Request $request) {
    \Log::info('Twilio room status webhook received', $request->all());
    return response()->json(['status' => 'ok']);
})->name('twilio.webhook.room-status');

// Pusher Broadcasting Authentication (requires auth)
Route::post('/broadcasting/auth', function (Illuminate\Http\Request $request) {
    $user = $request->user();
    
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    $channelName = $request->input('channel_name');
    $socketId = $request->input('socket_id');
    
    $broadcastingService = app(\App\Services\BroadcastingService::class);
    
    try {
        $auth = $broadcastingService->authenticateChannel($user, $channelName);
        return response($auth, 200);
    } catch (\Exception $e) {
        \Log::error('Broadcasting auth failed', [
            'channel' => $channelName,
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
        return response()->json(['error' => $e->getMessage()], 403);
    }
})->middleware('auth');

require __DIR__.'/settings.php';

// Debug routes (only in debug mode)
if (file_exists(__DIR__.'/debug.php')) {
    require __DIR__.'/debug.php';
}

// Newsletter Routes
Route::get('/newsletter/unsubscribe/{token}', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::post('/newsletter/unsubscribe/{token}', [App\Http\Controllers\NewsletterController::class, 'confirmUnsubscribe'])->name('newsletter.confirm-unsubscribe');
