<?php

namespace App\Jobs;

use App\Services\Plan\StripePaymentGateway;
use App\Services\Plan\SubscriptionService;
use App\Services\ProfileAvailableService;
use App\ValueObjects\Plan\SubscriptionStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPaymentSucceeded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private object $object)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SubscriptionService $subscriptionService, ProfileAvailableService $profileAvailableService, StripePaymentGateway $stripePaymentGateway): void
    {
        $sub = $subscriptionService->getByExternalId($this->object->subscription);
        Log::info('[ProcessPaymentSucceeded] Assinatura {id} encontrada.', ['id' => $this->object->subscription]);

        if (SubscriptionStatus::tryFrom($sub->status) === SubscriptionStatus::ACTIVE) {
            Log::info('[ProcessPaymentSucceeded] Assinatura {id} já está ativa.', ['id' => $this->object->subscription]);
            return;
        }

        if (SubscriptionStatus::tryFrom($sub->status) === SubscriptionStatus::CANCELED) {
            Log::info('[ProcessPaymentSucceeded] Assinatura {id} encontra-se cancelada.', ['id' => $this->object->subscription]);
            return;
        }

        $user = $profileAvailableService->getByExternalId($this->object->customer);

        if (empty($user)) {
            Log::error('[ProcessPaymentSucceeded] Não encontrado cliente {id}.', ['id' => $this->object->customer]);
            return;
        }

        $subActive = $subscriptionService->getActiveByUserId($user->id);

        Log::info('[ProcessPaymentSucceeded] Cancelando assinatura atual {id}', ['id' => $subActive->id]);
        $subscriptionService->cancelSubscription($subActive);

        $subscriptionService->update($sub->id, ['status' => SubscriptionStatus::ACTIVE->value]);
        Log::info('[ProcessPaymentSucceeded] Ativando assinatura {id}', ['id' => $this->object->subscription]);
    }
}
