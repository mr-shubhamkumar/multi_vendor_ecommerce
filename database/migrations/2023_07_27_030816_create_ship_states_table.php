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
        Schema::create('ship_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->references('id')->on('ship_divisions')->onDelete('cascade');
            $table->foreignId('districts_id')->references('id')->on('ship_districts')->onDelete('cascade');
            $table->string('states_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ship_states');
    }
};
