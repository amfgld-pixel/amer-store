<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['uuid', 'number', 'user_id', 'status', 'payment_status', 'currency', 'subtotal', 'discount_total', 'shipping_total', 'tax_total', 'grand_total', 'customer_name', 'email', 'phone', 'country_code', 'division', 'city', 'postal_code', 'address', 'notes', 'payment_method', 'shipping_method', 'client_ip'];

    protected function casts(): array
    {
        return ['subtotal' => 'decimal:2', 'grand_total' => 'decimal:2'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusEvents(): HasMany
    {
        return $this->hasMany(OrderStatusEvent::class)->orderBy('occurred_at');
    }
}

