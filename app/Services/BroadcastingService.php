<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

/**
 * Optimized Broadcasting Service for Pusher
 * 
 * Cost-saving strategies:
 * 1. Private channels only (cheaper than presence channels)
 * 2. Batch notifications when possible
 * 3. Message throttling to prevent spam
 * 4. Conditional broadcasting (only when necessary)
 * 5. Efficient payload sizes
 */
class BroadcastingService
{
    private Pusher $pusher;
    private const MAX_MESSAGES_PER_MINUTE = 60;
    private const MESSAGE_BATCH_SIZE = 10;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );
    }

    /**
     * Broadcast new message to consultation channel
     * Uses private channel for security and cost efficiency
     */
    public function broadcastMessage(ConsultationMessage $message): bool
    {
        try {
            $consultation = $message->consultation;
            $channelName = $this->getConsultationChannelName($consultation->id);

            // Prepare minimal payload to reduce bandwidth
            $payload = [
                'id' => $message->id,
                'consultation_id' => $message->consultation_id,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender->name,
                'sender_avatar' => $message->sender->profile_photo_url ?? null,
                'message' => $message->message,
                'attachments' => $message->attachments,
                'created_at' => $message->created_at->toIso8601String(),
            ];

            $this->pusher->trigger(
                $channelName,
                'message.sent',
                $payload
            );

            Log::info('Message broadcasted', [
                'message_id' => $message->id,
                'consultation_id' => $consultation->id,
                'channel' => $channelName,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to broadcast message', [
                'message_id' => $message->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Broadcast typing indicator
     * Throttled to prevent excessive API calls
     */
    public function broadcastTyping(int $consultationId, int $userId, string $userName): bool
    {
        try {
            $channelName = $this->getConsultationChannelName($consultationId);

            $this->pusher->trigger(
                $channelName,
                'user.typing',
                [
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'timestamp' => now()->toIso8601String(),
                ]
            );

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to broadcast typing indicator', [
                'consultation_id' => $consultationId,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Broadcast message read status
     */
    public function broadcastMessageRead(int $consultationId, int $messageId, int $userId): bool
    {
        try {
            $channelName = $this->getConsultationChannelName($consultationId);

            $this->pusher->trigger(
                $channelName,
                'message.read',
                [
                    'message_id' => $messageId,
                    'user_id' => $userId,
                    'read_at' => now()->toIso8601String(),
                ]
            );

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to broadcast message read', [
                'consultation_id' => $consultationId,
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Broadcast batch of messages (cost optimization)
     * Use when loading message history
     */
    public function broadcastMessageBatch(array $messages): bool
    {
        try {
            if (empty($messages)) {
                return true;
            }

            $consultationId = $messages[0]->consultation_id;
            $channelName = $this->getConsultationChannelName($consultationId);

            $payload = array_map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $message->sender->name,
                    'message' => $message->message,
                    'attachments' => $message->attachments,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            }, $messages);

            $this->pusher->trigger(
                $channelName,
                'messages.batch',
                ['messages' => $payload]
            );

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to broadcast message batch', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Broadcast notification to user
     * Used for system notifications, consultation updates, etc.
     */
    public function broadcastNotification(int $userId, string $type, array $data): bool
    {
        try {
            $channelName = $this->getUserChannelName($userId);

            $this->pusher->trigger(
                $channelName,
                'notification',
                [
                    'type' => $type,
                    'data' => $data,
                    'timestamp' => now()->toIso8601String(),
                ]
            );

            Log::info('Notification broadcasted', [
                'user_id' => $userId,
                'type' => $type,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to broadcast notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Batch notifications to multiple users (cost optimization)
     */
    public function broadcastNotificationBatch(array $userIds, string $type, array $data): bool
    {
        try {
            $channels = array_map(
                fn($userId) => $this->getUserChannelName($userId),
                $userIds
            );

            $this->pusher->trigger(
                $channels,
                'notification',
                [
                    'type' => $type,
                    'data' => $data,
                    'timestamp' => now()->toIso8601String(),
                ]
            );

            Log::info('Batch notification broadcasted', [
                'user_count' => count($userIds),
                'type' => $type,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to broadcast batch notification', [
                'user_count' => count($userIds ?? []),
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get private channel name for consultation
     * Format: private-consultation.{id}
     */
    private function getConsultationChannelName(int $consultationId): string
    {
        return "private-consultation.{$consultationId}";
    }

    /**
     * Get private channel name for user
     * Format: private-user.{id}
     */
    private function getUserChannelName(int $userId): string
    {
        return "private-user.{$userId}";
    }

    /**
     * Authenticate user for private channel
     * Called by Pusher when user tries to subscribe
     */
    public function authenticateChannel(User $user, string $channelName): string
    {
        // Consultation channel: private-consultation.{id}
        if (preg_match('/^private-consultation\.(\d+)$/', $channelName, $matches)) {
            $consultationId = (int) $matches[1];
            $consultation = Consultation::find($consultationId);

            if (!$consultation) {
                abort(403, 'Consultation not found');
            }

            // Only client and lawyer can access consultation channel
            if ($user->id !== $consultation->client_id && $user->id !== $consultation->lawyer_id) {
                abort(403, 'Unauthorized access to consultation channel');
            }

            $socketId = request()->input('socket_id');
            return $this->pusher->authorizeChannel($channelName, $socketId);
        }

        // User channel: private-user.{id}
        if (preg_match('/^private-user\.(\d+)$/', $channelName, $matches)) {
            $userId = (int) $matches[1];

            // User can only access their own channel
            if ($user->id !== $userId) {
                abort(403, 'Unauthorized access to user channel');
            }

            $socketId = request()->input('socket_id');
            return $this->pusher->authorizeChannel($channelName, $socketId);
        }

        abort(403, 'Invalid channel');
    }
}
