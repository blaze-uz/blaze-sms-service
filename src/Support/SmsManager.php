<?php

namespace Blaze\SmsService\Support;

use Blaze\SmsService\Contracts\SmsDriver;
use Blaze\SmsService\Drivers\BlazeCoreDriver;

/**
 * author 1996azizbekeshonaliyev@gmail.com
 * date 19/10/25
 **/
class SmsManager
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function driver(?string $name = null): SmsDriver
    {
        $name = $name ?? $this->config['default'];
        $drivers = $this->config['drivers'] ?? [];

        return match ($name) {
            'blaze_core' => new BlazeCoreDriver($drivers['blaze_core']),
            default => throw new \InvalidArgumentException("SMS driver [$name] topilmadi"),
        };
    }
}
