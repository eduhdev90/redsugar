<?php

namespace App\Services\Plan;

use App\Services\Plan\Dto\CustomerDataDTO;
use App\Services\Plan\Dto\CustomerResponseDTO;
use App\Services\Plan\Dto\PaymentMethodResponseDTO;
use App\Services\Plan\Dto\SubscriptionDataDTO;
use App\Services\Plan\Dto\SubscriptionResponseDTO;
use Stripe\PaymentIntent;

interface PaymentGatewayInterface
{
    public function createCustomer(CustomerDataDTO $customerDataDto): CustomerResponseDTO;
    public function createSubscription(SubscriptionDataDTO $subscriptionDataDTO): SubscriptionResponseDTO;
    public function getSubscriptionById(string $subscriptionId): ?SubscriptionResponseDTO;
    public function cancelSubscription(string $subscriptionId): SubscriptionResponseDTO;
    public function getPaymentMethodById(string $paymentMethodId): ?PaymentMethodResponseDTO;
    public function attachPaymentMethod(string $paymentMethodId, string $customerId): PaymentMethodResponseDTO;
    public function createPaymentIntent(array $data): PaymentIntent;
}
