<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'user_profile_id' => $this->user_profile_id,
            'product_id' => $this->product_id,
            'price_id' => $this->price_id,
            'currency' => $this->currency,
            'amount' => $this->amount(),
            'current_period_start' => $this->current_period_start,
            'current_period_end' => $this->current_period_end,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'product' => new ProductResource($this->product),
            'item' => new ProductPriceResource($this->price),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
