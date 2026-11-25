<?php

namespace App\Support;

/**
 * Session cart. Stores only slug => quantity; names and prices always come
 * from the catalogue, so a visitor can never change what they pay.
 */
class Cart
{
    public const MAX_QTY = 50;

    private const KEY = 'cart';

    public static function raw(): array
    {
        return session(self::KEY, []);
    }

    public static function add(string $slug, int $qty = 1): void
    {
        $cart = static::raw();
        $cart[$slug] = min(self::MAX_QTY, ($cart[$slug] ?? 0) + max(1, $qty));
        session([self::KEY => $cart]);
    }

    public static function set(string $slug, int $qty): void
    {
        $cart = static::raw();
        if ($qty <= 0) {
            unset($cart[$slug]);
        } else {
            $cart[$slug] = min(self::MAX_QTY, $qty);
        }
        session([self::KEY => $cart]);
    }

    public static function clear(): void
    {
        session()->forget(self::KEY);
    }

    public static function freeFrom(): int
    {
        return (int) config('site.shipping.free_from', 499);
    }

    /** Shipping is free on prepaid orders from the threshold; cash on delivery always pays the fee. */
    public static function shipping(int $subtotal, string $method = 'online'): int
    {
        if ($subtotal === 0) {
            return 0;
        }

        return $method !== 'cod' && $subtotal >= static::freeFrom() ? 0 : (int) config('site.shipping.fee', 49);
    }

    /**
     * Everything the drawer, cart page and checkout need.
     */
    public static function summary(): array
    {
        $books = Catalog::books()->keyBy('slug');
        $items = [];

        foreach (static::raw() as $slug => $qty) {
            $book = $books->get($slug);
            if (! $book || $book['stock'] <= 0) {
                continue; // book removed from the catalogue or sold out
            }
            $qty = min($qty, $book['stock']);
            $items[] = [
                'id' => $slug,
                'name' => $book['name'],
                'meta' => $book['class_label'].', '.$book['board_label'],
                'image' => $book['image'],
                'url' => route('books.show', $slug),
                'price' => $book['price'],
                'qty' => $qty,
                'line_total' => $book['price'] * $qty,
            ];
        }

        $subtotal = array_sum(array_column($items, 'line_total'));

        // A coupon applied at checkout stays in the session and is
        // re-checked here, so an expired or used-up code drops off.
        [$coupon] = \App\Models\Coupon::check(session('coupon'), $subtotal);
        $discount = $coupon ? $coupon->discountFor($subtotal) : 0;

        return [
            'items' => $items,
            'count' => array_sum(array_column($items, 'qty')),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon' => $coupon ? ['code' => $coupon->code, 'label' => $coupon->label()] : null,
            'shipping' => static::shipping($subtotal),
            'free_from' => static::freeFrom(),
        ];
    }
}
