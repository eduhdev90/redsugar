<?php

namespace App\Services\Plan;

use App\Exceptions\SubscriptionLogicException;
use App\Http\Resources\SubscriptionResource;
use App\Models\Price;
use App\Models\Subscription;
use App\Models\UserProfile;
use App\Repositories\Plan\PriceRepositoryInterface;
use App\Repositories\Plan\ProductRepositoryInterface;
use App\Repositories\Plan\SubscriptionActiveViewRepositoryInterface;
use App\Repositories\Plan\SubscriptionRepositoryInterface;
use App\Repositories\User\UserProfileRepositoryInterface;
use App\Services\Plan\Dto\CustomerDataDTO;
use App\Services\Plan\Dto\CustomerResponseDTO;
use App\Services\Plan\Dto\PaymentMethodResponseDTO;
use App\Services\Plan\Dto\SubscriptionDataDTO;
use App\Services\Plan\Dto\SubscriptionResponseDTO;
use App\Services\Plan\Factories\PaymentGatewayFactory;
use App\ValueObjects\Plan\Benefits;
use App\ValueObjects\Plan\SubscriptionStatus;
use App\ValueObjects\Profile;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Stripe\PaymentIntent;

class SubscriptionService
{
    private PaymentGatewayInterface $paymentService;

    public function __construct(
        private SubscriptionRepositoryInterface $repository,
        private PriceRepositoryInterface $priceRepository,
        private ProductRepositoryInterface $productRepository,
        private UserProfileRepositoryInterface $userProfileRepository,
        private SubscriptionActiveViewRepositoryInterface $subActiveRepository,
        private ProductService $productService
    ) {
        $this->paymentService = PaymentGatewayFactory::create('stripe');
    }

    public function getActiveAll(): ResourceCollection
    {
        return SubscriptionResource::collection($this->repository->getActiveAll());
    }

    public function getActiveByUserId(int $userProfileId): ?SubscriptionResource
    {
        $subscriptionActive = $this->repository->getActiveByUserProfileId($userProfileId);
        return empty($subscriptionActive) ? null : new SubscriptionResource($subscriptionActive);
    }

    public function getByExternalId(string $externalId): ?SubscriptionResource
    {
        $sub = $this->repository->getByExternalId($externalId);
        return empty($sub) ? null : new SubscriptionResource($sub);
    }

    public function storeByPrice(int $userProfileId, int $priceId, string $paymentMethodId): SubscriptionResource|JsonResponse
    {
        try {
            $price = $this->validatePrice($priceId);
            $profileUser = $this->validateUserProfile($userProfileId, $price);
            $subscriptionActive = $this->validateSubscriptionActive($userProfileId, $priceId);

            $paymentMethodResponse = $this->paymentService->getPaymentMethodById($paymentMethodId);
            $this->validatePaymentMethod($paymentMethodResponse, $profileUser);

            $profileUser = $this->handleCustomerCreation($profileUser);
            $paymentMethod = $this->handleAttachPaymentMethod($profileUser, $paymentMethodResponse, $paymentMethodId);
            $subscriptionResponse = $this->createSubscription($profileUser, $price, $paymentMethod->externalId);
            $statusSubscription = $this->handleSubscriptionStatus($subscriptionActive, $subscriptionResponse);

            $subscription = $this->createSubscriptionRecord($userProfileId, $price, $statusSubscription, $subscriptionResponse);

            return $this->handleSubscriptionResponse($subscription, $subscriptionResponse);
        } catch (\Throwable $th) {
            throw new HttpException(400, $th->getMessage());
        }
    }

    public function cancelSubscription(SubscriptionResource $subscriptionActive): void
    {
        if (!empty($subscriptionActive)) {
            if (!empty($subscriptionActive->external_id)) {
                $this->paymentService->cancelSubscription($subscriptionActive->external_id);
            }
            $this->repository->cancel($subscriptionActive->id);
        }
    }

    public function storeFreePlan(int $userProfileId): SubscriptionResource
    {
        $profileUser = $this->userProfileRepository->getById($userProfileId);
        $product = $this->productService->getFreeProductByProfile($profileUser->profile);

        $subscriptionActive = $this->repository->getActiveByUserProfileId($userProfileId);

        if (!empty($subscriptionActive)) {
            if ($subscriptionActive->product_id === $product->id) {
                return new SubscriptionResource($subscriptionActive);
            }

            $this->repository->cancel($subscriptionActive->id);
        }

        $dateNow = CarbonImmutable::now();

        $subscription = $this->repository->create([
            'user_profile_id' => $userProfileId,
            'product_id' => $product->id,
            'unit_amount' => 0,
            'status' => SubscriptionStatus::ACTIVE->value,
            'current_period_start' => $dateNow,
            'current_period_end' => $dateNow->addDays(30),
        ]);

        return new SubscriptionResource($subscription);
    }

    public function update(int $id, array $data): SubscriptionResource
    {
        $response = $this->repository->update($id, $data);

        return new SubscriptionResource($response);
    }

    public function canAddFavorite(int $userProfileId): bool
    {
        $sub = $this->subActiveRepository->getByBenefitName($userProfileId, Benefits::FAVORITES_LIMIT->value);

        return empty($sub) || $sub->benefit_amount > $sub->total_favorites;
    }

    public function canVisitProfile(int $userProfileId): bool
    {
        $sub = $this->subActiveRepository->getByBenefitName($userProfileId, Benefits::VISITS_LIMIT->value);

        return empty($sub) || $sub->benefit_amount > $sub->total_visits;
    }

    private function validatePrice(int $priceId): Price
    {
        $price = $this->priceRepository->getActiveById($priceId);
        if (empty($price)) {
            throw new ModelNotFoundException("Preço não encontrado", 404);
        }
        return $price;
    }

    private function validateUserProfile(int $userProfileId, Price $price): UserProfile
    {
        $profileUser = $this->userProfileRepository->getById($userProfileId);
        if (!$this->canAddPlan($profileUser->profile, $price->product->profile)) {
            throw new SubscriptionLogicException("Plano inválido para o perfil do usuário", 422);
        }
        return $profileUser;
    }

    private function canAddPlan(int|string $userProfileType, int|string $profile): bool
    {
        return Profile::tryFrom($profile) === Profile::tryFrom($userProfileType);
    }

    private function validateSubscriptionActive(int $userProfileId, int $priceId): ?SubscriptionResource
    {
        $subscriptionActive = $this->getActiveByUserId($userProfileId);
        if (!empty($subscriptionActive) && $subscriptionActive->price_id === $priceId) {
            throw new SubscriptionLogicException("Usuário já possui o plano selecionado ativo", 422);
        }

        return $subscriptionActive;
    }

    private function validatePaymentMethod(PaymentMethodResponseDTO $paymentMethodResponse, UserProfile $profileUser): void
    {
        if (empty($paymentMethodResponse)) {
            throw new SubscriptionLogicException("Meio de pagamento não encontrado", 422);
        }

        if ($this->isPaymentMethodInvalidForUser($paymentMethodResponse, $profileUser)) {
            throw new SubscriptionLogicException("Meio de pagamento inválido para o usuário", 422);
        }
    }

    private function isPaymentMethodInvalidForUser(PaymentMethodResponseDTO $paymentMethodResponse, UserProfile $profileUser): bool
    {
        return !empty($paymentMethodResponse->customer) &&
            !empty($profileUser->external_id) &&
            $paymentMethodResponse->customer !== $profileUser->external_id;
    }

    private function handleCustomerCreation(UserProfile $profileUser): UserProfile
    {
        if (empty($profileUser->external_id)) {
            $customerResponse = $this->createCustomer($profileUser);
            $profileUser->external_id = $customerResponse->externalId;
            $profileUser->save();
        }

        return $profileUser;
    }

    private function createCustomer(UserProfile $profileUser): CustomerResponseDTO
    {
        return $this->paymentService->createCustomer(new CustomerDataDTO(
            $profileUser->id,
            $profileUser->user->name,
            $profileUser->user->email
        ));
    }

    private function handleAttachPaymentMethod(UserProfile $profileUser, PaymentMethodResponseDTO $paymentMethodResponse, string $paymentMethodId): PaymentMethodResponseDTO
    {
        if (empty($paymentMethodResponse->customer)) {
            return $this->paymentService->attachPaymentMethod($paymentMethodId, $profileUser->external_id);
        }
        return $paymentMethodResponse;
    }

    private function createSubscription(UserProfile $profileUser, Price $price, string $paymentMethodId): SubscriptionResponseDTO
    {
        return $this->paymentService->createSubscription(new SubscriptionDataDTO(
            $profileUser->external_id,
            $price->external_id,
            $paymentMethodId
        ));
    }

    private function handleSubscriptionStatus(SubscriptionResource $subscriptionActive, SubscriptionResponseDTO $subscriptionResponse): int
    {
        $statusSubscription = SubscriptionStatus::PENDING->value;
        if ($subscriptionResponse->statusPayment === 'succeeded') {
            $statusSubscription = SubscriptionStatus::ACTIVE->value;

            $this->cancelSubscription($subscriptionActive);
        }
        return $statusSubscription;
    }

    private function createSubscriptionRecord(int $userProfileId, Price $price, int $statusSubscription, SubscriptionResponseDTO $subscriptionResponse): Subscription
    {
        return $this->repository->create([
            'external_id' => $subscriptionResponse->externalId,
            'user_profile_id' => $userProfileId,
            'product_id' => $price->product->id,
            'price_id' => $price->id,
            'currency' => $price->currency,
            'unit_amount' => $price->unit_amount,
            'status' => $statusSubscription,
            'current_period_start' => Carbon::createFromTimestamp($subscriptionResponse->currentPeriodStart),
            'current_period_end' => Carbon::createFromTimestamp($subscriptionResponse->currentPeriodEnd),
        ]);
    }

    private function handleSubscriptionResponse(Subscription $subscription, SubscriptionResponseDTO $subscriptionResponse): SubscriptionResource|JsonResource
    {
        if ($subscriptionResponse->statusPayment !== 'succeeded') {
            return new SubscriptionResource($subscription);
        }
        return new SubscriptionResource($subscription);
    }

    public function createPaymentIntent(int $amount, string $currency): PaymentIntent
    {
        return $this->paymentService->createPaymentIntent([
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }

    public function confirmPayment(string $paymentIntentId): void
    {
        \Log::info("Iniciando confirmação de pagamento para PaymentIntent ID: {$paymentIntentId}");

        // Obtenha o PaymentIntent do Stripe usando o paymentService
        $paymentIntent = $this->paymentService->retrievePaymentIntent($paymentIntentId);

        // Verifique o status do pagamento
        if ($paymentIntent->status === 'succeeded') {
            \Log::info("Pagamento sucedido para PaymentIntent ID: {$paymentIntentId}");

            // Obtendo o subscriptionId dos metadados do PaymentIntent
            $subscriptionId = $paymentIntent->metadata['subscription_id'] ?? null;
            if (!$subscriptionId) {
                \Log::error("subscription_id não encontrado nos metadados do PaymentIntent ID: {$paymentIntentId}");
                return;
            }

            // Recuperar a assinatura pelo ID externo
            $subscription = $this->repository->getByExternalId($subscriptionId);

            if ($subscription) {
                \Log::info("Atualizando assinatura com ID: {$subscription->id} para status ativo");

                $subscription->status = SubscriptionStatus::ACTIVE->value;
                $subscription->save();

                \Log::info("Assinatura com ID: {$subscription->id} atualizada com sucesso.");
            } else {
                \Log::error("Assinatura não encontrada para subscription_id: {$subscriptionId}");
            }
        } else {
            \Log::warning("Status do PaymentIntent ID: {$paymentIntentId} não é 'succeeded'. Status atual: {$paymentIntent->status}");
        }
    }

    public function handleSuccessfulPayment($paymentIntent): void
    {
        \Log::info("Processando pagamento bem-sucedido para PaymentIntent ID: {$paymentIntent->id}");

        // Verifique o status do PaymentIntent
        if ($paymentIntent->status === 'succeeded') {
            \Log::info("Pagamento sucedido para PaymentIntent ID: {$paymentIntent->id}");

            // Obtendo o subscriptionId dos metadados do PaymentIntent
            $subscriptionId = $paymentIntent->metadata['subscription_id'] ?? null;
            if (!$subscriptionId) {
                \Log::error("subscription_id não encontrado nos metadados do PaymentIntent ID: {$paymentIntent->id}");
                return;
            }

            // Recuperar a assinatura pelo ID externo
            $subscription = $this->repository->getByExternalId($subscriptionId);

            if ($subscription) {
                \Log::info("Atualizando assinatura com ID: {$subscription->id} para status ativo");

                $subscription->status = SubscriptionStatus::ACTIVE->value;
                $subscription->save();

                \Log::info("Assinatura com ID: {$subscription->id} atualizada com sucesso.");
            } else {
                \Log::error("Assinatura não encontrada para subscription_id: {$subscriptionId}");
            }
        } else {
            \Log::warning("Status do PaymentIntent ID: {$paymentIntent->id} não é 'succeeded'. Status atual: {$paymentIntent->status}");
        }
    }


}
