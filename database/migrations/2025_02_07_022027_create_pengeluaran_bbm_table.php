<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengeluaran_bbm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kendaraan')->constrained('kendaraan')->cascadeOnDelete();
            $table->foreignId('id_rekening')->constrained('rekening')->cascadeOnDelete()->nullable();
            $table->string('foto_struk')->nullable();
            $table->integer('jumlah_liter')->nullable();
            $table->integer('harga_bbm')->nullable();
            $table->integer('nominal');
            $table->string('spbu')->nullable();
            $table->date('tanggal_pengisian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_bbm');
    }
};
