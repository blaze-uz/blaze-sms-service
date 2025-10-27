<?php

namespace Blaze\SmsService\Facades;
use Illuminate\Support\Facades\Facade;


/**
 * @date 10/22/2025 10:54 AM
 */
class SMS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}
