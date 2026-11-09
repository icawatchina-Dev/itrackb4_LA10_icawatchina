<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $now = now();
        DB::table('brgys')
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->each(fn (string $city) => DB::table('municipalities')->insert([
                'name' => $city,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
