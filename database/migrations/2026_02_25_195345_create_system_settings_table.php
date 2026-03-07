<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->string('group')->default('general'); // general, deadlines, payment, notifications
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default deadline settings
        $settings = [
            // Video/Phone/Chat Consultation Deadlines
            [
                'key' => 'deadline.video_min_advance_booking_hours',
                'value' => '3',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Minimum Advance Booking (Hours)',
                'description' => 'Minimum hours required before session time for video/phone/chat bookings',
            ],
            [
                'key' => 'deadline.video_lawyer_response_hours',
                'value' => '24',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Lawyer Response Time (Hours)',
                'description' => 'Standard hours for lawyer to respond to video/phone/chat requests',
            ],
            [
                'key' => 'deadline.video_lawyer_response_buffer_hours',
                'value' => '2',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Lawyer Response Buffer (Hours)',
                'description' => 'Minimum hours before session that lawyer must respond',
            ],
            [
                'key' => 'deadline.video_quote_response_hours',
                'value' => '24',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Quote Response Time (Hours)',
                'description' => 'Standard hours for client to respond to quote',
            ],
            [
                'key' => 'deadline.video_quote_response_buffer_hours',
                'value' => '1',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Quote Response Buffer (Hours)',
                'description' => 'Minimum hours before session that client must accept quote',
            ],
            [
                'key' => 'deadline.video_payment_hours',
                'value' => '24',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Payment Time (Hours)',
                'description' => 'Standard hours for client to complete payment',
            ],
            [
                'key' => 'deadline.video_payment_buffer_hours',
                'value' => '1',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Payment Buffer (Hours)',
                'description' => 'Minimum hours before session that payment must be completed',
            ],

            // Document Review Deadlines
            [
                'key' => 'deadline.document_lawyer_response_hours',
                'value' => '48',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Document Review - Lawyer Response (Hours)',
                'description' => 'Hours for lawyer to respond to document review requests',
            ],
            [
                'key' => 'deadline.document_quote_response_hours',
                'value' => '48',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Document Review - Quote Response (Hours)',
                'description' => 'Hours for client to respond to document review quote',
            ],
            [
                'key' => 'deadline.document_payment_hours',
                'value' => '24',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Document Review - Payment Time (Hours)',
                'description' => 'Hours for client to complete payment for document review',
            ],
            [
                'key' => 'deadline.document_min_turnaround_days',
                'value' => '1',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Document Review - Min Turnaround (Days)',
                'description' => 'Minimum business days for document review completion',
            ],
            [
                'key' => 'deadline.document_max_turnaround_days',
                'value' => '14',
                'type' => 'integer',
                'group' => 'deadlines',
                'label' => 'Document Review - Max Turnaround (Days)',
                'description' => 'Maximum business days for document review completion',
            ],

            // Platform Settings
            [
                'key' => 'platform.fee_percentage',
                'value' => '10',
                'type' => 'integer',
                'group' => 'payment',
                'label' => 'Platform Fee (%)',
                'description' => 'Percentage fee charged on consultations',
            ],
            [
                'key' => 'platform.min_consultation_fee',
                'value' => '500',
                'type' => 'integer',
                'group' => 'payment',
                'label' => 'Minimum Consultation Fee (PHP)',
                'description' => 'Minimum amount lawyers can charge',
            ],

            // Notification Settings
            [
                'key' => 'notification.reminder_intervals',
                'value' => '[6, 20, 23]',
                'type' => 'json',
                'group' => 'notifications',
                'label' => 'Reminder Intervals (Hours)',
                'description' => 'Hours before deadline to send reminders (JSON array)',
            ],
            [
                'key' => 'notification.enable_sms',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Enable SMS Notifications',
                'description' => 'Send SMS for urgent notifications',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
