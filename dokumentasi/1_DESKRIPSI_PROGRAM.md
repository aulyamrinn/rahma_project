# DOKUMENTASI SISTEM PENGADUAN SARANA DAN PRASARANA SEKOLAH (SCHOOLCARE)
## Standar Uji Kompetensi Keahlian (UKK) Rekayasa Perangkat Lunak

---

### 1. Profil Aplikasi
- **Nama Aplikasi**: SCHOOLCARE (Sistem Pengaduan Sarana dan Prasarana Sekolah)
- **Teknologi**: Laravel 12, PHP 8.2, MySQL 8 / MariaDB, Bootstrap 5.3, JavaScript, HTML5, CSS3.
- **Arsitektur**: Model-View-Controller (MVC) terstruktur dengan routing terpusat dan middleware role-based.
- **Tujuan Sistem**: Menyediakan media pelaporan dan penanganan kerusakan fasilitas sekolah secara cepat, transparan, terukur, dan dapat dipantau langsung oleh siswa serta dikelola secara komprehensif oleh administrator sarana dan prasarana (Sarpras).

---

### 2. Sasaran Pengguna (Role Pengguna)
Aplikasi memiliki 2 (dua) hak akses utama:
1. **Administrator (Admin Sarpras)**:
   - Memantau ringkasan statistik seluruh pengaduan sarana di dashboard.
   - Mengelola dan meninjau seluruh aspirasi yang diajukan oleh siswa.
   - Melakukan pencarian dan penyaringan (filter) data berdasarkan kata kunci, tanggal, bulan, kategori sarana, dan siswa pelapor.
   - Memeriksa foto bukti kerusakan fasilitas sarana.
   - Memperbarui status penanganan (`Menunggu`, `Diproses`, `Selesai`, `Ditolak`) disertai catatan log.
   - Memberikan tanggapan dan arahan resmi (umpan balik / feedback) kepada siswa.
   - Menelusuri seluruh jejak perubahan status (histori pengaduan).
   - Menghapus pengaduan spam atau duplikat dengan konfirmasi keamanan.

2. **Siswa (Pelapor Fasilitas)**:
   - Melakukan pendaftaran akun mandiri dengan NISN, kelas, email, dan kata sandi.
   - Masuk (login) menggunakan email atau NISN dan kata sandi yang telah di-hash.
   - Memantau dashboard pribadi dengan statistik jumlah pengaduan yang diajukan.
   - Mengisi formulir pengaduan sarana baru dengan:
     - Judul pengaduan.
     - Kategori sarana (8 kategori terstandar).
     - Lokasi spesifik fasilitas.
     - Deskripsi rinci kerusakan/kendala.
     - Upload foto bukti (opsional, batas 3 MB).
   - Memantau progres tahapan perbaikan fasilitas melalui *interactive progress stepper*.
   - Membaca umpan balik serta tindak lanjut dari admin sarpras secara real-time.
   - Memastikan privasi data: Siswa hanya dapat melihat dan mengakses pengaduannya sendiri.

---

### 3. Delapan Kategori Sarana & Prasarana
Sesuai instrumen UKK RPL, sistem menyediakan 8 (delapan) kategori utama:
1. **Fasilitas Kelas**: Kerusakan meja, kursi, papan tulis, proyektor, AC, kipas angin, dan lampu kelas.
2. **Kebersihan**: Masalah tempat sampah, kebersihan lingkungan taman, dan saluran sanitasi.
3. **Toilet**: Kerusakan kran air, kunci/selot pintu, wastafel bocor, closet, dan saluran pembuangan.
4. **Laboratorium**: Kendala PC komputer lab, perangkat LAN, instalasi listrik, dan alat praktikum.
5. **Perpustakaan**: Fasilitas ruang baca, sirkulasi udara AC, rak buku, dan sarana literasi.
6. **Lapangan**: Fasilitas lapangan olahraga, tiang gawang futsal, ring basket, dan net voli.
7. **Internet**: Akses hotspot Wi-Fi sekolah, koneksi internet lambat, dan kabel jaringan LAN.
8. **Lainnya**: Fasilitas pendukung sekolah lainnya yang belum tercakup di kategori utama.

---

### 4. Siklus Status Pengaduan (Workflow)
Setiap pengaduan memiliki 4 (empat) status yang tercatat dalam audit trail `aspiration_status_histories`:
1. **Menunggu**: Pengaduan baru saja diajukan oleh siswa dan menunggu verifikasi awal dari pihak sarpras.
2. **Diproses**: Pengaduan telah diverifikasi, teknisi sarpras ditugaskan, atau suku cadang sedang diadakan.
3. **Selesai**: Perbaikan fisik telah tuntas diselesaikan dan fasilitas telah berfungsi kembali secara normal.
4. **Ditolak**: Pengaduan ditolak jika tidak relevan, fasilitas di luar kewenangan sekolah, atau laporan fiktif.

