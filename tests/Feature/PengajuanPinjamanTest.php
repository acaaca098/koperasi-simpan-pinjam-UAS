<?php

namespace Tests\Feature;

use App\Exceptions\PinjamanException;
use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\User;
use App\Services\PinjamanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengajuanPinjamanTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_dengan_simpanan_cukup_bisa_mengajukan_pinjaman(): void
    {
        Storage::fake('s3');

        $user = User::factory()->create(['role' => 'anggota']);
        $anggota = Anggota::factory()->create(['user_id' => $user->id, 'status' => 'aktif']);
        Simpanan::factory()->create(['anggota_id' => $anggota->id, 'jenis' => 'wajib', 'saldo' => 1_000_000]);

        $pinjaman = app(PinjamanService::class)->ajukan(
            $anggota,
            2_000_000,
            12,
            UploadedFile::fake()->create('jaminan.pdf', 500),
        );

        $this->assertEquals('DIAJUKAN', $pinjaman->status);
        $this->assertDatabaseHas('pinjaman', ['id' => $pinjaman->id, 'status' => 'DIAJUKAN']);
    }

    public function test_anggota_tanpa_simpanan_cukup_tidak_bisa_mengajukan_pinjaman(): void
    {
        Storage::fake('s3');

        $user = User::factory()->create(['role' => 'anggota']);
        $anggota = Anggota::factory()->create(['user_id' => $user->id, 'status' => 'aktif']);
        // sengaja tidak buat Simpanan -> totalSimpanan() = 0

        $this->expectException(PinjamanException::class);

        app(PinjamanService::class)->ajukan(
            $anggota,
            2_000_000,
            12,
            UploadedFile::fake()->create('jaminan.pdf', 500),
        );
    }
}
