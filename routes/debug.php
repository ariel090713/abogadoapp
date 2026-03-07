<?php

use App\Models\Consultation;
use Illuminate\Support\Facades\Route;

// Debug route - remove after fixing
if (config('app.debug')) {
    Route::get('/debug/video-consultations', function () {
        $consultations = Consultation::where('consultation_type', 'video')
            ->with(['client', 'lawyer'])
            ->get()
            ->map(function ($c) {
                $now = now();
                $scheduledAt = $c->scheduled_at;
                $endTime = $scheduledAt ? $scheduledAt->copy()->addMinutes($c->duration) : null;
                
                $videoStatus = 'unknown';
                if ($scheduledAt) {
                    if ($now->lt($scheduledAt)) {
                        $videoStatus = 'waiting';
                    } elseif ($now->gte($scheduledAt) && $now->lt($endTime)) {
                        $videoStatus = 'active';
                    } else {
                        $videoStatus = 'ended';
                    }
                }
                
                return [
                    'id' => $c->id,
                    'status' => $c->status,
                    'scheduled_at' => $scheduledAt?->format('Y-m-d H:i:s'),
                    'duration' => $c->duration,
                    'end_time' => $endTime?->format('Y-m-d H:i:s'),
                    'video_status' => $videoStatus,
                    'client' => $c->client->name,
                    'lawyer' => $c->lawyer->name,
                    'client_url' => route('client.consultation.video', $c),
                    'lawyer_url' => route('lawyer.consultation.video', $c),
                ];
            });
        
        return response()->json([
            'current_time' => now()->format('Y-m-d H:i:s'),
            'consultations' => $consultations,
        ], 200, [], JSON_PRETTY_PRINT);
    });
}
