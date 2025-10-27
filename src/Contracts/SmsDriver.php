<?php

namespace Blaze\SmsService\Contracts;

use Blaze\SmsService\Models\SmsLog;
use Blaze\SmsService\Support\Result\BaseResult;

/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 **/
interface SmsDriver
{
    public function send(SmsLog $smsLog): BaseResult;
}
