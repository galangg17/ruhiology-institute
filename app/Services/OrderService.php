<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrderService
{
    public function createOrder(array $customerData, array $cartItems, ?User $user = null): Order
    {
        return DB::transaction(function () use ($customerData, $cartItems, $user) {
            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($cartItems as $item) {
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
                if (!$product) {
                    throw new Exception("Buku tidak ditemukan.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Stok untuk buku '{$product->title}' tidak mencukupi.");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $itemsToCreate[] = [
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'product_model' => $product,
                ];
            }

            $shippingFee = $customerData['shipping_fee'] ?? 15000;
            $totalAmount = $subtotal + $shippingFee;

            $orderNumber = 'ORD-' . strtoupper(Str::random(8));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user?->id,
                'customer_name' => $customerData['customer_name'],
                'customer_email' => $customerData['customer_email'],
                'customer_phone' => $customerData['customer_phone'],
                'shipping_address' => $customerData['shipping_address'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'order_status' => 'new',
                'notes' => $customerData['notes'] ?? null,
            ]);

            foreach ($itemsToCreate as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'product_title' => $itemData['product_title'],
                    'price' => $itemData['price'],
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemData['subtotal'],
                ]);

                // Deduct stock
                $itemData['product_model']->decrement('stock', $itemData['quantity']);
            }

            AuditLogService::log(
                action: 'create_order',
                module: 'Store',
                recordType: 'Order',
                recordId: (string) $order->id,
                changes: [
                    'order_number' => $orderNumber,
                    'total_amount' => $totalAmount,
                ]
            );

            return $order;
        });
    }
}
