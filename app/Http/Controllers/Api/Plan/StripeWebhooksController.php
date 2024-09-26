<?php

namespace App\Http\Controllers\Api\Plan;

use App\Http\Controllers\Controller;
use App\Services\Plan\StripePaymentGateway;
use App\Services\Plan\SubscriptionService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Illuminate\Support\Facades\Log;

#[Group("Webhooks", "Endpoint relacionado a webhooks")]
class StripeWebhooksController extends Controller
{
    private SubscriptionService $subscriptionService;

    public function __construct(StripePaymentGateway $stripeService, SubscriptionService $subscriptionService)
    {
        $this->stripeService = $stripeService;
        $this->subscriptionService = $subscriptionService;
    }

    public function handle(Request $request)
    {
        Log::info('Recebendo webhook do Stripe.');

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            Log::info("Evento de webhook recebido: {$event->type}");
        } catch (\UnexpectedValueException $e) {
            Log::error('Payload inválido recebido no webhook do Stripe.');
            abort(400, 'Invalid payload');
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Assinatura inválida no webhook do Stripe.');
            abort(400, 'Invalid signature');
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                Log::info("Processando evento payment_intent.succeeded para PaymentIntent ID: {$paymentIntent->id}");
                $this->subscriptionService->handleSuccessfulPayment($paymentIntent);
                break;
            default:
                Log::info("Evento não tratado: {$event->type}");
        }

        return response()->noContent();
    }

}
