<?php

namespace Blaze\SmsService\Models;

use Blaze\SmsService\Support\Enums\SmsTemplateStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 *
 * @property string $name
 * @property string $body
 * @property string $status
 * @property string|null $eskiz_template_id
 * @property string $moderation_response_at
 **/
class SmsTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'sms_templates';

    protected $fillable = [
        'name',
        'body',
        'status',
        'eskiz_template_id',
        'moderation_response_at',
    ];

    /**
     * Send this template to the configured SMS driver for approval.
     */
    public function sendForApproval(): bool
    {
        try {
            $driver = config('sms.default');
            $endpoint = config("sms.drivers.$driver.template_moderation_endpoint");

            if (! $endpoint) {
                throw new \Exception("Template approval endpoint not configured for driver [$driver].");
            }

            $validator = Validator::make([
                'template' => $this->body,
                'sample' => $this->body,
            ], [
                'template' => 'required|string',
                'sample' => 'required|string|min:10',
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }


            // Log the request
            Log::info('Template moderation request', [
                'headers' => [
                    'sms-token' => $this->resolveTenantToken(),
                ],
                'endpoint' => $endpoint,
                'body' => [
                    'template' => $this->body,
                    'sample' => $this->body,
                    'external_id' => $this->id,
                ],
            ]);

            $response = Http::withHeaders([
                'sms-token' => $this->resolveTenantToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($endpoint, [
                'template' => $this->body,
                'sample' => $this->body,
                'external_id' => (string) $this->id,
            ]);

            Log::info('Template moderation response', [
                'response' => $response->body(),
            ]);

            if (! $response->successful()) {
                throw new \Exception('Failed to send template to moderation');
            }

            $this->status = SmsTemplateStatus::MODERATION->value;
            $this->save();

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    /**
     * Handle callback from the SMS driver and update template status.
     */
    public function handleCallback(array $data): bool
    {
        try {
            $status = $data['status'] === 'approved' ? SmsTemplateStatus::APPROVED->value : SmsTemplateStatus::REJECTED->value;

            if (! in_array($status, ['approved', 'rejected'])) {
                return false;
            }

            $this->status = $status;
            $this->eskiz_template_id = $data['eskiz_template_id'] ?? null;
            $this->moderation_response_at = now();
            $this->save();

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }

    protected function resolveTenantToken(): ?string
    {
        $config = config('sms.drivers.blaze_core');

        if (tenancy()->initialized) {
            $tenant = tenancy()->tenant;

            return $tenant->getTokenFor($config['central_token_app_name']);
        }

        return $config['token'];
    }
}
