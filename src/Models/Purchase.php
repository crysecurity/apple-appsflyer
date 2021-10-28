<?php

namespace Cr4sec\AppleAppsFlyer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $transaction_id
 * @property int $apple_receipt_id
 * @property bool $is_trial
 * @property bool $sent_to_af
 * @property string|null $product_id
 * @property Carbon|null $purchase_date
 * @property Carbon|null $expires_date
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Receipt $receipt
 */
class Purchase extends Model
{
    use HasFactory;

    protected $table = 'apple_purchases';

    protected $touches = ['receipt'];

    protected $attributes = [
        'sent_to_af' => false
    ];

    protected $dates = [
        'purchase_date',
        'expires_date',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'transaction_id',
        'is_trial',
        'purchase_date',
        'expires_date',
        'product_id'
    ];

    protected $casts = [
        'is_trial' => 'bool',
        'sent_to_af' => 'bool',
    ];

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(config('apple-appsflyer.models.receipt'), 'apple_receipt_id');
    }

    public function scopeUnsent(Builder $builder): Builder
    {
        return $builder->whereSentToAf(false);
    }
}
