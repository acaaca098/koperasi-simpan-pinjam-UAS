<?php

use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimpananController;
use App\Models\Anggota;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Route;

// Halaman utama: tampilkan landing page untuk tamu, redirect ke area kerja
// masing-masing role kalau sudah login.
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome', [
        'totalAnggota' => Anggota::where('status', 'aktif')->count(),
        'totalPinjamanTersalurkan' => Pinjaman::whereIn('status', ['dicairkan', 'DICAIRKAN', 'lunas', 'LUNAS'])->sum('jumlah_pengajuan'),
    ]);
})->name('home');

// Dibutuhkan oleh AuthenticatedSessionController bawaan Breeze setelah login berhasil.
// Satu pintu login untuk semua role (route('login')); habis itu diarahkan
// sesuai role masing-masing.
Route::get('/dashboard', function () {
    return match (true) {
        auth()->user()->isAnggota() => redirect()->route('pinjaman.index'),
        auth()->user()->isPengurus(), auth()->user()->isKetua() => redirect('/admin'),
        default => redirect('/'),
    };
})->middleware('auth')->name('dashboard');

// ---- Route bawaan Breeze (profile) ----
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---- Koperasi Simpan Pinjam: route per role ----
Route::middleware(['auth'])->group(function () {

    // Anggota
    Route::middleware('role:anggota')->group(function () {
        Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
        Route::get('/pinjaman/ajukan', [PinjamanController::class, 'create'])->name('pinjaman.create');
        Route::post('/pinjaman', [PinjamanController::class, 'store'])->name('pinjaman.store');
        Route::post('/angsuran/{angsuran}/bayar', [AngsuranController::class, 'bayar'])->name('angsuran.bayar');

        // Simpanan: lihat saldo, setor, dan tarik simpanan sukarela sendiri.
        Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
        Route::post('/simpanan/{simpanan}/setor', [SimpananController::class, 'setor'])->name('simpanan.setor');
        Route::post('/simpanan/{simpanan}/tarik', [SimpananController::class, 'tarik'])->name('simpanan.tarik');
    });

    // Pengurus
    Route::middleware('role:pengurus')->group(function () {
        Route::post('/pinjaman/{pinjaman}/verifikasi', [PinjamanController::class, 'verifikasi'])->name('pinjaman.verifikasi');
        Route::post('/pinjaman/{pinjaman}/approve', [PinjamanController::class, 'approve'])->name('pinjaman.approve');
        Route::post('/pinjaman/{pinjaman}/cairkan', [PinjamanController::class, 'cairkan'])->name('pinjaman.cairkan');
        Route::post('/angsuran/{angsuran}/verifikasi', [AngsuranController::class, 'verifikasi'])->name('angsuran.verifikasi');
    });

    // Ketua
    Route::middleware('role:ketua')->group(function () {
        Route::post('/pinjaman/{pinjaman}/approve-ketua', [PinjamanController::class, 'approve'])->name('pinjaman.approve-ketua');
    });
});

require __DIR__.'/auth.php';