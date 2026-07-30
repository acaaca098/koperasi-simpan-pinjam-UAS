# Dokumentasi UML — Koperasi Simpan Pinjam

7 diagram wajib sesuai soal Bagian 5.1, konsisten dengan kode di `app/`.

| # | Diagram | File | Konsisten dengan kode |
|---|---|---|---|
| 1 | Use Case Diagram | [`1-use-case-diagram.png`](1-use-case-diagram.png) | Aktor `Anggota/Pengurus/Ketua` = kolom `users.role`; setiap use case dijaga oleh `EnsureUserHasRole` (route) atau `Policy` (Filament action) |
| 2 | Class Diagram | [`2-class-diagram.png`](2-class-diagram.png) | `app/Models/*`, `app/Services/*` — nama method di diagram sama persis dengan method di kode (`ajukan()`, `verifikasiPengurus()`, `approvalKetua()`, `cairkan()`, `bayar()`, `verifikasiSetoran()`, `cekJatuhTempo()`, `kirim()`) |
| 3 | Sequence Diagram — Pengajuan Pinjaman | [`3-sequence-diagram-pengajuan-pinjaman.png`](3-sequence-diagram-pengajuan-pinjaman.png) | `PinjamanController` → `PinjamanService` → Event `PinjamanStatusChanged` → `SendPinjamanNotification` |
| 4 | Sequence Diagram — Bayar Angsuran | [`4-sequence-diagram-angsuran.png`](4-sequence-diagram-angsuran.png) | `AngsuranController` → `AngsuranService` → Event `AngsuranStatusChanged` → `SendAngsuranNotification` |
| 5 | Activity Diagram | [`5-activity-diagram.png`](5-activity-diagram.png) | Alur end-to-end lintas role: Login → Ajukan Pinjaman → Verifikasi → (Approval Ketua bila di atas threshold) → Cairkan → Bayar Angsuran → Verifikasi → Lunas |
| 6 | Component/Architecture Diagram | [`6-component-diagram.png`](6-component-diagram.png) | 4 lapisan: Presentation (`routes/web.php`, `app/Filament`) → Application (`Controllers`, `Filament Resources`, `Policies`, `Events/Listeners`) → Domain (`Services`) → Infrastructure (`Eloquent Models`, `Migrations`, `File Storage`, `Email`) |
| 7 | ERD | [`7-erd.png`](7-erd.png) | `database/migrations/*` — semua tabel & foreign key sesuai diagram |

## Catatan Perbaikan Konsistensi

Selama audit kode terhadap diagram, ditemukan dan diperbaiki beberapa hal
supaya kode benar-benar konsisten dengan rancangan (bukan kode aspirational):

- `PinjamanResource`, `AngsuranResource`, dan `SimpananResource` (class utama
  Filament Resource) sebelumnya **belum ada di kode** meskipun sudah
  direferensikan oleh Pages-nya — sekarang dibuat lengkap dengan action
  `verifikasi`, `approve`, `tolak`, `cairkan` yang di-guard oleh Policy yang
  sama seperti di Class Diagram.
- Beberapa nama file (`SimpananController`, `SimpananPolicy`) sebelumnya
  memakai casing yang tidak sesuai PSR-4 — sudah diperbaiki.
- View halaman "Pinjaman Saya" (Anggota) sebelumnya kosong dari data jadwal
  angsuran — sekarang menampilkan daftar pinjaman + jadwal angsuran + form
  bayar sesuai sequence diagram #4.

Lihat riwayat commit Git untuk detail masing-masing perbaikan.
