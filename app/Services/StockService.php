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
        ';

        $conditions = [];
        $bindings = [];

        if (!empty($type) && $type !== 'ALL') {
            $conditions[] = 'sm.type = ?';
            $bindings[] = $type;
        }

        if (!empty($start) && !empty($end)) {
            $conditions[] = 'sm.created_at >= ? AND sm.created_at <= ?';
            $bindings[] = $start;
            $bindings[] = $end;
        }

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        return DB::select($query, $bindings);
    }
}
