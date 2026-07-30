<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->enum('jenis', ['pokok', 'wajib', 'sukarela']);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('simpanan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simpanan_id')->constrained('simpanan')->cascadeOnDelete();
            $table->enum('jenis', ['setor', 'tarik']);
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simpanan_transaksi');
        Schema::dropIfExists('simpanan');
    }
};
