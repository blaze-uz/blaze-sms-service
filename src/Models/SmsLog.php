<?php

namespace Blaze\SmsService\Models;

use Blaze\SmsService\Support\Enums\SmsLogStatusEnum;
use Blaze\SmsService\Support\HelperClasses\Phone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 *
 * @property int $id
 * @property int|null $receiver_id
 * @property string|null $receiver_type
 * @property int|null $sms_template_id
 * @property string $driver
 * @property Phone $phone
 * @property string $message
 * @property SmsLogStatusEnum $status
 * @property array|null $response
 * @property string|null $external_id
 * @property string|null $sent_at
 * @property string|null $error_message
 **/
class SmsLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'receiver_id',
        'receiver_type',
        'sms_template_id',
        'driver',
        'phone',
        'message',
        'status',
        'response',
        'external_id',
        'sent_at',
        'error_message',
        'eskiz_status',
        'result_date',
        'total_price',
        'price',
        'parts_count',
        'is_ad',
    ];

    protected $casts = [
        'response' => 'array',
        'status' => SmsLogStatusEnum::class,
        'is_ad' => 'boolean'
    ];

    public function receiver(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function template(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class, 'sms_template_id');
    }
}
