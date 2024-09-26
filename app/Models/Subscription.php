<?php

namespace App\Models;

use App\Models\Views\ProfileAvailableView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'user_profile_id',
        'product_id',
        'price_id',
        'currency',
        'unit_amount',
        'status',
        'current_period_start',
        'current_period_end',
        'payment_method'
    ];

    public function scopeActive(Builder $query)
    {
        $query->where('status', 1);
    }

    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class, 'price_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(ProfileAvailableView::class, 'user_profile_id', 'id');
    }

    public function amount(): null|string
    {
        return $this->unit_amount ? number_format($this->unit_amount / 100, 2) : null;
    }
}
