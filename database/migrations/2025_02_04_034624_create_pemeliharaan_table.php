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
        Schema::create('pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kendaraan')->constrained('kendaraan')->cascadeOnDelete();
            $table->date('tanggal_pemeliharaan');
            $table->string('bengkel')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('biaya')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemiliharaan');
    }
};