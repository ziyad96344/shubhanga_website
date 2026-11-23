<?php

namespace App\Http\Controllers;

use App\Support\Cart;
use App\Support\Catalog;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show()
    {
        return view('cart.show', ['cart' => Cart::summary()]);
    }

    public function summary()
    {
        return response()->json(Cart::summary());
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required', 'string'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:'.Cart::MAX_QTY],
        ]);

        abort_unless(Catalog::find($data['slug']), 404);
        Cart::add($data['slug'], (int) ($data['qty'] ?? 1));

        return $this->respond($request);
    }

    public function update(Request $request, string $slug)
    {
        $data = $request->validate(['qty' => ['required', 'integer', 'min:0', 'max:'.Cart::MAX_QTY]]);
        Cart::set($slug, (int) $data['qty']);

        return $this->respond($request);
    }

    public function remove(Request $request, string $slug)
    {
        Cart::set($slug, 0);

        return $this->respond($request);
    }

    /** JSON for the drawer; a redirect for the no-JS cart page forms. */
    private function respond(Request $request)
    {
        return $request->expectsJson()
            ? response()->json(Cart::summary())
            : redirect()->route('cart.show');
    }
}
