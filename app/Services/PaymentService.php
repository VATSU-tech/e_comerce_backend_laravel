<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function initiate(int $orderId, int $paymentMethodId, float $amount): array
    {
        $payment = Payment::query()->create([
            'order_id' => $orderId,
            'payment_method_id' => $paymentMethodId,
            'amount' => $amount,
            'status' => 'pending',
            'transaction_reference' => 'txn_'.uniqid(),
        ]);

        return $payment->toArray();
    }

    public function confirm(string $transactionReference): array
    {
        $payment = Payment::query()->where('transaction_reference', $transactionReference)->firstOrFail();
        $payment->update(['status' => 'paid']);

        return $payment->refresh()->toArray();
    }

    public function webhook(array $payload): array
    {
        if (! isset($payload['transaction_reference'])) {
            return ['ack' => false, 'message' => 'transaction_reference missing'];
        }

        $this->confirm($payload['transaction_reference']);

        return ['ack' => true];
    }
}
