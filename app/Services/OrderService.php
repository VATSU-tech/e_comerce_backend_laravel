<?php

namespace App\Services;

use App\Models\OrderStatus;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    public function createOrder(int $userId, float $total): array
    {
        $pendingStatus = OrderStatus::query()->where('name', 'PENDING')->firstOrFail();
        $order = $this->orderRepository->create([
            'user_id' => $userId,
            'order_status_id' => $pendingStatus->id,
            'total' => $total,
        ]);

        return $order->toArray();
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
        $cancelledStatus = OrderStatus::query()->where('name', 'CANCELLED')->firstOrFail();
        $order = $this->orderRepository->findForUser($orderId, $userId);

        return $this->orderRepository->updateStatus($order, $cancelledStatus->id)->toArray();
    }
}
