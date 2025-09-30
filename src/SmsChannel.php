<?php

namespace Pwd\SmsNotificationChannel;

use Pwd\SmsNotificationChannel\Messages\SmsMessage;
use Pwd\SmsNotificationChannel\Services\SmsInterface;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * The SMS service implementation.
     *
     * @var \App\Interfaces\SmsServiceInterface
     */
    protected $smsService;

    /**
     * Create a new channel instance.
     *
     * @param  \App\Interfaces\SmsServiceInterface  $smsService
     * @return void
     */
    public function __construct(SmsInterface $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // \Log::info("notification = ".print_r($notification,true));
        // \Log::info("notifiable = ".print_r($notifiable,true));
        $message = $notification->toSms($notifiable);
        // \Log::info("message = ".print_r($message,true));
        // \Log::info("getContent = ".print_r($message->getContent(),true));

        if (! $message instanceof SmsMessage) {
            return;
        }

        $recipient = $notifiable->routeNotificationFor('sms', $notification);

        if (empty($recipient)) {
            return;
        }

        $message->send($this->smsService);

        // $this->smsService->send($recipient, $message->getContent());

    }
}
