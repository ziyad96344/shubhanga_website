<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_cart_value', 'max_uses', 'used_count', 'expires_at', 'is_active'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /** Looks up a code and checks it can be used on a cart of this value. Returns [coupon|null, error|null]. */
    public static function check(?string $code, int $subtotal): array
    {
        $code = strtoupper(trim((string) $code));
        if ($code === '') {
            return [null, null];
        }

        $coupon = static::where('code', $code)->first();

        return match (true) {
            ! $coupon || ! $coupon->is_active => [null, 'This coupon code is not valid.'],
            $coupon->expires_at && $coupon->expires_at->isPast() => [null, 'This coupon has expired.'],
            $coupon->max_uses && $coupon->used_count >= $coupon->max_uses => [null, 'This coupon has been fully used.'],
            $coupon->min_cart_value && $subtotal < $coupon->min_cart_value => [null, 'Add books worth ₹'.number_format($coupon->min_cart_value).' or more to use this coupon.'],
            default => [$coupon, null],
        };
    }

    public function discountFor(int $subtotal): int
    {
        $discount = $this->type === 'percent' ? (int) floor($subtotal * $this->value / 100) : (int) $this->value;

        return min($discount, $subtotal);
    }

    public function label(): string
    {
        return $this->type === 'percent' ? $this->value.'% off' : '₹'.number_format($this->value).' off';
    }
}
