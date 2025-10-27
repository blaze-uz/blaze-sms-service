<?php

namespace Blaze\SmsService\Contracts;

/**
 * @date 10/22/2025 11:23 AM
 */
abstract class SmsReceiver
{
    public function __construct(
        public readonly string $type,
        public readonly ?int $id,
        public readonly string $phone,
        public readonly array $data = []
    ) {}

    abstract public static function make(...$args): static;
}
