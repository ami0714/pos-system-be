<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getProduct($catId, $stockStatus)
    {
        if (empty($catId) && empty($stockStatus)) {
            return [
                'status' => false,
                'message' => 'data kosong',
            ];
        }
       //query untuk semua cat dan semua stock
        $query = 'SELECT 
                    p.id,
                    p.barcode,
                    p.name,
                    c.name AS category,
                    p.purchase_price AS cost_price,
                    p.selling_price AS sell_price,
                    p.stock_quantity AS stock,
                    p.min_stock,
                    u.name AS unit,
                    p.created_at,
                    p.updated_at

                     FROM products p
                     LEFT JOIN categories c ON p.category_id = c.id
                     LEFT JOIN units u ON p.unit_id = u.id
                     WHERE 1 AND p.stock_quantity >=0 ';
        $bindings = [];


        //cek untuk jika category bukan sama 5 maka akan tambah query dan masuk dalam binding
        if (!empty($catId) && $catId != 5) { // jika stock all
            $query .= ' AND category_id = :category_id'; //akan tambah query
            $bindings['category_id'] = $catId;
        }

         //syarat untuk jika stokc tidak sama all
        if (!empty($stockStatus) && $stockStatus !== 'ALL') {
            if ($stockStatus === 'LOW') {
                $query .= ' AND stock_quantity = min_stock';
            } elseif ($stockStatus === 'OUT') {
                $query .= ' AND stock_quantity = 0';
            }
        }

        $products = DB::select($query, $bindings);

        return [
            'status' => true,
            'data' => $products,
        ];
    }
    
    public function getProductByBarcode($barcode){
             if (empty($barcode)) {
            return [
                'status' => false,
                'message' => 'data kosong',
                'barcode' => $barcode
            ];
        }

        $query = 'SELECT 
                    p.id,
                    p.barcode,
                    p.name,
                    c.name AS category,
                    c.id AS category_id,
                    p.purchase_price AS cost_price,
                    p.selling_price AS sell_price,
                    p.stock_quantity AS stock,
                    p.min_stock,
                    u.name AS unit,
                    u.id AS unitId,
                    p.created_at,
                    p.updated_at

                     FROM products p
                     LEFT JOIN categories c ON p.category_id = c.id
                     LEFT JOIN units u ON p.unit_id = u.id
                     WHERE barcode = :barcode';

                      return DB::selectOne($query,['barcode'=> $barcode]);
    }


    public function addProduct( $barcode,
            $name,
            $categoryId,
            $costPrice,
            $sellingPrice,
            $stockQuantity,
            $minStock,
            $unitId)
    {
        return DB::transaction(function () use ($barcode, $name, $categoryId, $costPrice, $sellingPrice, $stockQuantity, $minStock, $unitId) {
            // Insert the new product into the products table
            DB::insert('INSERT INTO products (barcode, name, category_id, purchase_price, selling_price, stock_quantity, min_stock, unit_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [$barcode, $name, $categoryId, $costPrice, $sellingPrice, $stockQuantity, $minStock, $unitId]);

            return [
                'status' => true,
                'message' => 'Product added successfully.',
            ];
        });
    }

     public function editProduct( $barcode,
            $name,
            $categoryId,
            $costPrice,
            $sellingPrice,
            $stockQuantity,
            $minStock,
            $unitId,
            $productId)
    {
        return DB::transaction(function () use ($barcode, $name, $categoryId, $costPrice, $sellingPrice, $stockQuantity, $minStock, $unitId, $productId) {
            DB::update(
                'UPDATE products SET barcode = ?, name = ?, category_id = ?, purchase_price = ?, selling_price = ?, stock_quantity = ?, min_stock = ?, unit_id = ? WHERE id = ?',
                [$barcode, $name, $categoryId, $costPrice, $sellingPrice, $stockQuantity, $minStock, $unitId, $productId]
            );

            return [
                'status' => true,
                'message' => 'Product updated successfully.',
            ];
        });
    }
}

