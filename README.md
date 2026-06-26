# Perancangan Sistem Informasi Pemesanan Bunga Berbasis Web pada Usaha Florist Little Joy Jakarta

Repositori ini berisi kode sumber lengkap untuk aplikasi **Sistem Informasi Pemesanan Bunga Little Joy Jakarta**, yang dikembangkan sebagai proyek tugas akhir (Skripsi). Aplikasi ini dirancang untuk menggantikan alur pemesanan manual via WhatsApp menjadi sistem berbasis web terintegrasi yang andal, aman, dan memiliki antarmuka premium dengan bahasa desain **Botanical Heritage**.

Sistem ini menerapkan arsitektur monolitik modern menggunakan **Laravel** di sisi *backend*, **React & TypeScript** di sisi *frontend*, dengan **Inertia.js** sebagai jembatan JSON reaktif yang menghubungkan keduanya secara efisien tanpa memerlukan REST API terpisah.

---

## 1. Struktur Direktori Utama

Berikut adalah pemetaan folder penting di dalam proyek untuk memudahkan navigasi pemeriksaan:

```text
Little-joy-florist/
├── app/                              # Backend Domain Logic (Laravel)
│   ├── Http/
│   │   ├── Controllers/             # Mengontrol Aliran Request & Navigasi Halaman
│   │   │   ├── Admin/               # Dashboard, Laporan, CRUD Kategori & Produk
│   │   │   ├── Operator/            # Dashboard Kerja, Verifikasi Bayar, Kelola Stok
│   │   │   └── ...                  # Katalog, Keranjang, Checkout, Profil (Public/Customer)
│   │   ├── Middleware/              # Otorisasi Hak Akses (Breeze & RoleMiddleware)
│   │   └── Requests/                # Validasi Form Input Sisi Server (Form Requests)
│   ├── Models/                      # representasi Tabel Database & Relasi Eloquent
│   ├── Policies/                    # Kebijakan Otorisasi Keamanan Hak Akses Data
│   └── Services/                    # Logika Bisnis Kompleks & Transaksi Database
├── database/
│   ├── migrations/                  # Berkas Migrasi Skema Tabel Database Fisik
│   └── seeders/                     # Seeder Data Awal untuk Demo Pengujian
├── docs/
│   └── bab-3/                       # Aset Dokumentasi Lengkap BAB III Skripsi
│       ├── activity/                # Sumber PlantUML Diagram Aktivitas (Laju Kerja)
│       ├── architecture/            # Sumber PlantUML Class & Deployment Diagram
│       ├── database/                # Sumber ERD (Mermaid), LRS (PlantUML), & DBML
│       ├── navigation/              # Sumber Sitemap (Mermaid) & User Flow (PlantUML)
│       ├── ui/                      # Spesifikasi Wireframes Antarmuka
│       ├── exported/                # Folder Penampung Aset Gambar Grafik Hasil Ekspor
│       ├── use-case/                # Sumber Use Case Diagram & Deskripsi Tekstual
│       ├── diagram-explanations.md  # Narasi Penjelasan Akademis Indonesia Setiap Diagram
│       └── use-case-descriptions.md # Deskripsi Tekstual Terstruktur 12 Use Case Utama
├── resources/js/                     # Frontend Single Page Application (React + TSX)
│   ├── Components/                  # Komponen UI Reusable (Button, Input, ScrollReveal)
│   ├── Layouts/                     # Tata Letak Halaman (PublicLayout & DashboardLayout)
│   ├── Pages/                       # Halaman Antarmuka Utama Berdasarkan Portal Peran
│   │   ├── Public/                  # Beranda, Katalog, Detail Bunga, Tentang Kami
│   │   ├── Auth/                    # Login, Registrasi, Lupa Password
│   │   ├── Customer/                # Keranjang, Checkout, Unggah Bayar, Riwayat Order
│   │   ├── Operator/                # Dashboard Kerja Staf & Antrean Pesanan
│   │   └── Admin/                   # Dashboard Analitik, Kelola Staf/Pelanggan, Laporan
│   └── types/                       # Deklarasi Tipe Data Statis TypeScript
├── routes/
│   └── web.php                      # Pemetaan Rute URL Aplikasi & Otorisasi Peran
└── tests/Feature/                   # Berkas Pengujian Fitur Backend (PHPUnit)
```

---

## 2. Panduan Instalasi & Pengoperasian Lokal (Setelah Clone)

Ikuti langkah-langkah terstruktur berikut untuk menjalankan aplikasi di lingkungan pengembangan lokal (*local development*):

### Persyaratan Sistem
*   **PHP** >= 8.2 (Sangat direkomendasikan menggunakan Laravel Herd atau Laragon)
*   **Composer** >= 2.0
*   **Node.js** >= 18.0 (LTS) & **npm**
*   **MySQL Server** >= 8.0

### Langkah 1: Kloning Repositori
Buka terminal dan jalankan perintah kloning:
```bash
git clone https://github.com/username/Little-joy-florist.git
cd Little-joy-florist
```

### Langkah 2: Instalasi Dependensi PHP (Backend)
Jalankan composer untuk mengunduh seluruh pustaka backend Laravel:
```bash
composer install
```

### Langkah 3: Instalasi Dependensi Javascript (Frontend)
Jalankan npm untuk memasang seluruh paket frontend React dan dependensi kompilasi:
```bash
npm install
```

### Langkah 4: Konfigurasi Berkas Lingkungan (.env)
1.  Salin berkas `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
2.  Buka berkas `.env` pada editor teks Anda dan sesuaikan konfigurasi database MySQL lokal:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=little_joy_florist
    DB_USERNAME=root
    DB_PASSWORD=             # Kosongkan jika menggunakan XAMPP default / sesuaikan
    ```
3.  Buat database baru bernama `little_joy_florist` pada server MySQL lokal Anda menggunakan phpMyAdmin atau aplikasi administrasi database pilihan Anda.

### Langkah 5: Generate Application Key
Jalankan perintah berikut untuk menghasilkan kunci enkripsi aplikasi yang aman:
```bash
php artisan key:generate
```

### Langkah 6: Hubungkan Link Penyimpanan (Sangat Penting)
Aplikasi ini menyimpan foto produk dan foto bukti transfer bank pada direktori penyimpanan lokal. Anda **wajib** membuat tautan simbolik agar file media dapat diakses oleh browser:
```bash
php artisan storage:link
```

### Langkah 7: Migrasi Tabel & Seeding Data
Jalankan perintah migrasi untuk membuat seluruh tabel database dan mengisinya dengan data awal tiruan (*mock data*) untuk demonstrasi pengujian:
```bash
php artisan migrate:fresh --seed
```
*Perintah ini akan secara otomatis membuat 10 tabel fisik utama dan menanamkan data 1 Administrator, 2 Operator, 5 Pelanggan, 5 Kategori, 20 Produk Bunga, serta beberapa pesanan awal, log histori, dan kartu stok mutasi.*

### Langkah 8: Jalankan Server Pengembangan
Anda harus menjalankan **dua server secara bersamaan** di terminal terpisah:

1.  **Terminal 1 (Server PHP Laravel)**:
    ```bash
    php artisan serve
    ```
    *Aplikasi akan dapat diakses di browser melalui alamat: `http://127.0.0.1:8000`*

2.  **Terminal 2 (Kompiler Aset Vite)**:
    ```bash
    npm run dev
    ```
    *Perintah ini mengaktifkan Hot Module Replacement (HMR) untuk pembaruan antarmuka React secara real-time.*

---

## 3. Data Kredensial untuk Pengujian Demo (Seeded Accounts)

Gunakan akun-akun berikut yang telah terdaftar di database hasil seeding untuk menguji alur kerja masing-masing peran (*role*). Seluruh akun menggunakan password default: **`password`**

| Nama Pengguna | Alamat Email | Peran (*Role*) | Deskripsi Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin Little Joy** | `admin@littlejoy.com` | `admin` | Memiliki kontrol penuh sistem: CRUD produk, kategori, inventori, mengelola operator staf, melihat pelanggan, dan laporan keuangan komprehensif. |
| **Operator Satu** | `operator1@littlejoy.com` | `operator` | Staf pengerjaan: Melakukan persetujuan/penolakan bukti transfer pelanggan, mengelola penyesuaian stok bunga, dan memperbarui status pengerjaan pesanan. |
| **Operator Dua** | `operator2@littlejoy.com` | `operator` | Staf pengerjaan: Memiliki kapabilitas operasional yang sama dengan Operator Satu. |
| **Ahmad Rian** | `customer1@gmail.com` | `customer` | Pelanggan Utama: Menjelajahi katalog, mengelola keranjang, melakukan checkout, mengunggah bukti bayar transfer bank, dan memantau timeline status pesanan. |
| **Siti Aminah** | `customer2@gmail.com` | `customer` | Pelanggan Utama: Memiliki hak akses transaksi pelanggan yang sama. |

---

## 4. Prosedur Pengujian Otomatis (Testing)

Aplikasi ini dilengkapi dengan pengujian fitur otomatis komprehensif (*automated feature tests*) berbasis PHPUnit untuk memastikan integritas logika bisnis.

### Persiapan Database Uji
1.  Buat database tambahan bernama `little_joy_florist_testing` pada server MySQL lokal Anda.
2.  Jalankan perintah pengujian di terminal:
    ```bash
    php artisan test
    ```
    *Sistem akan menjalankan **82 pengujian fitur** (420 assertions) yang mencakup validasi registrasi/login, otorisasi peran, keranjang belanja, proses checkout transaksional, pencegahan balapan stok (*race conditions*), unggah bukti bayar, verifikasi/penolakan operator, penyesuaian kartu stok, dan laporan filter penjualan.*

---

## 5. Hal-Hal Penting yang Wajib Diperhatikan

Untuk menghindari kegagalan sistem saat pengujian atau demonstrasi, perhatikan beberapa aturan teknis yang telah diterapkan:

1.  **Penguncian Stok Transaksional**:
    *   Sistem menggunakan fitur `lockForUpdate()` pada level database saat proses *checkout* dan *verifikasi pembayaran*. Hal ini mencegah terjadinya *oversold* (bunga terjual melebihi stok fisik) ketika ada dua transaksi pemesanan konkuren yang masuk secara bersamaan.
2.  **Snapshot Riwayat Transaksi**:
    *   Ketika pesanan dibuat, sistem menyalin nama produk dan harga satuan ke tabel `order_items` sebagai *snapshot*. Jika Admin mengubah harga produk atau menghapus produk secara logis (*soft delete*) di kemudian hari, data invoice historis pesanan lama milik pelanggan tidak akan pernah berubah atau rusak.
3.  **Unggah Bukti Transfer**:
    *   Berkas gambar bukti transfer dibatasi maksimal **2 MB** dengan ekstensi gambar yang valid (`jpg`, `jpeg`, `png`, `webp`). 
    *   Jika pelanggan melakukan unggah ulang bukti transfer baru (misal karena bukti transfer pertama ditolak oleh operator), sistem secara otomatis menghapus berkas gambar bukti lama dari disk server untuk menghemat kapasitas penyimpanan.
4.  **Penonaktifan Akun**:
    *   Admin dapat menonaktifkan status keaktifan pelanggan maupun operator (`is_active = false`). Pengguna dengan status tidak aktif akan ditolak login-nya oleh sistem secara otomatis dan sesinya langsung dihapus.

---

## 6. Pemanfaatan Berkas BAB III Skripsi

Seluruh berkas pemodelan diagram dan dokumen deskripsi akademis disimpan di dalam direktori `docs/bab-3/`.

*   **Berkas Sumber Diagram**: Ditulis menggunakan format berbasis teks deklaratif yang sangat bersih:
    *   UML Diagram (Aktivitas, Use Case, Sequence, Class, LRS, Deployment, Component, User Flow) ditulis dalam format **PlantUML** (`.puml`).
    *   Entity Relationship Diagram (ERD) & Sitemap ditulis dalam format **Mermaid** (`.mmd`).
    *   Rancangan Skema Database fisik ditulis dalam format **DBML** (`.dbml`).
*   **Cara Visualisasi & Ekspor**:
    *   Anda dapat memasang ekstensi *PlantUML* dan *Mermaid* pada editor VS Code untuk melihat gambar diagram secara langsung.
    *   Sebagai alternatif, Anda dapat menyalin seluruh isi teks berkas sumber tersebut dan menempelkannya ke situs editor daring gratis seperti:
        *   [PlantText](https://www.planttext.com/) (untuk diagram `.puml`).
        *   [Mermaid Live Editor](https://mermaid.live/) (untuk diagram `.mmd`).
        *   [dbdiagram.io](https://dbdiagram.io/) (untuk skema `.dbml`).
    *   Gunakan opsi ekspor ke format **SVG** atau **PNG** beresolusi tinggi pada alat tersebut agar gambar diagram tampak tajam dan tidak pecah saat dimasukkan ke dalam dokumen Microsoft Word skripsi Anda.
