<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->decimal('jumlah_pengajuan', 15, 2);
            $table->unsignedInteger('tenor_bulan');
            $table->decimal('bunga_persen', 5, 2)->default(1.5);
            $table->string('jaminan_path')->nullable(); // path file upload jaminan (object storage)

            // status mengikuti activity & sequence diagram:
            // DIAJUKAN -> DIVERIFIKASI -> (DISETUJUI|DITOLAK) -> DICAIRKAN -> LUNAS
            $table->enum('status', [
                'DIAJUKAN', 'DIVERIFIKASI', 'DISETUJUI', 'DITOLAK', 'DICAIRKAN', 'LUNAS',
            ])->default('DIAJUKAN');

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
