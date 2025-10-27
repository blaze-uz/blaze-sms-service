<?php

namespace Blaze\SmsService\Support\Enums;

/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 **/
enum SmsTemplateStatus: string
{
    case NEW = 'new';

    case MODERATION = 'moderation';

    case APPROVED = 'approved';

    case REJECTED = 'rejected';

    public static function getLabel(SmsTemplateStatus $status): string
    {
        return __("sms.status.{$status->value}");
    }

    public static function getAllLabels(): array
    {
        $labels = [];
        foreach (SmsTemplateStatus::cases() as $case) {
            $labels[$case->value] = self::getLabel($case);
        }

        return $labels;
    }

    public static function getColor(SmsTemplateStatus $status): string
    {
        return match ($status) {
            SmsTemplateStatus::NEW => 'bg-gray-100 text-gray-800',
            SmsTemplateStatus::MODERATION => 'bg-blue-100 text-blue-800',
            SmsTemplateStatus::APPROVED => 'bg-green-100 text-green-800',
            SmsTemplateStatus::REJECTED => 'bg-red-100 text-red-800',
        };
    }
}
