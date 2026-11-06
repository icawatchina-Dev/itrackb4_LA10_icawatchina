<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brgys', function (Blueprint $table) {
            $table->string('brgy_name');    
            $table->integer('population');
            $table->string('city');
            $table->integer('household')->nullable();
            $table->boolean('has_health_center');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brgys');
    }
};
