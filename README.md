# Koperasi Simpan Pinjam — Sistem Pengajuan & Verifikasi Pinjaman Digital

Aplikasi manajemen simpan-pinjam koperasi berbasis web. Dibangun dengan
**Laravel 12** + **Filament 3** sebagai tugas akhir semester mata kuliah
Cloud Computing (UML, Software Engineering, dan Deployment ke Cloud).

## Ringkasan Studi Kasus

Anggota koperasi dapat menyetor/menarik simpanan dan mengajukan pinjaman
dengan jaminan. Pengajuan pinjaman diverifikasi oleh **Pengurus**; pengajuan
dengan nominal di atas ambang batas tertentu wajib mendapat persetujuan
tambahan dari **Ketua**. Setelah dicairkan, sistem otomatis membuat jadwal
angsuran bulanan, dan setiap hari sistem mengecek angsuran yang lewat jatuh
tempo untuk menandainya terlambat serta menghitung dendanya secara otomatis
(scheduled job).

| Elemen wajib tugas | Implementasi |
|---|---|
| ≥2 role dengan RBAC | `anggota`, `pengurus`, `ketua` — lihat `app/Policies/*`, `app/Http/Middleware/EnsureUserHasRole.php` |
| Proses approval/verifikasi | Alur `DIAJUKAN → DIVERIFIKASI (Pengurus) → DISETUJUI (Pengurus/Ketua, tergantung nominal) → DICAIRKAN` |
| Upload file | Jaminan pinjaman & bukti transfer angsuran, disimpan di object storage (S3-compatible) |
| Notifikasi otomatis | In-app (`notifikasi` table) + email, dipicu lewat Event/Listener setiap status berubah |
| Proses terjadwal | `php artisan angsuran:cek-jatuh-tempo`, dijadwalkan lewat `routes/console.php` (`Schedule::command(...)->dailyAt('01:00')`) |

## Arsitektur

Arsitektur berlapis (lihat `docs/uml/6-component-diagram.png`):

```
Presentation   → routes/web.php (Anggota), app/Filament (Pengurus & Ketua)
Application    → app/Http/Controllers, app/Filament/Resources, app/Policies
Domain         → app/Services (business logic), app/Events, app/Listeners
Infrastructure → app/Models (Eloquent), database/migrations, Storage (S3), Mail
```

- **Controllers** hanya menangani HTTP request/response & validasi input.
- **Services** (`PinjamanService`, `AngsuranService`, `SimpananService`,
  `NotificationService`) berisi semua business logic dan transaksi database.
- **Policies** menegakkan role-based access control di level otorisasi
  (dipanggil di Controller lewat `$this->authorize()` dan di Filament lewat
  `auth()->user()->can(...)`).
- **Events/Listeners** memisahkan efek samping (kirim notifikasi) dari alur
  bisnis utama, supaya `PinjamanService`/`AngsuranService` tidak perlu tahu
  soal pengiriman notifikasi.

Dokumentasi UML lengkap (7 diagram) ada di [`docs/uml/`](docs/uml/).

## Instalasi Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite   # kalau pakai SQLite untuk lokal
php artisan migrate --seed
php artisan storage:link         # hanya perlu kalau FILESYSTEM_DISK=public saat dev

npm install && npm run build
php artisan serve
```

Untuk development, biarkan `FILESYSTEM_DISK=local` (atau `public`) di `.env`
supaya tidak perlu kredensial S3. Untuk konfigurasi object storage cloud
sungguhan, contoh ada di `.env.cloud.example`.

### Menjalankan scheduler & queue (lokal)

```bash
php artisan schedule:work   # jalankan scheduled job tiap menit (simulasi cron)
php artisan queue:work      # proses email notifikasi (MAIL_MAILER=queue-able)
```

### Menjalankan test

```bash
php artisan test
```

## Akun Demo (dari `database/seeders/DatabaseSeeder.php`)

| Role | Email | Password |
|---|---|---|
| Anggota | anggota@koperasi.test | password |
| Pengurus | pengurus@koperasi.test | password |
| Ketua | ketua@koperasi.test | password |

Anggota masuk lewat halaman login biasa (`/login`) dan diarahkan ke
`/pinjaman`. Pengurus & Ketua masuk lewat panel Filament di `/admin`.

## Struktur Proyek Singkat

```
app/
  Models/        Anggota, Simpanan, SimpananTransaksi, Pinjaman, Angsuran, Notifikasi, User
  Services/      PinjamanService, AngsuranService, SimpananService, NotificationService
  Policies/      PinjamanPolicy, AngsuranPolicy, SimpananPolicy
  Http/
    Controllers/ PinjamanController, AngsuranController, SimpananController (web, role anggota)
    Middleware/  EnsureUserHasRole (role:anggota|pengurus|ketua)
  Filament/
    Resources/   PinjamanResource, AngsuranResource, SimpananResource (panel Pengurus & Ketua)
  Events/        PinjamanStatusChanged, AngsuranStatusChanged
  Listeners/     SendPinjamanNotification, SendAngsuranNotification
  Console/Commands/ CekJatuhTempoAngsuran  (scheduled job harian)
  Exceptions/    PinjamanException        (custom exception untuk business rule)
database/
  migrations/    struktur tabel sesuai docs/uml/7-erd.png
  seeders/       akun demo 3 role
docs/uml/        7 diagram UML wajib (lihat daftar di docs/uml/README.md)
```

## Alur Pengajuan Pinjaman (ringkas)

1. Anggota ajukan pinjaman + upload jaminan → status `DIAJUKAN`.
2. Sistem cek eligibilitas (total simpanan minimum) sebelum menyimpan.
3. Pengurus memverifikasi → status `DIVERIFIKASI`.
4. Jika nominal di atas ambang batas (`Pinjaman::THRESHOLD_APPROVAL_KETUA`,
   saat ini Rp 5.000.000), harus disetujui Ketua. Jika di bawah, Pengurus
   bisa langsung menyetujui.
5. Setelah `DISETUJUI`, Pengurus mencairkan dana → status `DICAIRKAN`,
   jadwal angsuran otomatis dibuat.
6. Anggota membayar tiap angsuran (upload bukti transfer) → Pengurus
   memverifikasi setoran → jika seluruh angsuran lunas, Pinjaman otomatis
   berstatus `LUNAS`.

Setiap perpindahan status memicu `Event` yang mengirim notifikasi otomatis
(in-app + email) ke pihak terkait.

## Deployment ke Cloud

Lihat bagian *Deployment & Arsitektur Cloud* pada laporan (`docs/laporan.pdf`,
kalau sudah dibuat) untuk detail justifikasi platform, estimasi biaya, dan
konsep cloud yang diterapkan (object storage terpisah untuk file upload
lewat disk `s3` di `config/filesystems.php`, secrets lewat environment
variable, dan scheduled job untuk `angsuran:cek-jatuh-tempo`).
