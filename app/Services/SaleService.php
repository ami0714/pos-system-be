<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
class SaleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function checkout($items, $total, $paymentMethod, $paidAmount, $balance, $discount,$adminId)
    {
        // Jalankan semua proses checkout dalam satu transaksi database.
        return DB::transaction(function () use ($items, $total, $paymentMethod, $paidAmount, $balance, $discount, $adminId) {
            // Ambil nombor resit terakhir untuk menjana nombor resit seterusnya pada hari ini.
            $invoicePrefix = 'INV-' . date('ymd') . '-';

            // SQL ini mengambil nombor resit terakhir dan mengunci rekod semasa transaksi berjalan.
            $lastInvoice = DB::select(
                'SELECT invoice_number FROM sales WHERE invoice_number LIKE ? ORDER BY id DESC LIMIT 1 FOR UPDATE',
                [$invoicePrefix . '%']
            );

            // Tambah satu pada nombor turutan resit terakhir.
            $invoiceNumber = $invoicePrefix . '0001';
            if (!empty($lastInvoice)) {
                // Ambil empat digit terakhir dari nombor resit terakhir dan tambah satu untuk menjana nombor resit baru.
                $lastNumber = (int) substr($lastInvoice[0]->invoice_number, -4);//substr hanya ambik 4 digit terakhir dari invoice_number
                // str_pad fungsi ini menambah sifar di hadapan nombor untuk memastikan ia sentiasa mempunyai empat digit.
                //str_pad_left ialah tambah 0 di kiri str_pad(nilai asal,panjang,apa yang nak tambah,STR_PAD_LEFT)
                $invoiceNumber = $invoicePrefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }

            // Kira subtotal sebelum diskaun berdasarkan nilai payload.
            $subtotal = (float) $total + (float) $discount;

            // SQL ini menyimpan ringkasan transaksi ke dalam jadual sales.
            DB::insert(
                'INSERT INTO sales (invoice_number, user_id, subtotal, discount, grand_total, paid_amount, change_amount, payment_method, status, created_at, updated_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
                [$invoiceNumber, $adminId, $subtotal, $discount, $total, $paidAmount, $balance, $paymentMethod, 'COMPLETED']
            );

            // SQL ini mengambil ID jualan yang baru dimasukkan.
            $saleId = DB::select('SELECT LAST_INSERT_ID() AS id');
            $saleId = $saleId[0]->id;
            $receiptItems = [];

            // Ulang setiap item untuk menolak stok dan menyimpan butiran resit.
            //disebabkan hanya ada satu data banyak iaitu items maka hanya satu loop dan gunakan untuk query semua table
            foreach ($items as $item) {
                // SQL ini mengambil harga, nama, dan stok produk serta mengunci produk tersebut.
                $product = DB::select(
                    'SELECT id, name, purchase_price, selling_price, stock_quantity FROM products WHERE id = ? FOR UPDATE',
                    [$item['productId']]
                );

                // Hentikan checkout jika produk tidak dijumpai.
                if (empty($product)) {
                    throw new \Exception('Produk tidak dijumpai.');
                }

                // Hentikan checkout jika stok produk tidak mencukupi.
                if ($product[0]->stock_quantity < $item['qty']) {
                    throw new \Exception('Stok produk ' . $product[0]->name . ' tidak mencukupi.');
                }

                // Kira jumlah untuk satu baris item.
                $itemSubtotal = (float) $product[0]->selling_price * (int) $item['qty'];

                // SQL ini menolak kuantiti stok produk selepas jualan.
                DB::update(
                    'UPDATE products SET stock_quantity = stock_quantity - ?, updated_at = NOW() WHERE id = ?',
                    [$item['qty'], $item['productId']]
                );

                // SQL ini menyimpan butiran item ke dalam jadual sale_items.
                DB::insert(
                    'INSERT INTO sale_items (sale_id, product_id, quantity, buy_price, sell_price, subtotal, created_at, updated_at)
                     VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())',
                    [$saleId, $item['productId'], $item['qty'], $product[0]->purchase_price, $product[0]->selling_price, $itemSubtotal]
                );

                // SQL ini menyimpan rekod stok keluar untuk audit jualan.
                DB::insert(
                    'INSERT INTO stock_movements (product_id, user_id, type, quantity, note, created_at, updated_at)
                     VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
                    [$item['productId'], $adminId, 'SALE', $item['qty'], 'Sale ' . $invoiceNumber]
                );

                // Sediakan item dalam format data resit yang diperlukan oleh React.
                $receiptItems[] = [
                    'name' => $product[0]->name,
                    'price' => (float) $product[0]->selling_price,
                    'qty' => (int) $item['qty'],
                ];
            }

            // SQL ini mengambil nama cashier untuk dipaparkan pada resit.
            $cashier = DB::select('SELECT name FROM users WHERE id = ?', [$adminId]);

            // Pulangkan data resit selepas semua proses checkout berjaya.
            return [
                'status' => 'success',
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
                ],
            ];
        });
    }
}
