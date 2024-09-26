<?php

namespace App\Services\Plan\Dto;

final readonly class CustomerDataDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public array $metadata = []
    ) {
    }
}
