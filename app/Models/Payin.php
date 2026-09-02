<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payin extends Model
{
    use CrudTrait;
    protected $fillable = [
        'merchant_id',
        'transaction_id',
        'amount',
        'status',
        'request_payload',
        'response_payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_payload' => 'array',
        'response_payload' => 'array',
    ];


        protected static function booted()
    {
        static::creating(function ($payin) {
            if (empty($payin->transaction_id)) {
                $payin->transaction_id =
                    'PAYIN-' . strtoupper(Str::random(12));
            }
        });
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
