<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AuditService;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected AuditService $auditService
    ) {}

    public function index(Request $request): View
    {
        $query = Order::with('user');

        if ($search = $request->input('search')) {
            $query->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items', 'payments.transactions']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,awaiting_payment,paid,processing,packed,shipped,delivered,cancelled,returned,refunded',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $newStatus = $request->input('status');
        $oldStatus = $order->status;
        $trackingNumber = $request->input('tracking_number');

        DB::transaction(function () use ($order, $newStatus, $oldStatus, $trackingNumber) {
            $updates = ['status' => $newStatus];

            if ($trackingNumber) {
                $updates['tracking_number'] = $trackingNumber;
            }

            if ($newStatus === 'shipped' && ! $order->shipped_at) {
                $updates['shipped_at'] = now();
            }

            if ($newStatus === 'delivered' && ! $order->delivered_at) {
                $updates['delivered_at'] = now();
            }

            if (in_array($newStatus, ['cancelled', 'refunded']) && ! in_array($oldStatus, ['cancelled', 'refunded'])) {
                $updates['cancelled_at'] = now();

                // If stock was deducted when paid, restore it
                if ($order->payment_status === 'paid') {
                    foreach ($order->items as $item) {
                        $this->inventoryService->restoreStock(
                            productId: $item->product_id,
                            variantId: $item->product_variant_id,
                            quantity: $item->quantity,
                            reason: 'admin_order_cancelled_or_refunded',
                            referenceId: "ORDER-{$order->order_number}",
                            userId: auth()->id()
                        );
                    }
                }
            }

            $order->update($updates);

            $this->auditService->log(
                action: 'order.status_update',
                auditable: $order,
                oldValues: ['status' => $oldStatus],
                newValues: ['status' => $newStatus, 'tracking_number' => $trackingNumber]
            );
        });

        return back()->with('success', 'وضعیت سفارش با موفقیت بروزرسانی گردید.');
    }
}
