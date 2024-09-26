<?php

namespace App\Services\Plan\Dto;

final readonly class SubscriptionDataDTO
{
    public function __construct(
        public string $customer,
        public string $priceId,
        public string $defaultPaymentMethod
    ) {
    }
}
