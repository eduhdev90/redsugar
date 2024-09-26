<?php

namespace App\Services\Plan\Dto;

final readonly class PaymentMethodResponseDTO
{
    public function __construct(
        public string $externalId,
        public string $type,
        public ?string $customer,
    ) {
    }
}
