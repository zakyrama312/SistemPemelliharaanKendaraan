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
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users')->cascadeOnDelete();
            $table->string('no_polisi');
            $table->string('slug')->unique();
            $table->string('merk');
            $table->string('model');
            $table->string('jenis');
            $table->string('foto')->nullable();
            $table->date('tahun_pembuatan');
            $table->date('masa_aktif_pajak_tahunan');
            $table->date('masa_aktif_plat');
            $table->string('warna');
            $table->string('no_rangka');
            $table->integer('interval_bulan')->nullable();
            $table->string('no_mesin');
            $table->string('bahan_bakar')->nullable();
            $table->string('jumlah_roda')->nullable();
            $table->string('bidang')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};