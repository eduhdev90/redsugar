<?php

namespace App\Services\Plan\Dto;

final readonly class SubscriptionResponseDTO
{
    public function __construct(
        public string $externalId,
        public string $status,
        public ?string $statusPayment = null,
        public ?string $clientSecret = null,
        public ?string $currentPeriodStart = null,
        public ?string $currentPeriodEnd = null,
    ) {
    }
}
