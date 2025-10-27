<?php

namespace Blaze\SmsService\Jobs;

use Blaze\SmsService\Models\SmsLog;
use Blaze\SmsService\Support\SmsManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * @date 10/21/2025 4:55 PM
 */
class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SmsLog $smsLog;
    public string $driver;
    public string $tenant_id;

    public function __construct(SmsLog $smsLog)
    {
        $this->smsLog = $smsLog;
        $this->tenant_id = tenant('id'); // saqlab qo'y
    }

    public function handle()
    {
        // Bu joyda Stancl avtomatik ravishda tenant context ni tiklaydi
        // tenancy()->initialized === true bo‘ladi
        Log::debug("Tenant" , [
            'tenancy' => \tenancy()->tenant,
            'smsLog' => $this->smsLog,
        ]);

        $smsManager = new SmsManager(config('sms'));
        $driverInstance = $smsManager->driver($this->smsLog->driver);

        $result = $driverInstance->send($this->smsLog);

        // there return BaseResult if success false bo'lsa 1 minutdan keyin qayta urinish
        if (!$result->success) {
            $this->release(60);
        }
    }
}
