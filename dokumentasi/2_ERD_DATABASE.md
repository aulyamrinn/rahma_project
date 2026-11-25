# ENTITY RELATIONSHIP DIAGRAM (ERD) & STRUKTUR TABEL DATABASE
## Sistem Pengaduan Sarana dan Prasarana Sekolah (SchoolCare)

---

### 1. Diagram ERD (Mermaid)

```mermaid
erDiagram
    USERS ||--o{ ASPIRATIONS : "mengajukan (1:N)"
    USERS ||--o{ FEEDBACK : "memberikan tanggapan (1:N)"
    USERS ||--o{ ASPIRATION_STATUS_HISTORIES : "mencatat perubahan (1:N)"
    CATEGORIES ||--o{ ASPIRATIONS : "mengelompokkan (1:N)"
    ASPIRATIONS ||--o{ FEEDBACK : "memiliki respon (1:N)"
    ASPIRATIONS ||--o{ ASPIRATION_STATUS_HISTORIES : "memiliki riwayat (1:N)"

    USERS {
        bigint id PK
        string name
        string email UK
        string role "admin | siswa"
        string nisn UK "nullable"
        string kelas "nullable"
        string phone "nullable"
        string avatar "nullable"
        string password "hashed"
        timestamp created_at
        timestamp updated_at
    }

    CATEGORIES {
        bigint id PK
        string name UK
        string slug UK
        text description "nullable"
        string icon "nullable"
        boolean is_active "default: true"
        timestamp created_at
        timestamp updated_at
    }

    ASPIRATIONS {
        bigint id PK
        string ticket_number UK "e.g. SC-20261112-1001"
        bigint user_id FK "users.id (ON DELETE CASCADE)"
        bigint category_id FK "categories.id (ON DELETE RESTRICT)"
        string title
        string location
        text description
        string photo "nullable"
        enum status "Menunggu | Diproses | Selesai | Ditolak"
        timestamp created_at
        timestamp updated_at
    }

    FEEDBACK {
        bigint id PK
        bigint aspiration_id FK "aspirations.id (ON DELETE CASCADE)"
        bigint user_id FK "users.id (ON DELETE CASCADE)"
        text response
        timestamp created_at
        timestamp updated_at
    }

    ASPIRATION_STATUS_HISTORIES {
        bigint id PK
        bigint aspiration_id FK "aspirations.id (ON DELETE CASCADE)"
        bigint user_id FK "users.id (ON DELETE SET NULL)"
        string previous_status "nullable"
        string new_status
        text notes "nullable"
        timestamp created_at
        timestamp updated_at
    }
```

---

### 2. Deskripsi Relasi Antar Tabel

1. **`users` -> `aspirations` (One-to-Many)**:
   - Satu pengguna (siswa) dapat mengajukan banyak pengaduan sarana.
   - Kunci relasi: `aspirations.user_id` mereferensikan `users.id` dengan aturan `ON DELETE CASCADE`.

2. **`categories` -> `aspirations` (One-to-Many)**:
   - Satu kategori sarana mencakup banyak pengaduan fasilitas.
   - Kunci relasi: `aspirations.category_id` mereferensikan `categories.id` dengan aturan `ON DELETE RESTRICT` untuk mencegah terhapusnya kategori yang sedang digunakan oleh data aspirasi.

3. **`aspirations` -> `feedback` (One-to-Many)**:
   - Satu pengaduan sarana dapat memiliki beberapa tanggapan atau instruksi berkala dari administrator.
   - Kunci relasi: `feedback.aspiration_id` mereferensikan `aspirations.id` dengan aturan `ON DELETE CASCADE`.
   - `feedback.user_id` mereferensikan `users.id` (administrator yang memberikan feedback).

4. **`aspirations` -> `aspiration_status_histories` (One-to-Many)**:
   - Setiap pengaduan memiliki histori audit trail setiap kali terjadi perubahan status (`Menunggu` -> `Diproses` -> `Selesai` / `Ditolak`).
   - Kunci relasi: `aspiration_status_histories.aspiration_id` mereferensikan `aspirations.id` dengan aturan `ON DELETE CASCADE`.
   - `aspiration_status_histories.user_id` mereferensikan `users.id` dengan aturan `ON DELETE SET NULL` agar riwayat tetap tersimpan utuh meskipun akun pengubah dihapus di kemudian hari.

