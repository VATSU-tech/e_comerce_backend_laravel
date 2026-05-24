<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $request->user()->id]);

        return response()->json($cart->load('items'));
    }

    public function addItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = Cart::query()->firstOrCreate(['user_id' => $request->user()->id]);
        $item = CartItem::query()->updateOrCreate(
            ['cart_id' => $cart->id, 'product_variant_id' => $data['product_variant_id']],
            ['quantity' => $data['quantity']]
        );

        return response()->json($item, 201);
    }
}
