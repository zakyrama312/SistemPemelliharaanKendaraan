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
        Schema::create('pajak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kendaraan')->constrained('kendaraan')->cascadeOnDelete();
            $table->foreignId('id_rekening')->constrained('rekening')->cascadeOnDelete();
            $table->date('masa_berlaku');
            $table->enum("jenis_pajak",['pajak_tahunan', 'pajak_plat'])->default('pajak_tahunan');
            $table->integer('nominal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pajak');
    }
};