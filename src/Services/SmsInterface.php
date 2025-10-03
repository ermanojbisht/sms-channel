<?php

namespace Pwd\SmsNotificationChannel\Services;

interface SmsInterface
{
    public function sendMsg($phone_number, $message, $templateId, $language = 'english');
}