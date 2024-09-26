<?php

namespace App\Models;

use App\ValueObjects\Plan\PeriodPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function scopeActive(Builder $query)
    {
        $query->where('status', 1);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function amount(): null|string
    {
        return $this->unit_amount ? number_format($this->unit_amount / 100, 2) : null;
    }

    public function period(): string
    {
        return PeriodPrice::tryFrom($this->period)->label();
    }
}
