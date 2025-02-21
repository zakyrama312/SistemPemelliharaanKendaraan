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
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rekening')->constrained('rekening')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->enum('sumber_transaksi', ['pajak', 'pengeluaran_bbm', 'pemeliharaan']);
            $table->unsignedBigInteger('id_sumber'); // ID dari pajak, pengeluaran_bbm, atau pemeliharaan
            $table->integer('nominal');
            $table->integer('saldo_setelah');
            $table->timestamps();

            // Index untuk mempercepat pencarian
            $table->index(['id_rekening', 'sumber_transaksi', 'id_sumber']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};