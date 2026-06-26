<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'order_date',
        'delivery_date',
        'recipient_name',
        'recipient_phone',
        'delivery_address',
        'greeting_message',
        'customer_note',
        'operator_note',
        'subtotal',
        'delivery_fee',
        'total',
        'payment_status',
        'order_status',
        'cancelled_at',
        'completed_at',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the customer who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment associated with the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the status history of the order.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
