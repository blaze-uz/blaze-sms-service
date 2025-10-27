<?php

namespace Blaze\SmsService\Support\Enums;

enum SmsActionEnum: string
{
    case PAYMENT_RECEIVED = 'payment_received';
    case SUBSCRIPTION_EXPIRED = 'subscription_expired';
    case PAYMENT_DUE_SOON = 'payment_due_soon';
    case ABSENT_FROM_CLASS = 'absent_from_class';
    case HAPPY_NEW_YEAR = 'happy_new_year';

    public function getLabel(): string
    {
        return __('sms-service::sms-actions.actions.' . $this->value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
