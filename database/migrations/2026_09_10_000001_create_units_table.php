<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('units')->insert([
            ['name' => 'tin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'pack', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'package', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'bottle', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'board', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'bag', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kg', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
