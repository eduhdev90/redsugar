<?php

namespace App\Services\Plan\Factories;

use App\Services\Plan\PaymentGatewayInterface;
use App\Services\Plan\StripePaymentGateway;
use Exception;

class PaymentGatewayFactory
{
    public static function create(string $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            'stripe' => new StripePaymentGateway(),
            default => throw new \Exception("Gateway de pagamento inválido!", 400),
        };
    }
}
