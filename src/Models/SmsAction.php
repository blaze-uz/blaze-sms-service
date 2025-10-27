<?php

namespace Blaze\SmsService\Models;

use Blaze\SmsService\Support\Enums\SmsActionEnum;
use Illuminate\Database\Eloquent\Model;

/**
 * @date 10/20/2025 5:24 PM
 */
class SmsAction extends Model
{
    protected $fillable = [
        'action',
        'sms_template_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'action' => SmsActionEnum::class,
    ];

    public function template(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class, 'sms_template_id');
    }

    // $template = SmsAction::getTemplateFor(SmsActionEnum::PAYMENT_RECEIVED->value);

    public static function getTemplateFor(string $action): ?SmsTemplate
    {
        $smsAction = self::where('action', $action)
            ->where('is_active', true)
            ->first();

        return $smsAction?->template;
    }
}
