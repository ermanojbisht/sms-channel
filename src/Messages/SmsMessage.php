<?php

namespace Pwd\SmsNotificationChannel\Messages;

use Illuminate\Support\Facades\Log;

class SmsMessage
{
    protected $lines = [];

    protected $variables = [];

    protected $templateId; // This is the DLT Template ID for the gateway

    protected $to;

    public function __construct($lines = [])
    {
        $this->lines = $lines;

        return $this;
    }

    public function templateId($templateId): self
    {
        $this->templateId = $templateId;

        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function line($line = ''): self
    {
        $this->lines[] = $line;

        return $this;
    }

    public function variables(array $variables): self
    {
        $this->variables = $variables;

        return $this;
    }

    public function getContent(): string
    {
        $content = implode("\n", $this->lines);
        foreach ($this->variables as $key => $value) {
            $content = str_replace('{#'.$key.'#}', $value, $content);
        }

        return $content;
    }

    public function send($smsService)
    {
        if (empty($this->to)) {
            Log::warning('SMS not sent: Missing recipient \'to\' number.', [
                'template_id' => $this->templateId,
            ]);

            return; // Gracefully exit
        }

        if (empty($this->lines)) {
            Log::warning('SMS not sent: Message content is empty.', [
                'to' => $this->to,
                'template_id' => $this->templateId,
            ]);

            return; // Gracefully exit
        }

        if (empty($this->templateId)) {
            Log::warning('SMS not sent: DLT Template ID is missing.', [
                'to' => $this->to,
            ]);

            return; // Gracefully exit
        }

        $text = $this->getContent();
        $smsService->sendMsg($this->to, $text, $this->templateId);
    }
}
