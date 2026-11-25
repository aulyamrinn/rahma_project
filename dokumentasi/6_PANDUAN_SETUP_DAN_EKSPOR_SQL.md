# PANDUAN SETUP DATABASE, MENJALANKAN APLIKASI, DAN EKSPOR FILE .SQL
## Aplikasi Pengaduan Sarana Sekolah (SchoolCare)

---

### 1. Menyiapkan Database MySQL (`db_schoolcare`)

Aplikasi telah dikonfigurasi untuk menggunakan database MySQL bernama `db_schoolcare`.

#### Langkah A: Nyalakan Layanan MySQL
- **Jika Menggunakan XAMPP**:
  1. Buka aplikasi **XAMPP Control Panel**.
  2. Klik tombol **Start** pada modul **Apache** dan **MySQL**.
  3. Pastikan status modul MySQL berwarna hijau (Port default: `3306`).
- **Jika Menggunakan Laragon**:
  1. Buka **Laragon**.
  2. Klik tombol **Start All**.

---

#### Langkah B: Opsi 1 - Jalankan Migrasi & Seeder via Terminal
Setelah MySQL aktif, jalankan perintah berikut di PowerShell / Terminal proyek:
```bash
# 1. Buat database dan tabel serta isi data bawaan:
php artisan migrate:fresh --seed
```
*Catatan*: Jika database `db_schoolcare` belum ada, Laravel akan menanyakan:
`"Database db_schoolcare does not exist on the mysql connection. Would you like to create it?"`
Tekan **Enter** atau ketik `yes`.

---

#### Langkah C: Opsi 2 - Impor File SQL Melalui phpMyAdmin
File SQL lengkap telah disediakan pada:
`database/db_schoolcare.sql`

Cara impor via phpMyAdmin:
1. Buka browser dan kunjungi `http://localhost/phpmyadmin`.
2. Klik tab **Databases / Basis Data**, buat basis data baru bernama: `db_schoolcare`.
3. Klik database `db_schoolcare` di panel sebelah kiri.
4. Pilih tab **Import / Impor** di menu bagian atas.
5. Klik **Choose File** dan pilih berkas:
   `C:\Users\Anindia WelanLestari\Downloads\pengaduan-sarana-sekolah\rahma_project\database\db_schoolcare.sql`
6. Gulir ke bawah dan klik tombol **Import / Kirim**.

---

### 2. Menjalankan Aplikasi Web

Jalankan perintah berikut pada terminal proyek:
```bash
php artisan serve
```

Aplikasi dapat dibuka melalui browser di alamat:
👉 **`http://localhost:8000`**

---

### 3. Akun Pengujian UKK (Demo Credentials)

Sistem telah dilengkapi data awal (*seeder*) siap pakai untuk penguji UKK:

| Peran (Role) | Email / Akun | NISN | Kata Sandi | Deskripsi |
|:---|:---|:---|:---|:---|
| **Administrator** | `admin@schoolcare.sch.id` | - | `password` | Mengelola seluruh pengaduan, filter, ubah status, & beri umpan balik |
| **Siswa 1** | `ahmad@siswa.schoolcare.sch.id` | `20241001` | `password` | Siswa XII RPL 1 (Memiliki pengaduan aktif) |
| **Siswa 2** | `siti@siswa.schoolcare.sch.id` | `20241002` | `password` | Siswa XI TKJ 2 |
| **Siswa 3** | `budi@siswa.schoolcare.sch.id` | `20241003` | `password` | Siswa XII MM 1 |

> 💡 **Fitur Tambahan**: Pada halaman Login, telah disediakan tombol **"Isi Otomatis"** untuk akun Admin dan Siswa agar mempermudah penguji UKK tanpa harus mengetik secara manual.

---

### 4. Panduan Ekspor Database Menjadi File .sql (Sesuai Ketentuan UKK)

Setelah aplikasi diuji dan terdapat data pengaduan baru yang ditambahkan, penguji atau siswa dapat mengekspor database menjadi file `.sql` menggunakan salah satu dari dua metode di bawah ini:

#### Metode 1: Menggunakan phpMyAdmin (GUI)
1. Buka `http://localhost/phpmyadmin` di browser.
2. Klik database `db_schoolcare` di panel sebelah kiri.
3. Klik tab **Export / Ekspor** di navigasi atas.
4. Pada opsi *Export Method*, pilih **Quick (Cepat)**.
5. Pada format, pastikan memilih **SQL**.
6. Klik tombol **Export / Ekspor**.
7. File `.sql` otomatis terunduh ke komputer Anda.

#### Metode 2: Menggunakan Terminal / CMD (mysqldump)
Buka terminal dan jalankan perintah:
```bash
# Untuk XAMPP di Windows:
C:\xampp\mysql\bin\mysqldump -u root -p db_schoolcare > database_schoolcare_export.sql

# Untuk Laragon di Windows:
C:\laragon\bin\mysql\current\bin\mysqldump -u root db_schoolcare > database_schoolcare_export.sql
```
*(Tekan Enter saat diminta password jika user root tidak menggunakan password)*.

