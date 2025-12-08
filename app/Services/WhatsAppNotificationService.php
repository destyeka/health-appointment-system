<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    private $twilio;
    private $fromNumber;

    public function __construct()
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $this->fromNumber = config('services.twilio.whatsapp_from');

        if ($accountSid && $authToken) {
            try {
                $this->twilio = new Client($accountSid, $authToken);
            } catch (\Throwable $e) {
                Log::error('Twilio client initialization failed: ' . $e->getMessage());
                $this->twilio = null;
            }
        }
    }

    public function sendMessage(string $toNumber, string $message): bool
    {
        if (!$this->twilio || !$this->fromNumber) {
            Log::warning('Twilio not configured. Message not sent to ' . $toNumber);
            return false;
        }

        try {
            // Normalize numbers
            $toRaw = preg_replace('/^whatsapp:/i', '', trim($toNumber));
            $fromRaw = preg_replace('/^whatsapp:/i', '', trim($this->fromNumber));
            
            $toFormatted = 'whatsapp:' . $toRaw;
            $fromFormatted = 'whatsapp:' . $fromRaw;

            $this->twilio->messages->create(
                $toFormatted,
                [
                    'from' => $fromFormatted,
                    'body' => $message,
                ]
            );

            Log::info('WhatsApp message sent to ' . $toNumber);
            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp send failed: ' . $e->getMessage());
            return false;
        }
    }
}
