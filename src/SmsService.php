<?php

namespace Blaze\SmsService;

use Blaze\SmsService\Contracts\SmsReceiver;
use Blaze\SmsService\Jobs\SendSmsJob;
use Blaze\SmsService\Models\SmsAction;
use Blaze\SmsService\Models\SmsLog;
use Blaze\SmsService\Models\SmsTemplate;
use Blaze\SmsService\Support\Enums\SmsDriverEnum;
use Blaze\SmsService\Support\Enums\SmsLogStatusEnum;
use Blaze\SmsService\Support\HelperClasses\Phone;

/**
 * @date 10/22/2025 10:53 AM
 */
class SmsService
{
    public function sendByAction(string $action, SmsReceiver $receiver, ?string $driver = SmsDriverEnum::BLAZE_CORE->value): ?SmsLog 
    {
        $template = SmsAction::getTemplateFor($action);

        if (! $template) {
            \Illuminate\Support\Facades\Log::error('Template not found for action', [
                'action' => $action,
                'message' => 'Sms yuborish uchun template topilmadi, template yoq yoki aktiv emas',
            ]);
            return null;
        }

        return $this->createAndSendSms($template, $receiver, $driver);
    }

    public function sendByTemplateId(int $templateId, SmsReceiver $receiver,?string $driver = SmsDriverEnum::BLAZE_CORE->value): ?SmsLog 
    {
        $template = SmsTemplate::find($templateId);

        if (! $template) {
            \Illuminate\Support\Facades\Log::error('Template not found for template id', [
                'template_id' => $templateId,
                'message' => 'Sms yuborish uchun template topilmadi, template yoq yoki aktiv emas',
            ]);
            return null;
        }

        return $this->createAndSendSms($template, $receiver, $driver);
    }

    private function createAndSendSms(SmsTemplate $template, SmsReceiver $receiver,string $driver): SmsLog {

        $message = $this->parseTemplate($template->body, $receiver->data);

        $smsLog = SmsLog::query()->create([
            'receiver_type' => $receiver->type,
            'receiver_id' => $receiver->id,
            'sms_template_id' => $template->id,
            'phone' => new Phone($receiver->phone),
            'message' => $message,
            'driver' => $driver,
            'status' => SmsLogStatusEnum::PENDING,
        ]);

        dispatch_sync(new SendSmsJob($smsLog));

        return $smsLog;
    }

    protected function parseTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }

        return $template;
    }
}
