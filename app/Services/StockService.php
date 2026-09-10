<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
class StockService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getLogProduct($type,$start,$end){
        $query = 'SELECT 
                    sm.id AS movId,
                    p.name AS productName,
                    u.name AS adminName,
                    sm.type AS movType,
                    sm.quantity AS quantity,
                    sm.note AS note,
                    sm.created_at AS date
                    FROM stock_movements sm
                    JOIN products p ON sm.product_id = p.id
                    JOIN users u ON sm.user_id = u.id
                    ORDER BY sm.created_at DESC
        ';


        $conditions = [];
        $bindings = [];

        if (!empty($type) && $type !== 'ALL') {
            //push kondisi ke array conditions dan masuk ke array bindings
            $conditions[] = 'sm.type = ?';
            $bindings[] = $type;
        }

        if (!empty($start) && !empty($end)) {
            //push kondisi ke array conditions dan masuk ke array bindings
            $conditions[] = 'sm.created_at >= ? AND sm.created_at <= ?';
            $bindings[] = $start;
            $bindings[] = $end;
        }

        if (!empty($conditions)) {
            //gabungkan kondisi menjadi satu string dengan AND,impolde akan menggabungkan array menjadi string
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        return DB::select($query, $bindings);
    }

    public function stockIn($productId, $quantity,$sellPrice,$buyPrice, $type, $note = null, $adminId)
    {
        return DB::transaction(function () use ($productId, $quantity, $sellPrice, $buyPrice, $type, $note, $adminId) {

        if($quantity <= 0){
            return [
                'status' => false,
                'message' => 'Quantity must be greater than zero.',
            ];
        }
    
            $updateStock = DB::update('UPDATE products SET stock_quantity = stock_quantity + ?,purchase_price = ?,selling_price=? WHERE id = ?', [$quantity, $buyPrice, $sellPrice, $productId]);
            $moveStock = DB::insert('INSERT INTO stock_movements (product_id, user_id, type, quantity, note, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())', [$productId, $adminId, $type, $quantity, $note]);    


            return [
                'status' => true,
                'message' => 'Stock updated successfully.',
            ];
        });
    }

    
    public function stockAdjust($productId, $quantity,$sellPrice,$buyPrice, $type, $note = null, $adminId)
    {
        return DB::transaction(function () use ($productId, $quantity, $sellPrice, $buyPrice, $type, $note, $adminId) {
    
            $updateStock = DB::update('UPDATE products SET stock_quantity = stock_quantity + ?,purchase_price = ?,selling_price=? WHERE id = ?', [$quantity, $buyPrice, $sellPrice, $productId]);
            $moveStock = DB::insert('INSERT INTO stock_movements (product_id, user_id, type, quantity, note, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())', [$productId, $adminId, $type, $quantity, $note]);    


            return [
                'status' => true,
                'message' => 'Stock updated successfully.',
            ];
        });
    }
}
