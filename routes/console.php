<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule consolidated consultation workflow processing every minute
// This handles: deadlines, status updates, reminders, expirations
Schedule::command('consultations:process')->everyMinute();

// Schedule refund lawyer response deadline processing every hour
// Auto-approves refunds where lawyer did not respond within 7 days
Schedule::command('refunds:process-expired-lawyer-responses')->hourly();
