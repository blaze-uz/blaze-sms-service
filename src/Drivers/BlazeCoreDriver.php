<?php

namespace Blaze\SmsService\Drivers;

use Blaze\SmsService\Contracts\SmsDriver;
use Blaze\SmsService\Models\SmsLog;
use Blaze\SmsService\Support\Enums\SmsLogStatusEnum;
use Blaze\SmsService\Support\Result\BaseResult;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 **/
class BlazeCoreDriver implements SmsDriver
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @throws ConnectionException
     * @throws \Exception
     */
    public function send(SmsLog $smsLog): BaseResult
    {
        $endpoint = $this->config['endpoint'] ?? null;

        if (! $endpoint) {
            throw new \Exception('BlazeCore endpoint sozlanmagan.');
        }

        $message = [
            'phone_number' => $smsLog->phone,
            'sms_template_code' => 'custom',
            'params' => [
                'message' => $smsLog->message,
                'channel' => $this->config['channel'],
                'template_id' => $smsLog->sms_template_id,
                'eskiz_template_id' => $smsLog->template?->eskiz_template_id ?? null,
                'sms_group_id' => 1
            ],
        ];

        $response = Http::withHeaders([
            'sms-token' => $this->resolveTenantToken() ?? $this->config['token'],
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($endpoint, $message);

        $statusCode = $response->status();
        $responseData = $response->json();

        if ($statusCode == 429) {
            return new BaseResult(false);
        } else {
            if ($statusCode == 200) {
                $smsLog->update([
                    'status' => SmsLogStatusEnum::PENDING->value,
                    'external_id' => $responseData['data']['id'],
                    'sent_at' => $responseData['data']['sent_at'],
                ]);
            } else {
                $smsLog->update([
                    'status' => SmsLogStatusEnum::FAILED->value,
                    'error_message' => $responseData['message'],
                ]);
            }
        }

        return new BaseResult(true);
    }

    protected function resolveTenantToken(): ?string
    {
        if (tenancy()->initialized) {
            $tenant = tenancy()->tenant;

            return $tenant->getTokenFor($this->config['central_token_app_name']);
        }

        return null;
    }
}
