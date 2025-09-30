# PWD SMS Notification Channel

This package provides a simple and configurable way to send SMS notifications in a Laravel application using the SmsGatewayHub provider.

## Installation

To install the package, you first need to add the repository to your `composer.json` file. If you are using a private repository, you will need to configure authentication.

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://your-git-provider.com/your-username/your-repo-name.git"
    }
]
```

Then, you can require the package using Composer:

```bash
composer require pwd/sms-notification-channel
```

## Configuration

1.  Add the following credentials to your `.env` file:

    ```
    SMS_URL=https://www.smsgatewayhub.com/api/mt/SendSMS
    SMS_APIkey=YOUR_API_KEY
    SMS_SenderId=YOUR_SENDER_ID
    SMS_EntityId=YOUR_ENTITY_ID
    ```

2.  Add the following configuration to your `config/services.php` file:

    ```php
    'smsgatewayhub' => [
        'url' => env('SMS_URL', 'https://www.smsgatewayhub.com/api/mt/SendSMS'),
        'apiKey' => env('SMS_APIkey'),
        'senderId' => env('SMS_SenderId'),
        'entityId' => env('SMS_EntityId'),
    ],
    ```

## Usage

To use the SMS notification channel, you can create a new notification class that uses the `SmsChannel`.

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Pwd\SmsNotificationChannel\SmsChannel;
use Pwd\SmsNotificationChannel\Messages\SmsMessage;

class ExampleSmsNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return (new SmsMessage)
            ->to($notifiable->phone_number) // Assuming the user has a 'phone_number' attribute
            ->templateId('YOUR_DLT_TEMPLATE_ID')
            ->line('This is a test message from your application.');
    }
}
```

Then, you can send the notification to a notifiable user like this:

```php
$user->notify(new ExampleSmsNotification());
```

## Local Development

For local development, the package uses the `LocalSmsService`, which logs SMS messages to the `storage/logs/sms.log` file instead of sending them. This allows you to test the SMS functionality without sending actual SMS messages.
