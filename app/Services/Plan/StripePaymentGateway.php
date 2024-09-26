<?php

namespace App\Services\Plan;

use App\Jobs\ProcessPaymentSucceeded;
use App\Services\Plan\Dto\CustomerDataDTO;
use App\Services\Plan\Dto\CustomerResponseDTO;
use App\Services\Plan\Dto\PaymentMethodResponseDTO;
use App\Services\Plan\Dto\SubscriptionDataDTO;
use App\Services\Plan\Dto\SubscriptionResponseDTO;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class StripePaymentGateway implements PaymentGatewayInterface
{

    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient([
            'api_key' => env('STRIPE_SECRET_KEY'),
            'stripe_version' => '2024-04-10'
        ]);
    }

    public function createCustomer(CustomerDataDTO $customerDataDto): CustomerResponseDTO
    {
        $response = $this->stripe->customers->create([
            'name' => $customerDataDto->name,
            'email' => $customerDataDto->email
        ]);

        return new CustomerResponseDTO($customerDataDto->id, $response->id);
    }

    public function getSubscriptionById(string $subscriptionId): ?SubscriptionResponseDTO
    {
        $response = $this->stripe->subscriptions->retrieve($subscriptionId);

        return empty($response) ? null : new SubscriptionResponseDTO(
            $response->id,
            $response->status,
            $response->latest_invoice->payment_intent->status,
            $response->latest_invoice->payment_intent->client_secret,
            $response->current_period_start,
            $response->current_period_end
        );
    }

    public function createSubscription(SubscriptionDataDTO $subscriptionDataDTO): SubscriptionResponseDTO
    {
        $response = $this->stripe->subscriptions->create([
            'customer' => $subscriptionDataDTO->customer,
            'items' => [
                ['price' => $subscriptionDataDTO->priceId]
            ],
            'default_payment_method' => $subscriptionDataDTO->defaultPaymentMethod,
            'expand' => ['latest_invoice.payment_intent']
        ]);

        return new SubscriptionResponseDTO(
            $response->id,
            $response->status,
            $response->latest_invoice->payment_intent->status,
            $response->latest_invoice->payment_intent->client_secret,
            $response->current_period_start,
            $response->current_period_end
        );
    }

    public function cancelSubscription(string $subscriptionId): SubscriptionResponseDTO
    {
        $response = $this->stripe->subscriptions->cancel($subscriptionId);

        return new SubscriptionResponseDTO(
            $response->id,
            $response->status
        );
    }

    public function getPaymentMethodById(string $paymentMethodId): ?PaymentMethodResponseDTO
    {
        try {
            $response = $this->stripe->paymentMethods->retrieve($paymentMethodId);

            return new PaymentMethodResponseDTO(
                $response->id,
                $response->type,
                $response->customer
            );
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return null;
        }
    }

    public function attachPaymentMethod(string $paymentMethodId, string $customerId): PaymentMethodResponseDTO
    {
        $response = $this->stripe->paymentMethods->attach(
            $paymentMethodId,
            ['customer' => $customerId]
        );

        return new PaymentMethodResponseDTO(
            $response->id,
            $response->type,
            $response->customer
        );
    }

    public function webhooks()
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            throw new HttpException(400, 'Error parsing payload: ' . $e->getMessage());
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            throw new HttpException(400, 'Error verifying webhook signature: ' . $e->getMessage());
        }

        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                if (!empty($invoice->subscription)) {
                    ProcessPaymentSucceeded::dispatch($invoice);
                }
            default:
                // echo 'Received unknown event type ' . $event->type;
        }

        return response()->json([], 200);
    }

    public function createPaymentIntent(array $data): PaymentIntent
    {
        // Cria um PaymentIntent usando a API da Stripe
        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'payment_method_types' => ['card'],
        ]);

        // Log do client_secret para depuração
        \Log::info('PaymentIntent created', ['client_secret' => $paymentIntent->client_secret]);

        return $paymentIntent;
    }

    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        \Log::info("Recuperando PaymentIntent com ID: {$paymentIntentId}");

        try {
            $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            \Log::info("PaymentIntent ID: {$paymentIntentId} recuperado com sucesso.");
            return $paymentIntent;
        } catch (\Exception $e) {
            \Log::error("Erro ao recuperar PaymentIntent ID: {$paymentIntentId}. Erro: {$e->getMessage()}");
            throw new HttpException(400, 'Erro ao recuperar PaymentIntent.');
        }
    }




}
