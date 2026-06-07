<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\OrderStatus;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    public function createOrderFromCart(int $userId): array
    {
        return DB::transaction(function () use ($userId): array {
            $cart = Cart::query()
                ->with(['items.product'])
                ->firstOrCreate(['user_id' => $userId]);

            abort_if($cart->items->isEmpty(), 422, 'The cart is empty.');

            $total = $cart->items->sum(fn (CartItem $item): float => (float) $item->product->price * $item->quantity);
            $pendingStatus = OrderStatus::query()->where('slug', 'pending')->firstOrFail();

            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'order_status_id' => $pendingStatus->id,
                'total' => round($total, 2),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);
            }

            $cart->items()->delete();

            return $order->load(['status', 'items.product'])->toArray();
        });
    }

    public function listOrders(int $userId): Collection
    {
        return $this->orderRepository->userOrders($userId);
    }

    public function getOrder(int $orderId, int $userId): array
    {
        return $this->orderRepository->findForUser($orderId, $userId)->toArray();
    }

    public function cancelOrder(int $orderId, int $userId): array
    {
        $cancelledStatus = OrderStatus::query()->where('slug', 'cancelled')->firstOrFail();
        $order = $this->orderRepository->findForUser($orderId, $userId);

        return $this->orderRepository->updateStatus($order, $cancelledStatus->id)->toArray();
    }
}
