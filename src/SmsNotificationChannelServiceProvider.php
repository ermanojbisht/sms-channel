<?php

namespace Pwd\SmsNotificationChannel;

use Illuminate\Support\ServiceProvider;
use Pwd\SmsNotificationChannel\Services\SmsInterface;
use Pwd\SmsNotificationChannel\Services\SmsGatewayHubService;
use Pwd\SmsNotificationChannel\Services\LocalSmsService;

class SmsNotificationChannelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SmsInterface::class, function ($app) {
            if (config('app.env') === 'production') {
                return new SmsGatewayHubService();
            } else {
                return new LocalSmsService();
            }
        });
    }

    public function boot()
    {
        //
    }
}
