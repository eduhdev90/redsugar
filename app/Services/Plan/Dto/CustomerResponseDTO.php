<?php

namespace App\Services\Plan\Dto;

final readonly class CustomerResponseDTO
{
    public function __construct(
        public string $id,
        public string $externalId,
    ) {
    }
}
