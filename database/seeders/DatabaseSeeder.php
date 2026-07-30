<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo untuk presentasi/testing - password sama semua: "password"

        $ketua = User::create([
            'name' => 'Budi Ketua',
            'email' => 'ketua@koperasi.test',
            'password' => Hash::make('password'),
            'role' => 'ketua',
        ]);

        $pengurus = User::create([
            'name' => 'Siti Pengurus',
            'email' => 'pengurus@koperasi.test',
            'password' => Hash::make('password'),
            'role' => 'pengurus',
        ]);

        $userAnggota = User::create([
            'name' => 'Andi Anggota',
            'email' => 'anggota@koperasi.test',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);

        $anggota = Anggota::create([
            'user_id' => $userAnggota->id,
            'nomor_anggota' => 'ANG-0001',
            'alamat' => 'Jl. Contoh No. 1',
            'status' => 'aktif',
        ]);

        Simpanan::create([
            'anggota_id' => $anggota->id,
            'jenis' => 'wajib',
            'saldo' => 1_500_000,
        ]);

        // Anggota tambahan lewat factory kalau butuh data lebih banyak
        // Anggota::factory(10)->has(Simpanan::factory())->create();

        $this->command->info('Demo accounts:');
        $this->command->info('ketua@koperasi.test / pengurus@koperasi.test / anggota@koperasi.test');
        $this->command->info('Password semua: password');
    }
}
