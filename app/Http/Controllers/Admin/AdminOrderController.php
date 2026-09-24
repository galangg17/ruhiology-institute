<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('items.product');

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }
        if ($request->filled('q')) {
            $query->where('order_number', 'like', '%' . $request->q . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->q . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->q . '%');
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
            'order_status' => ['required', 'in:new,processing,shipped,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $order->update($validated);

        AuditLogService::log(
            action: 'update_order_status',
            module: 'Store',
            recordType: 'Order',
            recordId: (string) $order->id,
            changes: $validated
        );

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
