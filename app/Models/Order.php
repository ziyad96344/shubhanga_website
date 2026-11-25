<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'number', 'name', 'email', 'phone',
        'address_line1', 'address_line2', 'city', 'state', 'pincode', 'notes',
        'subtotal', 'shipping', 'discount', 'coupon_code', 'total',
        'payment_method', 'payment_status', 'status',
        'tracking_number', 'admin_notes', 'shipped_at', 'delivered_at',
        'razorpay_order_id', 'razorpay_payment_id', 'ip',
    ];

    protected function casts(): array
    {
        return [
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public const STATUS_LABELS = [
        'awaiting_payment' => 'Awaiting payment',
        'placed' => 'Order placed',
        'packed' => 'Packed',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    /** Short, readable order number, e.g. SP-26-7F3K9Q */
    public static function newNumber(): string
    {
        do {
            $number = 'SP-'.now()->format('y').'-'.strtoupper(substr(str_replace(['0', 'O', '1', 'I'], '', bin2hex(random_bytes(6))), 0, 6));
        } while (static::where('number', $number)->exists());

        return $number;
    }
}
