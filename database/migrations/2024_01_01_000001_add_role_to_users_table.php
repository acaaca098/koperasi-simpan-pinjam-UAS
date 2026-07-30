<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NOTE: file ini menambah kolom ke tabel `users` bawaan Laravel (dibuat oleh
// migration default 0001_01_01_000000_create_users_table.php).
// Sesuai ERD: USERS punya kolom role (anggota/pengurus/ketua).

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['anggota', 'pengurus', 'ketua'])
                ->default('anggota')
                ->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
