<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete();
            $table->unsignedInteger('angsuran_ke');
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('denda', 15, 2)->default(0);
            $table->date('jatuh_tempo');
            $table->string('bukti_transfer_path')->nullable(); // object storage path

            // status mengikuti sequence diagram angsuran:
            // BELUM_BAYAR -> MENUNGGU_VERIFIKASI -> LUNAS (atau TELAT via scheduled job)
            $table->enum('status', ['BELUM_BAYAR', 'MENUNGGU_VERIFIKASI', 'LUNAS', 'TELAT'])
                ->default('BELUM_BAYAR');

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
