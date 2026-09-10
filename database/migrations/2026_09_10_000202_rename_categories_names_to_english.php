<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $map = [
            'Minuman' => [
                'name' => 'Beverages',
                'description' => 'Bottled water, cans, and cartons',
            ],
            'Makanan Ringan' => [
                'name' => 'Snacks',
                'description' => 'Chips, biscuits, and snacks',
            ],
            'Keperluan Dapur' => [
                'name' => 'Kitchen Supplies',
                'description' => 'Oil, flour, and spices',
            ],
            'Produk Segar' => [
                'name' => 'Fresh Products',
                'description' => 'Bread, vegetables, and fruits',
            ],
            'All' => [
                'name' => 'All',
                'description' => 'All product types',
            ],
        ];

        foreach ($map as $oldName => $payload) {
            DB::table('categories')
                ->where('name', $oldName)
                ->update([
                    'name' => $payload['name'],
                    'description' => $payload['description'],
                ]);
        }
    }

    public function down(): void
    {
        $map = [
            'Beverages' => [
                'name' => 'Minuman',
                'description' => 'Air botol, tin, dan kotak',
            ],
            'Snacks' => [
                'name' => 'Makanan Ringan',
                'description' => 'Chips, biskut, dan snek',
            ],
            'Kitchen Supplies' => [
                'name' => 'Keperluan Dapur',
                'description' => 'Minyak, tepung, dan rempah',
            ],
            'Fresh Products' => [
                'name' => 'Produk Segar',
                'description' => 'Roti, sayur, dan buah',
            ],
            'All' => [
                'name' => 'All',
                'description' => 'semua jenis',
            ],
        ];

        foreach ($map as $currentName => $payload) {
            DB::table('categories')
                ->where('name', $currentName)
                ->update([
                    'name' => $payload['name'],
                    'description' => $payload['description'],
                ]);
        }
    }
};
