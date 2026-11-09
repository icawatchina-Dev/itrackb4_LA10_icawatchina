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
        Schema::create('_barangay', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('brgy_name');
            $table->string('city'); 
            $table->integer('population')->nullable();
        });
        Schema::table('_barangay', function (Blueprint $table) {
            $table->dropColumn(['brgy_name', 'city']);
        });
        Schema::table('_barangay', function (Blueprint $table) {
            $table->foreignId('brgy_id')->constrained('_barangay');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_barangay');
    }
};
