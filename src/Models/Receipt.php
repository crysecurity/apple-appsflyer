<?php

namespace Cr4sec\AppleAppsFlyer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $original_transaction_id
 * @property string $currency
 * @property float $price
 * @property string|null $idfa
 * @property string $appsflyer_id
 * @property int $user_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * @property-read Collection|Purchase[] $purchases
 */
class Receipt extends Model
{
    use HasFactory;

    protected $table = 'apple_receipts';

    protected $fillable = [
        'currency'
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'apple_receipt_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('apple-appsflyer.models.user'));
    }
}
