<?php

namespace Blaze\SmsService\Support\HelperClasses;


/**
 * @date 10/21/2025 3:59 PM
 */
class Phone
{
    private string|array|null $formattedNumber;

    public function __construct(private ?string $rawNumber = null) {
        $this->formattedNumber = $this->formatNumber();
    }

    private function cleanNumber(): array|string|null
    {
        return preg_replace('/\D/', '', $this->rawNumber);
    }

    private function formatNumber(): array|string|null
    {
        $cleaned = $this->cleanNumber();

        return strlen($cleaned) == 9 ? "998$cleaned" : $cleaned;
    }

    public function __toString() {
        return $this->formattedNumber;
    }
}
