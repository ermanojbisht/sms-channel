<?php

namespace Pwd\SmsNotificationChannel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Pwd\SmsNotificationChannel\Services\SmsInterface;
use Pwd\SmsNotificationChannel\Services\SmsGatewayHubService;
use Pwd\SmsNotificationChannel\Services\LocalSmsService;

class SmsNotificationChannelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SmsInterface::class, function ($app) {
           if (config('app.env') === 'production' || config('site.sms_testing_in_real_channel') === true) {
                return new SmsGatewayHubService();
            } else {
                return new LocalSmsService();
            }
        });
    }

    public function boot()
    {
        Notification::extend('sms', function ($app) {
            return $app->make(SmsChannel::class);
        }); 
    }
}
