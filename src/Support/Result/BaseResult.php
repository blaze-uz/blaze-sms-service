<?php

namespace Blaze\SmsService\Support\Result;


/**
 * @date 10/21/2025 5:20 PM
 */
class BaseResult
{
    public function __construct(
        public bool $success,
        public ?string $message = null,
        public ?array $data = null
    ) {}
}
