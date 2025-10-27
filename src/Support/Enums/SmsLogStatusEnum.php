<?php

namespace Blaze\SmsService\Support\Enums;

enum SmsLogStatusEnum: string
{
    case PENDING = 'pending';
    case SENDING = 'sending';

    case SENT = 'sent';
    case WAITING = 'waiting';
    case FAILED = 'faixled';

    public static function getLabel(SmsLogStatusEnum $status): string
    {
        return __("sms-service::sms_logs.status.{$status->value}");
    }

    public static function getAllLabels(): array
    {
        $labels = [];
        foreach (SmsLogStatusEnum::cases() as $case) {
            $labels[$case->value] = self::getLabel($case);
        }

        return $labels;
    }
}
