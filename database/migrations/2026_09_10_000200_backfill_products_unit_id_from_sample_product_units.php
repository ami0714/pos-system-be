<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $mapping = [
            1 => 'tin',
            2 => 'pcs',
            3 => 'pack',
            4 => 'package',
            5 => 'bottle',
            6 => 'tin',
            7 => 'pack',
            8 => 'board',
            9 => 'bag',
            10 => 'kg',
        ];

        foreach ($mapping as $productId => $unitName) {
            $unitId = DB::table('units')->where('name', $unitName)->value('id');

            if ($unitId) {
                DB::table('products')
                    ->where('id', $productId)
                    ->update(['unit_id' => $unitId]);
            }
        }
    }

    public function down(): void
    {
        DB::table('products')->update(['unit_id' => null]);
    }
};
