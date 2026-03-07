<?php

namespace App\Services;

use App\Models\Consultation;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

/**
 * Twilio Video Service
 * 
 * Handles video room creation, access tokens, and room management
 */
class TwilioVideoService
{
    private Client $client;
    private string $accountSid;
    private string $apiKeySid;
    private string $apiKeySecret;

    public function __construct()
    {
        $this->accountSid = config('twilio.account_sid');
        $this->apiKeySid = config('twilio.api_key_sid');
        $this->apiKeySecret = config('twilio.api_key_secret');
        
        $this->client = new Client(
            $this->accountSid,
            config('twilio.auth_token')
        );
    }

    /**
     * Create a video room for consultation
     * Room name format: consultation-{id}
     */
    public function createRoom(Consultation $consultation): ?string
    {
        try {
            $roomName = "consultation-{$consultation->id}";
            
            // Create room with specific settings
            $room = $this->client->video->v1->rooms->create([
                'uniqueName' => $roomName,
                'type' => 'group', // Supports up to 50 participants
                'recordParticipantsOnConnect' => $consultation->recording_enabled ?? false,
                'maxParticipants' => 2, // Only client and lawyer
                'statusCallback' => route('twilio.webhook.room-status'),
                'statusCallbackMethod' => 'POST',
            ]);

            Log::info('Twilio room created', [
                'consultation_id' => $consultation->id,
                'room_sid' => $room->sid,
                'room_name' => $roomName,
            ]);

            return $room->sid;

        } catch (\Exception $e) {
            Log::error('Failed to create Twilio room', [
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Generate access token for user to join room
     */
    public function generateAccessToken(Consultation $consultation, int $userId, string $userName): string
    {
        $roomName = "consultation-{$consultation->id}";
        
        // Create access token
        $token = new AccessToken(
            $this->accountSid,
            $this->apiKeySid,
            $this->apiKeySecret,
            3600, // Token valid for 1 hour
            $userId // User identity
        );

        // Create video grant
        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);
        
        // Add grant to token
        $token->addGrant($videoGrant);

        Log::info('Access token generated', [
            'consultation_id' => $consultation->id,
            'user_id' => $userId,
            'room_name' => $roomName,
        ]);

        return $token->toJWT();
    }

    /**
     * Get room status
     */
    public function getRoomStatus(string $roomSid): ?array
    {
        try {
            $room = $this->client->video->v1->rooms($roomSid)->fetch();

            return [
                'sid' => $room->sid,
                'name' => $room->uniqueName,
                'status' => $room->status,
                'duration' => $room->duration,
                'participants' => $this->getRoomParticipants($roomSid),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get room status', [
                'room_sid' => $roomSid,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get participants in room
     */
    public function getRoomParticipants(string $roomSid): array
    {
        try {
            $participants = $this->client->video->v1
                ->rooms($roomSid)
                ->participants
                ->read();

            return array_map(function ($participant) {
                return [
                    'sid' => $participant->sid,
                    'identity' => $participant->identity,
                    'status' => $participant->status,
                    'duration' => $participant->duration,
                ];
            }, $participants);

        } catch (\Exception $e) {
            Log::error('Failed to get room participants', [
                'room_sid' => $roomSid,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Complete room (end consultation)
     * This will disconnect all participants and mark room as completed
     */
    public function completeRoom(string $roomSid): bool
    {
        try {
            $room = $this->client->video->v1
                ->rooms($roomSid)
                ->update(['status' => 'completed']);

            Log::info('Twilio room completed', [
                'room_sid' => $roomSid,
                'status' => $room->status,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to complete Twilio room', [
                'room_sid' => $roomSid,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Remove participant from room
     */
    public function removeParticipant(string $roomSid, string $participantSid): bool
    {
        try {
            $this->client->video->v1
                ->rooms($roomSid)
                ->participants($participantSid)
                ->update(['status' => 'disconnected']);

            Log::info('Participant removed from room', [
                'room_sid' => $roomSid,
                'participant_sid' => $participantSid,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to remove participant', [
                'room_sid' => $roomSid,
                'participant_sid' => $participantSid,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get room recordings (if enabled)
     */
    public function getRoomRecordings(string $roomSid): array
    {
        try {
            $recordings = $this->client->video->v1
                ->rooms($roomSid)
                ->recordings
                ->read();

            return array_map(function ($recording) {
                return [
                    'sid' => $recording->sid,
                    'status' => $recording->status,
                    'duration' => $recording->duration,
                    'size' => $recording->size,
                    'url' => $recording->url,
                ];
            }, $recordings);

        } catch (\Exception $e) {
            Log::error('Failed to get room recordings', [
                'room_sid' => $roomSid,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
}
