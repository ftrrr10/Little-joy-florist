# Perancangan Sistem Informasi Pemesanan Bunga Berbasis Web pada Usaha Florist Little Joy Jakarta

Repositori ini berisi kode sumber lengkap untuk aplikasi **Sistem Informasi Pemesanan Bunga Little Joy Jakarta**, yang dikembangkan sebagai proyek tugas akhir (Skripsi). Aplikasi ini dirancang untuk menggantikan alur pemesanan manual via WhatsApp menjadi sistem berbasis web terintegrasi yang andal, aman, dan memiliki antarmuka premium dengan bahasa desain **Botanical Heritage**.

Sistem ini menerapkan arsitektur **Full Laravel Blade Monolith** modern, menggunakan **Blade** di sisi *frontend* dikombinasikan dengan **Alpine.js** untuk interaktivitas reaktif, dan **Vite & Tailwind CSS** untuk kompilasi gaya visual premium yang responsif.

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
│   ├── Models/                      # Representasi Tabel Database & Relasi Eloquent
│   ├── Policies/                    # Kebijakan Otorisasi Keamanan Hak Akses Data
│   └── Services/                    # Logika Bisnis Kompleks & Transaksi Database
├── database/
│   ├── migrations/                  # Berkas Migrasi Skema Tabel Database Fisik
│   └── seeders/                     # Seeder Data Awal untuk Demo Pengujian
├── docs/
│   └── bab-3/                       # Aset Dokumentasi Lengkap BAB III Diagram Skripsi
│       ├── activity/                # Sumber PlantUML Diagram Aktivitas (Laju Kerja)
│       ├── architecture/            # Sumber PlantUML Class & Deployment Diagram
│       ├── database/                # Sumber ERD (Mermaid), LRS (PlantUML), & DBML
│       ├── navigation/              # Sumber Sitemap (Mermaid) & User Flow (PlantUML)
│       ├── ui/                      # Spesifikasi Wireframes Antarmuka
│       ├── exported/                # Folder Penampung Aset Gambar Grafik Hasil Ekspor
│       ├── use-case/                # Sumber Use Case Diagram & Deskripsi Tekstual
│       ├── diagram-explanations.md  # Narasi Penjelasan Akademis Indonesia Setiap Diagram
│       └── use-case-descriptions.md # Deskripsi Tekstual Terstruktur 12 Use Case Utama
├── resources/
│   ├── css/                          # Kustomisasi Gaya Visual (Botanical Heritage tokens)
│   ├── js/                           # Script Utama (Integrasi AlpineJS, Axios, Animate on Scroll)
│   └── views/                        # Template Halaman Utama (Blade)
│       ├── admin/                    # Dashboard, Kelola Staf, Pelanggan, Produk, Kategori, Laporan
│       ├── operator/                 # Dashboard Operator, Antrean Pesanan, Kelola Kartu Stok
│       ├── customer/                 # Profil, Riwayat Transaksi, Unggah Bayar, Cart, Checkout
│       ├── public/                   # Landing Page (Home), Katalog Florist, Tentang, Kontak
│       ├── auth/                     # Form Login, Registrasi, Lupa/Reset Password
│       ├── components/               # Komponen UI Reusable (Navbar, Footer, Button, Inputs)
│       └── layouts/                  # Tata Letak Grid & Panel Dashboard Dashboard Layout
├── routes/
│   ├── web.php                      # Pemetaan Rute URL Aplikasi & Otorisasi Peran
│   └── auth.php                     # Rute Autentikasi Pengguna & Reset Password
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
Jalankan npm untuk memasang paket frontend dan pustaka Alpine.js:
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
*Perintah ini akan secara otomatis membuat 10 tabel fisik utama dan menanamkan data 1 Administrator, 2 Operator, 5 Pelanggan, 5 Kategori, 20 Produk Bunga, serta beberapa pesanan awal, log histori, dan kartu stok mutasi. Selain itu, sistem seeder secara otomatis menyalin gambar produk asli dari folder aset seeder dan membuat gambar struk bukti transfer BCA/Mandiri tiruan yang realistis di dalam penyimpanan lokal (`storage/app/public`) agar aplikasi langsung berfungsi dengan gambar yang lengkap tanpa perlu menyalin file secara manual.*

### Langkah 8: Kompilasi Aset Frontend (Vite)
Jalankan build Vite untuk mengompilasi CSS dan JS untuk pertama kalinya:
```bash
npm run build
```

### Langkah 9: Jalankan Server Pengembangan
Jalankan server PHP Laravel lokal:
```bash
php artisan serve
```
*Aplikasi akan dapat diakses di browser melalui alamat: `http://127.0.0.1:8000`*

*(Opsional)* Jika Anda ingin melakukan perubahan pada file CSS atau JS dan ingin dikompilasi secara otomatis, Anda dapat menjalankan Vite Watcher di terminal terpisah:
```bash
npm run dev
```

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

## 4. Hal-Hal Penting yang Wajib Diperhatikan

Untuk menghindari kegagalan sistem saat pengujian atau demonstrasi, perhatikan beberapa aturan teknis yang telah diterapkan:

1.  **Pengunduhan Aset & Build**:
    *   Karena proyek ini bermigrasi sepenuhnya ke arsitektur monolitik Blade, tidak perlu lagi menjalankan server Node.js terpisah saat aplikasi diuji di staging. Jalankan `npm run build` sekali untuk menyusun aset static produksi, dan server PHP siap melayani seluruh website.
2.  **Penguncian Stok Transaksional**:
    *   Sistem menggunakan fitur `lockForUpdate()` pada level database saat proses *checkout* dan *verifikasi pembayaran*. Hal ini mencegah terjadinya *oversold* (bunga terjual melebihi stok fisik) ketika ada dua transaksi pemesanan konkuren yang masuk secara bersamaan.
3.  **Snapshot Riwayat Transaksi**:
    *   Ketika pesanan dibuat, sistem menyalin nama produk dan harga satuan ke tabel `order_items` sebagai *snapshot*. Jika Admin mengubah harga produk atau menghapus produk secara logis (*soft delete*) di kemudian hari, data invoice historis pesanan lama milik pelanggan tidak akan pernah berubah atau rusak.
4.  **Unggah Bukti Transfer**:
    *   Berkas gambar bukti transfer dibatasi maksimal **2 MB** dengan ekstensi gambar yang valid (`jpg`, `jpeg`, `png`, `webp`). 
    *   Jika pelanggan melakukan unggah ulang bukti transfer baru (misal karena bukti transfer pertama ditolak oleh operator), sistem secara otomatis menghapus berkas gambar bukti lama dari disk server untuk menghemat kapasitas penyimpanan.
5.  **Penonaktifan Akun**:
    *   Admin dapat menonaktifkan status keaktifan pelanggan maupun operator (`is_active = false`). Pengguna dengan status tidak aktif akan ditolak login-nya oleh sistem secara otomatis dan sesinya langsung dihapus.
6.  **Aset Gambar & Bukti Pembayaran Otomatis**:
    *   Untuk memudahkan kolaborasi antar pengembang, seeder secara otomatis menyalin aset gambar produk yang tersimpan di `database/seeders/assets/products` ke dalam folder penyimpanan publik lokal (`storage/app/public/products`).
    *   Jika gambar bukti transfer (`sample_proof.jpg`) belum tersedia di penyimpanan lokal, seeder akan mendeteksi ketersediaan ekstensi PHP GD dan secara otomatis menggambar struk transfer bank tiruan yang sangat realistis di folder `proofs`. Ini menjamin halaman verifikasi pembayaran pada panel operator tidak pernah menampilkan gambar pecah saat pertama kali dijalankan.

---

## 5. Pemanfaatan Berkas BAB III Skripsi

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
