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
        $cart = $this->userCart((int) $request->user()->id);

        return response()->json($this->cartPayload($cart));
    }

    public function addItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $this->userCart((int) $request->user()->id);
        $item = CartItem::query()->updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $data['product_id']],
            ['quantity' => $data['quantity']]
        );

        return response()->json($item->load('product'), 201);
    }

    public function updateItem(Request $request, CartItem $item): JsonResponse
    {
        $this->authorizeCartItem($request, $item);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item->update(['quantity' => $data['quantity']]);

        return response()->json($item->refresh()->load('product'));
    }

    public function removeItem(Request $request, CartItem $item): JsonResponse
    {
        $this->authorizeCartItem($request, $item);
        $item->delete();

        return response()->json([], 204);
    }

    private function userCart(int $userId): Cart
    {
        return Cart::query()
            ->firstOrCreate(['user_id' => $userId])
            ->load(['items.product']);
    }

    /**
     * @return array<string, mixed>
     */
    private function cartPayload(Cart $cart): array
    {
        $items = $cart->items;
        $total = $items->sum(fn (CartItem $item): float => (float) $item->product->price * $item->quantity);

        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'items' => $items,
            'total' => round($total, 2),
            'created_at' => $cart->created_at,
            'updated_at' => $cart->updated_at,
        ];
    }

    private function authorizeCartItem(Request $request, CartItem $item): void
    {
        $item->loadMissing('cart');

        abort_unless($item->cart->user_id === $request->user()->id, 404);
    }
}
