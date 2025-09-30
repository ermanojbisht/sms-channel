<?php

namespace Pwd\SmsNotificationChannel\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsGatewayHubService implements SmsInterface
{
    protected $apiUrl;

    protected $apiKey;

    protected $senderId;

    protected $entityId;

    public function __construct()
    {
        $this->apiUrl = config('services.smsgatewayhub.url');
        $this->apiKey = config('services.smsgatewayhub.apiKey');
        $this->senderId = config('services.smsgatewayhub.senderId');
        $this->entityId = config('services.smsgatewayhub.entityId');
    }

    public function sendMsg($phone_number, $message, $templateId)
    {
        if (empty($this->apiUrl) || empty($this->apiKey)) {
            Log::error('SmsGatewayHub service is not configured. Please check your .env file.');

            return false;
        }

        try {

            $client = new Client;
            $data = [
                'Account' => [
                    'APIkey' => $this->apiKey,
                    'SenderId' => $this->senderId,
                    'Channel' => '2',
                    'DCS' => '0',
                    'SchedTime' => null,
                    'GroupId' => null,
                    'EntityId' => $this->entityId,
                ],
                'Messages' => [
                    [
                        'Text' => $message,
                        'DLTTemplateId' => $templateId,
                        'Number' => $phone_number,
                    ],
                ],
            ];
            // \Log::info("data = ".print_r($data,true));
            $response = $client->post($this->apiUrl, ['form_params' => $data], ['Content-Type' => 'application/json']);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                Log::info('SMS sent successfully via SmsGatewayHub to: '.$phone_number.' | Response: '.$response->getBody());

                return json_decode($response->getBody(), true);
            } else {
                Log::error('Failed to send SMS via SmsGatewayHub to: '.$phone_number, [
                    'status' => $response->getStatusCode(),
                    'response' => (string) $response->getBody(),
                ]);

                return false;
            }

        } catch (\Exception $e) {
            Log::error('Exception while sending SMS via SmsGatewayHub', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
