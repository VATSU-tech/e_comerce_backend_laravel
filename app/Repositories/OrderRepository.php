<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::query()->create($data);
    }

    public function userOrders(int $userId): Collection
    {
        return Order::query()->with(['status', 'items'])->where('user_id', $userId)->latest()->get();
    }

    public function findForUser(int $orderId, int $userId): Order
    {
        return Order::query()->with(['status', 'items'])->where('user_id', $userId)->findOrFail($orderId);
    }

    public function updateStatus(Order $order, int $statusId): Order
    {
        $order->update(['order_status_id' => $statusId]);

        return $order->refresh();
    }
}
