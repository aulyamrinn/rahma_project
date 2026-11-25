# DOKUMENTASI FUNGSI, PROSEDUR, DAN STRUKTUR KODE
## Sistem Pengaduan Sarana dan Prasarana Sekolah (SchoolCare)

---

### 1. Struktur Pengendali (Controllers)

#### A. `App\Http\Controllers\AuthController`
Mengelola seluruh siklus hidup autentikasi pengguna:
1. `showLoginForm()`:
   - **Tipe**: Prosedur Tampilan (GET).
   - **Deskripsi**: Menampilkan halaman form login. Jika pengguna telah terautentikasi, otomatis dialihkan (*redirect*) ke dashboard yang sesuai peran (*role*).
2. `login(Request $request)`:
   - **Tipe**: Prosedur Otentikasi (POST).
   - **Validasi Input**:
     - `login`: `['required', 'string']` (Mendukung identifikasi dinamis Email atau NISN siswa).
     - `password`: `['required', 'string']`.
   - **Logika**: Memeriksa `FILTER_VALIDATE_EMAIL`. Jika valid email maka dicari kolom `email`, jika tidak maka dicari kolom `nisn`. Jika lolos `Auth::attempt()`, sesi diregenerasi untuk mencegah *Session Fixation*, kemudian diarahkan ke `admin.dashboard` jika `role == 'admin'` atau `siswa.dashboard` jika `role == 'siswa'`.
3. `showRegisterForm()`:
   - **Tipe**: Prosedur Tampilan (GET).
   - **Deskripsi**: Menampilkan halaman registrasi akun siswa baru.
4. `register(Request $request)`:
   - **Tipe**: Prosedur Registrasi (POST).
   - **Validasi Input**:
     - `name`: `['required', 'string', 'max:100']`
     - `nisn`: `['required', 'numeric', 'digits_between:5,15', 'unique:users,nisn']`
     - `kelas`: `['required', 'string', 'max:50']`
     - `email`: `['required', 'string', 'email', 'max:150', 'unique:users,email']`
     - `phone`: `['nullable', 'string', 'max:20']`
     - `password`: `['required', 'string', 'min:6', 'confirmed']`
   - **Logika**: Menyimpan pengguna baru dengan *role* baku `siswa` dan melakukan *hashing* password menggunakan Bcrypt (`Hash::make()`), kemudian melakukan *auto-login* dan mengalihkan ke `siswa.dashboard`.
5. `logout(Request $request)`:
   - **Tipe**: Prosedur Pengakhiran Sesi (POST).
   - **Logika**: Menjalankan `Auth::logout()`, menginvalasi sesi `session()->invalidate()`, meregenerasi token CSRF `session()->regenerateToken()`, dan mengalihkan ke rute login.

---

#### B. `App\Http\Controllers\Admin\DashboardController`
1. `index()`:
   - **Tipe**: Fungsi Query & Pengolahan Data Statistik (GET).
   - **Prosedur Pengolahan Array**:
     - Menghitung agregasi data menggunakan `Aspiration::count()` dan filter status `Menunggu`, `Diproses`, `Selesai`, dan `Ditolak`.
     - Mengolah *Category Summary* melalui pemetaan koleksi array (`Collection::map`) untuk menghasilkan nama kategori, jumlah pengaduan, dan persentase numerik.
     - Mengolah data tren bulanan tahun berjalan menggunakan perulangan array 12 bulan (`$monthlyStats[]`) sesuai ketentuan UKK dalam pemanfaatan struktur array.
   - **Keluaran**: View `admin.dashboard` dengan variabel terikat.

---

#### C. `App\Http\Controllers\Admin\AspirationController`
1. `index(Request $request)`:
   - **Tipe**: Fungsi Penelusuran & Penyaringan Multi-Kriteria (GET).
   - **Parameter Filter**:
     - `q`: Pencarian *fulltext* pada tiket, judul, lokasi, deskripsi, nama siswa, atau kelas.
     - `status`: Filter berdasarkan enum status (`Menunggu`, `Diproses`, `Selesai`, `Ditolak`).
     - `category_id`: Filter berdasarkan relasi ID kategori.
     - `user_id`: Filter pengaduan milik siswa tertentu.
     - `date`: Filter tanggal spesifik pengaduan (`created_at` YYYY-MM-DD).
     - `month`: Filter bulan dan tahun pengaduan (`created_at` YYYY-MM).
   - **Keluaran**: View `admin.aspirations.index` dengan hasil `paginate(10)->withQueryString()`.
2. `show($id)`:
   - **Tipe**: Prosedur Tinjauan Detail (GET).
   - **Logika**: *Eager loading* relasi `['user', 'category', 'feedbacks.user', 'statusHistories.user']`.
3. `updateStatus(Request $request, $id)`:
   - **Tipe**: Prosedur Pembaruan Status & Perekaman Histori (PUT).
   - **Validasi Input**: `status` in `Menunggu,Diproses,Selesai,Ditolak`, `notes` (nullable), `feedback` (nullable).
   - **Logika**:
     1. Menyimpan status lama (*previous status*).
     2. Memperbarui status baru pada tabel `aspirations`.
     3. Menyisipkan rekaman ke tabel `aspiration_status_histories` sebagai audit trail.
     4. Jika kolom `feedback` diisi, otomatis membuat rekaman baru pada tabel `feedback`.
4. `storeFeedback(Request $request, $id)`:
   - **Tipe**: Prosedur Pengiriman Umpan Balik (POST).
   - **Validasi Input**: `response` required, min: 3 karakter.
   - **Logika**: Menyimpan rekaman tanggapan pada tabel `feedback` dengan referensi `user_id` administrator yang sedang login.
5. `destroy($id)`:
   - **Tipe**: Prosedur Penghapusan Aman (DELETE).
   - **Logika**: Menghapus file foto bukti fisik dari disk penyimpanan `public` jika ada, kemudian menghapus data aspirasi (relasi feedback dan histori terhapus otomatis via *Foreign Key Cascade*).

---

#### D. `App\Http\Controllers\Siswa\DashboardController`
1. `index()`:
   - **Tipe**: Fungsi Agregasi Data Siswa (GET).
   - **Logika**: Membatasi kueri hanya untuk ID pengguna yang sedang aktif (`Auth::id()`), menghitung jumlah aspirasi pribadi (total, menunggu, diproses, selesai, ditolak), mengambil 5 pengaduan terakhir dan 4 umpan balik admin terbaru.

---

#### E. `App\Http\Controllers\Siswa\AspirationController`
1. `index(Request $request)`:
   - **Tipe**: Fungsi Tampilan Daftar Aspirasi Siswa (GET).
   - **Keamanan**: Memastikan klausa `where('user_id', Auth::id())` selalu aktif sehingga siswa tidak dapat melihat aspirasi siswa lain.
2. `create()`:
   - **Tipe**: Prosedur Tampilan Formulir (GET).
   - **Logika**: Memuat 8 kategori sarana yang aktif untuk dijadikan opsi dropdown.
3. `store(Request $request)`:
   - **Tipe**: Prosedur Pembuatan Pengaduan (POST).
   - **Validasi Input**:
     - `title`: `['required', 'string', 'min:5', 'max:150']`
     - `category_id`: `['required', 'exists:categories,id']`
     - `location`: `['required', 'string', 'min:3', 'max:100']`
     - `description`: `['required', 'string', 'min:10', 'max:3000']`
     - `photo`: `['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072']` (Maks. 3 MB)
   - **Logika**:
     1. Mengunggah file foto bukti ke `storage/app/public/aspirations` jika disertakan.
     2. Menyimpan model `Aspiration` (Nomor tiket otomatis ter-generate via *Model Boot Hook* format `SC-YYYYMMDD-XXXX`).
     3. Mencatat histori status awal (`Menunggu`) pada tabel `aspiration_status_histories`.
4. `show($id)`:
   - **Tipe**: Prosedur Tampilan Detail (GET).
   - **Otorisasi Ketat**: Memeriksa `$aspiration->user_id === Auth::id()`. Jika tidak cocok, sistem segera melempar `abort(403, 'Akses ditolak')`.

---

### 2. Middleware Akses: `RoleMiddleware`
- **Lokasi**: `app/Http/Middleware/RoleMiddleware.php`.
- **Alias Rute**: `role`.
- **Fungsi**: Memeriksa otentikasi dan hak akses peran pengguna (`...$roles`).
  - Jika unauthenticated: dialihkan ke halaman login dengan notifikasi.
  - Jika siswa mencoba membuka rute ber-role `admin`: sistem menolak akses dan mengalihkan siswa ke dashboard siswa dengan peringatan flash message.
  - Jika admin membuka rute khusus siswa: dialihkan kembali ke dashboard admin.

