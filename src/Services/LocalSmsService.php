<?php
namespace Pwd\SmsNotificationChannel\Services;

use Illuminate\Support\Facades\Log;

class LocalSmsService implements SmsInterface
{
    public function sendMsg($phone_number, $message, $templateId, $language = 'english'): bool
    {
        Log::channel('sms')->info("Sending SMS to {$phone_number}. message:{$message} ,templateId:{$templateId} ,language:{$language}");

        return true;
    }
}
