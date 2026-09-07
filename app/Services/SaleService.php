<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleService
{
    public function checkout($items, $total, $paymentMethod, $paidAmount, $balance, $discount, $adminId)
    {
        // Validation: pastikan items tidak kosong
        if (empty($items)) {
            throw new \Exception('Keranjang kosong.');
        }

        Log::info('Checkout started', ['items' => $items, 'total' => $total]);

        return DB::transaction(function () use ($items, $total, $paymentMethod, $paidAmount, $balance, $discount, $adminId) {
            
            // 1. Generate Invoice Number
            $invoicePrefix = 'INV-' . date('ymd') . '-';
            $lastInvoice = DB::select(
                'SELECT invoice_number FROM sales WHERE invoice_number LIKE ? ORDER BY id DESC LIMIT 1 FOR UPDATE',
                [$invoicePrefix . '%']
            );

            $invoiceNumber = $invoicePrefix . '0001';
            if (!empty($lastInvoice)) {
                $lastNumber = (int) substr($lastInvoice[0]->invoice_number, -4);
                $invoiceNumber = $invoicePrefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }

            // 2. Kira subtotal
            $subtotal = (float) $total + (float) $discount;

            // 3. Insert ke sales
            DB::insert(
                'INSERT INTO sales (invoice_number, user_id, subtotal, discount, grand_total, paid_amount, change_amount, payment_method, status, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
                [$invoiceNumber, $adminId, $subtotal, $discount, $total, $paidAmount, $balance, $paymentMethod, 'COMPLETED']
            );

            // 4. Dapatkan sale_id
            $saleId = DB::select('SELECT LAST_INSERT_ID() AS id');
            $saleId = $saleId[0]->id;
            Log::info('Sale inserted', ['sale_id' => $saleId, 'invoice' => $invoiceNumber]);

            $receiptItems = [];

            // 5. Loop setiap item
            foreach ($items as $item) {
                // Pastikan key wujud
                if (!isset($item['productId']) || !isset($item['qty'])) {
                    throw new \Exception('Data item tidak lengkap.');
                }

                // Ambil produk (dengan lock)
                $product = DB::select(
                    'SELECT id, name, purchase_price, selling_price, stock_quantity FROM products WHERE id = ? FOR UPDATE',
                    [$item['productId']]
                );

                if (empty($product)) {
                    throw new \Exception('Produk ID ' . $item['productId'] . ' tidak dijumpai.');
                }

                if ($product[0]->stock_quantity < $item['qty']) {
                    throw new \Exception('Stok produk ' . $product[0]->name . ' tidak mencukupi.');
                }

                $itemSubtotal = (float) $product[0]->selling_price * (int) $item['qty'];

                // 6. Update stok
                DB::update(
                    'UPDATE products SET stock_quantity = stock_quantity - ?, updated_at = NOW() WHERE id = ?',
                    [$item['qty'], $item['productId']]
                );

                // 7. Insert sale_items
                DB::insert(
                    'INSERT INTO sale_items (sale_id, product_id, quantity, buy_price, sell_price, subtotal, created_at, updated_at)
                     VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())',
                    [$saleId, $item['productId'], $item['qty'], $product[0]->purchase_price, $product[0]->selling_price, $itemSubtotal]
                );
                Log::info('Sale_item inserted', ['product' => $product[0]->name]);

                // 8. Insert stock_movements (guna negatif untuk SALE)
                DB::insert(
                    'INSERT INTO stock_movements (product_id, user_id, type, quantity, note, created_at, updated_at)
                     VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
                    [$item['productId'], $adminId, 'SALE', -$item['qty'], 'Sale ' . $invoiceNumber]
                );
                Log::info('Stock_movement inserted', ['product' => $product[0]->name, 'qty' => -$item['qty']]);

                // Simpan untuk resit
                $receiptItems[] = [
                    'name' => $product[0]->name,
                    'price' => (float) $product[0]->selling_price,
                    'qty' => (int) $item['qty'],
                ];
            }

            // 9. Ambil cashier
            $cashier = DB::select('SELECT name FROM users WHERE id = ?', [$adminId]);

            Log::info('Checkout completed successfully', ['invoice' => $invoiceNumber]);

            return [
                'status' => true,
                'message' => 'Checkout berjaya.',
                'data' => [
                    'id' => $saleId,
                    'invoice_no' => $invoiceNumber,
                    'date' => date('d/m/Y'),
                    'cashier' => !empty($cashier) ? $cashier[0]->name : '',
                    'payment' => $paymentMethod === 'CASH' ? 'Cash' : $paymentMethod,
                    'total' => (float) $total,
                    'status' => 'COMPLETED',
                    'items' => $receiptItems,
                    'payment_method' => $paymentMethod,
                ],
            ];
        });
    }
}