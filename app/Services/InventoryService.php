<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Reserve or verify stock for an order item using database locking.
     *
     * @throws Exception
     */
    public function deductStock(
        int $productId,
        ?int $variantId,
        int $quantity,
        string $reason = 'order_deducted',
        ?string $referenceId = null,
        ?int $userId = null
    ): void {
        DB::transaction(function () use ($productId, $variantId, $quantity, $reason, $referenceId, $userId) {
            if ($variantId) {
                $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->firstOrFail();
                if ($variant->stock < $quantity) {
                    throw new Exception("موجودی تنوع '{$variant->variant_label}' برای تعداد درخواستی کافی نمی‌باشد.");
                }

                $previousStock = $variant->stock;
                $newStock = $previousStock - $quantity;
                $variant->update(['stock' => $newStock]);

                // Also update parent product aggregate stock if tracked
                $product = Product::where('id', $productId)->lockForUpdate()->first();
                if ($product) {
                    $product->decrement('stock', $quantity);
                }

                // Update inventory table if present
                $inventory = Inventory::where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->lockForUpdate()
                    ->first();

                if ($inventory) {
                    $inventory->update(['stock' => $newStock]);
                }

                StockHistory::create([
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                    'user_id' => $userId,
                    'quantity_change' => -$quantity,
                    'previous_stock' => $previousStock,
                    'current_stock' => $newStock,
                    'reason' => $reason,
                    'reference_id' => $referenceId,
                ]);
            } else {
                $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
                if ($product->stock < $quantity) {
                    throw new Exception("موجودی محصول '{$product->name}' برای تعداد درخواستی کافی نمی‌باشد.");
                }

                $previousStock = $product->stock;
                $newStock = $previousStock - $quantity;
                $product->update(['stock' => $newStock]);

                $inventory = Inventory::where('product_id', $productId)
                    ->whereNull('product_variant_id')
                    ->lockForUpdate()
                    ->first();

                if ($inventory) {
                    $inventory->update(['stock' => $newStock]);
                }

                StockHistory::create([
                    'product_id' => $productId,
                    'product_variant_id' => null,
                    'user_id' => $userId,
                    'quantity_change' => -$quantity,
                    'previous_stock' => $previousStock,
                    'current_stock' => $newStock,
                    'reason' => $reason,
                    'reference_id' => $referenceId,
                ]);
            }
        });
    }

    /**
     * Restore stock when an order is cancelled or refunded.
     */
    public function restoreStock(
        int $productId,
        ?int $variantId,
        int $quantity,
        string $reason = 'order_cancelled',
        ?string $referenceId = null,
        ?int $userId = null
    ): void {
        DB::transaction(function () use ($productId, $variantId, $quantity, $reason, $referenceId, $userId) {
            if ($variantId) {
                $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->firstOrFail();
                $previousStock = $variant->stock;
                $newStock = $previousStock + $quantity;
                $variant->update(['stock' => $newStock]);

                $product = Product::where('id', $productId)->lockForUpdate()->first();
                if ($product) {
                    $product->increment('stock', $quantity);
                }

                $inventory = Inventory::where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->lockForUpdate()
                    ->first();

                if ($inventory) {
                    $inventory->update(['stock' => $newStock]);
                }

                StockHistory::create([
                    'product_id' => $productId,
                    'product_variant_id' => $variantId,
                    'user_id' => $userId,
                    'quantity_change' => $quantity,
                    'previous_stock' => $previousStock,
                    'current_stock' => $newStock,
                    'reason' => $reason,
                    'reference_id' => $referenceId,
                ]);
            } else {
                $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();
                $previousStock = $product->stock;
                $newStock = $previousStock + $quantity;
                $product->update(['stock' => $newStock]);

                $inventory = Inventory::where('product_id', $productId)
                    ->whereNull('product_variant_id')
                    ->lockForUpdate()
                    ->first();

                if ($inventory) {
                    $inventory->update(['stock' => $newStock]);
                }

                StockHistory::create([
                    'product_id' => $productId,
                    'product_variant_id' => null,
                    'user_id' => $userId,
                    'quantity_change' => $quantity,
                    'previous_stock' => $previousStock,
                    'current_stock' => $newStock,
                    'reason' => $reason,
                    'reference_id' => $referenceId,
                ]);
            }
        });
    }

    /**
     * Check if product/variant has sufficient available stock.
     */
    public function hasStock(int $productId, ?int $variantId, int $quantity): bool
    {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);

            return $variant && $variant->stock >= $quantity;
        }

        $product = Product::find($productId);

        return $product && $product->stock >= $quantity;
    }
}
