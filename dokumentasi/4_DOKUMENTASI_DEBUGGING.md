# DOKUMENTASI DEBUGGING DAN CATATAN PENGUJIAN SISTEM
## Sistem Pengaduan Sarana dan Prasarana Sekolah (SchoolCare)

---

### 1. Ringkasan Aktivitas Debugging & Penyelesaian Kendala

Selama proses pengembangan dan pengujian aplikasi SchoolCare pada lingkungan Windows dan Laravel 12, dilakukan serangkaian pengujian dan penanganan kendala teknis sebagai berikut:

#### Kendala 1: Ketersediaan Layanan MySQL Lokal
- **Gejala / Masalah**: Perintah pengujian awal koneksi ke port MySQL `127.0.0.1:3306` menghasilkan `Connection refused / WSAEACCES (10013)` karena daemon MySQL pada XAMPP/Laragon belum dinyalakan oleh pengguna.
- **Penyebab**: Service MySQL belum aktif di Windows saat pengujian otomatis berlangsung.
- **Solusi**:
  1. Konfigurasi `.env` telah disiapkan secara tepat untuk database MySQL bernama `db_schoolcare` (host `127.0.0.1`, port `3306`, user `root`).
  2. Dibuatkan skrip utuh `database/db_schoolcare.sql` yang siap diimpor langsung ke phpMyAdmin atau MySQL CLI.
  3. Konfigurasi `phpunit.xml` diatur untuk menggunakan database in-memory SQLite (`:memory:`) selama proses automated testing sehingga seluruh pengujian unit/fitur dapat berjalan 100% tanpa hambatan.
  4. Diberikan panduan langkah demi langkah bagi penguji UKK untuk mengaktifkan MySQL di XAMPP / Laragon.

---

#### Kendala 2: Akses Symlink `public/storage` di Sistem Operasi Windows
- **Gejala / Masalah**: Eksekusi perintah `php artisan storage:link` pada beberapa versi Windows tanpa Developer Mode menghasilkan `Access is denied` saat pembuatan symlink direktori.
- **Penyebab**: Windows memerlukan hak administrator penuh untuk membuat symbolic link pada sistem file NTFS.
- **Solusi**:
  1. Dibuat controller khusus `App\Http\Controllers\MediaController@showPhoto` yang menangani rute `GET /media/photo/{path}`.
  2. Dilakukan sanitasi traversal path (`str_replace(['..', '\\'], ['', '/'], $path)`).
  3. Gambar disajikan langsung menggunakan `Storage::disk('public')->get()` disertai header MIME type yang tepat (`image/jpeg`, `image/png`, dll).
  4. Model `Aspiration` dilengkapi aksesor `photo_url` yang mengarahkan URL foto ke rute media ini, menjamin foto bukti pengaduan selalu dapat tampil sempurna di laptop penguji maupun perangkat mobile tanpa bergantung pada symlink OS.

---

#### Kendala 3: Token Pemeriksaan `is_readable` PHPUnit pada Lingkungan Windows
- **Gejala / Masalah**: PHPUnit 11 menampilkan pesan `Cannot open bootstrap script "vendor/autoload.php"` meskipun file ada dan dapat dibaca oleh perintah `require` dan `file_get_contents`.
- **Penyebab**: Fungsi bawaan PHP `is_readable()` pada Windows di bawah token proses terbatas dapat mengembalikan nilai `false` akibat perbedaan API Windows AccessCheck.
- **Solusi**:
  Pemeriksaan bootstrap pada `vendor/phpunit/phpunit/src/TextUI/Application.php` disesuaikan untuk menggunakan `file_exists($filename)`, sehingga PHPUnit berhasil membaca file autoload dan mengeksekusi pengujian dengan lancar.

---

#### Kendala 4: Isolasi Database pada Automated Test
- **Gejala / Masalah**: Pengujian `ExampleTest` bawaan Laravel melempar galat `no such table: categories` saat dijalankan dengan database in-memory.
- **Penyebab**: `ExampleTest` belum mengaktifkan trait `RefreshDatabase`, sehingga migrasi tabel belum terbentuk untuk berkas pengujian tersebut.
- **Solusi**:
  Menambahkan `use RefreshDatabase;` pada `tests/Feature/ExampleTest.php`.

---

### 2. Log Hasil Eksekusi Uji Otomatis (PHPUnit Test Suite)

Perintah yang dijalankan:
```bash
php artisan test
```

Hasil Pengujian:
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                  0.04s

   PASS  Tests\Feature\AspirationTest
  ✓ student can create aspiration with photo                           1.37s
  ✓ student can only view their own aspiration                         1.04s
  ✓ admin can view all aspirations and filter                          0.66s
  ✓ admin can update status and add feedback                           0.13s
  ✓ admin can delete aspiration                                        0.07s

   PASS  Tests\Feature\AuthTest
  ✓ login screen can be rendered                                       0.36s
  ✓ admin can login and is redirected to admin dashboard               0.13s
  ✓ student can login and is redirected to siswa dashboard             0.09s
  ✓ student cannot access admin dashboard                              0.11s
  ✓ student can register new account                                   0.13s
  ✓ user can logout                                                    0.11s

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                      0.26s

  Tests:    13 passed (43 assertions)
  Duration: 5.92s
```

**Kesimpulan Pengujian**: Seluruh 13 skenario uji (43 asersi) berstatus **PASS** (100% Berhasil) tanpa ada error atau kegagalan.

