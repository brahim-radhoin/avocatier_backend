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
        Schema::create('avo_specialties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avo_id');
            $table->unsignedBigInteger('specialty_id');
            $table->timestamps();
    
            $table->foreign('avo_id')
                ->references('id_av')
                ->on('avos');
            
            $table->foreign('specialty_id')
                ->references('id_s')
                ->on('specialties');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avo_specialties');
    }
};