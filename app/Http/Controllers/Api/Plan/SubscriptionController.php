<?php

namespace App\Http\Controllers\Api\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSubscriptionFreeActivationRequest;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Requests\CreatePaymentIntentRequest;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\PaymentIntentResource;
use App\Models\Subscription;
use App\Services\Plan\SubscriptionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[Group("Assinaturas", "Endpoint relacionado a assinaturas")]
class SubscriptionController extends Controller
{
    private SubscriptionService $service;

    public function __construct(SubscriptionService $service)
    {
        $this->service = $service;
    }

    /**
     * Setar assinatura paga
     */
    #[ResponseFromApiResource(SubscriptionResource::class, Subscription::class, 201)]
    public function store(CreateSubscriptionRequest $request): SubscriptionResource|JsonResponse
    {
        $subscription = $this->service->storeByPrice(
            auth()->user()->profile->id,
            $request->price_id,
            $request->payment_method_id
        );

        return new SubscriptionResource($subscription);
    }

    /**
     * Setar assinatura gratuita
     */
    #[ResponseFromApiResource(SubscriptionResource::class, Subscription::class, 201)]
    public function storeFree(): SubscriptionResource
    {
        return $this->service->storeFreePlan(auth()->user()->profile->id);
    }

    /**
     * Setar assinatura gratuita para usuário aprovado
     */
    #[ResponseFromApiResource(SubscriptionResource::class, Subscription::class, 201)]
    public function storeFreeOnActivation(CreateSubscriptionFreeActivationRequest $request): SubscriptionResource
    {
        return $this->service->storeFreePlan($request->user_profile_id);
    }

    /**
     * Assinatura ativa do usuário logado
     */
    #[ResponseFromApiResource(SubscriptionResource::class, Subscription::class)]
    public function getActiveByUserLogged(): SubscriptionResource
    {
        $userProfileId = auth()->user()->profile->id;
        $subscription = $this->service->getActiveByUserId($userProfileId);

        if (!$subscription) {
            throw new ModelNotFoundException("Nenhum plano ativo", 404);
        }

        return new SubscriptionResource($subscription);
    }

    /**
     * Criar um PaymentIntent e retornar o clientSecret
     */
    #[ResponseFromApiResource(PaymentIntentResource::class)]
    public function createPaymentIntent(CreatePaymentIntentRequest $request): PaymentIntentResource
    {
        $paymentIntent = $this->service->createPaymentIntent($request->amount, $request->currency);
        return new PaymentIntentResource($paymentIntent);
    }

    /**
     * Confirmar o pagamento de uma assinatura
     */
    public function confirmPayment(string $paymentIntentId)
    {
        $this->service->confirmPayment($paymentIntentId);
        return response()->noContent();
    }
}
