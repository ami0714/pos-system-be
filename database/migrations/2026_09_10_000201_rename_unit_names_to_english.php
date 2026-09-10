<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $map = [
            'bungkus' => 'package',
            'botol' => 'bottle',
            'papan' => 'board',
            'beg' => 'bag',
        ];

        foreach ($map as $oldName => $newName) {
            DB::table('units')
                ->where('name', $oldName)
                ->update(['name' => $newName]);
        }
    }

    public function down(): void
    {
        $map = [
            'package' => 'bungkus',
            'bottle' => 'botol',
            'board' => 'papan',
            'bag' => 'beg',
        ];

        foreach ($map as $newName => $oldName) {
            DB::table('units')
                ->where('name', $newName)
                ->update(['name' => $oldName]);
        }
    }
};
