# Panduan Penjelasan Akademis Diagram (BAB III Skripsi)

Dokumen ini memuat narasi penjelasan akademik formal dalam Bahasa Indonesia untuk setiap diagram sistem **Little Joy Florist Jakarta**. Penulisan narasi ini disusun menggunakan tata bahasa baku yang disesuaikan untuk naskah skripsi program studi Informatika / Sistem Informasi.

---

## 1. Activity Diagram - Sistem Berjalan (Manual via WhatsApp)

*   **Tujuan Diagram**: Menggambarkan alur kerja aktual pemesanan bunga secara manual yang saat ini berjalan pada toko bunga Little Joy Jakarta sebelum menerapkan sistem berbasis web.
*   **Aktor yang Terlibat**:
    1.  **Pelanggan**: Pihak eksternal yang melakukan interaksi pembelian.
    2.  **Admin Florist**: Staf internal toko yang melayani chat dan merekap data transaksi secara manual.
*   **Proses Utama**:
    1.  Pelanggan memulai percakapan via WhatsApp untuk menanyakan info produk.
    2.  Admin mencari info ketersediaan bunga dan harga pada spreadsheet/buku catatan fisik.
    3.  Pencatatan rincian pengiriman, pengiriman instruksi nomor rekening, dan penerimaan foto bukti pembayaran dilakukan sepenuhnya di dalam ruang obrolan WhatsApp.
    4.  Admin memverifikasi uang masuk melalui aplikasi mobile banking terpisah sebelum bunga dirangkai dan diserahkan ke kurir.
*   **Keputusan Penting (Decision Points)**:
    *   *Apakah produk tersedia?* Jika tidak, pelanggan membatalkan pesanan.
    *   *Apakah transfer valid?* Jika tidak masuk pada rekening bank, pesanan dihentikan sementara sampai pelanggan memberikan klarifikasi atau mentransfer ulang.
*   **Hubungan dengan Sistem yang Diusulkan**: Diagram ini bertindak sebagai basis analisis kebutuhan (*requirements analysis*) untuk mengidentifikasi inefisiensi, seperti risiko kesalahan pencatatan manual, lambatnya konfirmasi stok, dan tidak adanya integrasi verifikasi pembayaran.

---

## 2. Activity Diagram - Sistem yang Diusulkan (Web-Based)

*   **Tujuan Diagram**: Menggambarkan usulan alur kerja baru yang memanfaatkan sistem informasi berbasis web guna mengotomatiskan pencatatan data dan manajemen transaksi.
*   **Aktor yang Terlibat**:
    1.  **Pelanggan**: Melakukan eksplorasi katalog, checkout, dan unggah bukti transfer.
    2.  **Sistem**: Mengotomatiskan validasi stok, menghitung tagihan, mencatat status, dan mengamankan data.
    3.  **Operator**: Staf internal yang bertugas meninjau pembayaran dan mengelola pengerjaan bunga.
*   **Proses Utama**:
    1.  Pelanggan menjelajahi katalog bunga, menambahkan produk ke keranjang, dan melakukan pengisian formulir checkout secara mandiri.
    2.  Sistem memproses penyimpanan order, mengunci persediaan produk, dan menerbitkan nomor pesanan berkode unik.
    3.  Pelanggan melakukan pembayaran dan mengunggah bukti fisik transfer langsung ke dalam portal web.
    4.  Operator meninjau bukti transfer dan memverifikasinya dalam sistem. Sistem secara otomatis melakukan pengurangan stok riil dan merekam mutasi log inventori.
*   **Keputusan Penting (Decision Points)**:
    *   *Validasi Stok & Status*: Sistem menghentikan checkout jika stok habis secara mendadak.
    *   *Validasi Pembayaran*: Operator menyetujui atau menolak bukti transfer. Jika ditolak, pelanggan diwajibkan mengunggah ulang bukti pembayaran.
*   **Hubungan dengan Sistem yang Diusulkan**: Diagram ini merinci keseluruhan siklus hidup (*lifecycle*) sistem yang diusulkan, menjadi cetak biru bagi tim pengembang untuk menulis logika logika alur bisnis utama.

---

## 3. Use Case Diagram

*   **Tujuan Diagram**: Memvisualisasikan fungsionalitas sistem yang ditawarkan kepada masing-masing aktor pengguna (*Guest, Customer, Operator, Admin*) dan menetapkan batasan ruang lingkup (*system boundary*).
*   **Aktor yang Terlibat**:
    1.  **Tamu (Guest)**: Pengguna anonim (akses katalog, daftar, login).
    2.  **Pelanggan (Customer)**: Pengguna pembeli terautentikasi (keranjang, checkout, upload bukti, pantau timeline).
    3.  **Operator**: Staf internal (verifikasi bayar, kelola status pesanan, pantau stok).
    4.  **Administrator**: Pemilik/Manajer toko (CRUD produk/kategori, kelola operator, laporan keuangan).
*   **Proses Utama**:
    *   Siklus belanja publik (Katalog, Keranjang, Checkout, dan Upload Pembayaran).
    *   Siklus operasional staf (Verifikasi Pembayaran dan Pengelolaan Status Pesanan).
    *   Siklus administratif (Manajemen Master Data Produk/Kategori, Manajemen Hak Akses Staf, dan Analisis Laporan Keuangan).
*   **Keputusan Penting (Decision Points)**: Hubungan relasional *include* (misal: checkout mencakup proses login) dan *generalization* aktor (Admin mewarisi seluruh kapabilitas Operator).
*   **Hubungan dengan Sistem yang Diusulkan**: Berfungsi sebagai dokumen spesifikasi fungsionalitas sistem (*functional requirements*) yang membatasi pengembangan fitur agar tidak keluar dari lingkup skripsi.

---

## 4. Use Case Descriptions (Dokumen Tekstual)

*   **Tujuan Dokumen**: Menyediakan penjelasan tekstual mendalam dan sangat terstruktur untuk setiap *use case* guna memahami variasi skenario transaksi.
*   **Aktor atau Komponen**: Tamu, Pelanggan, Operator, Admin, dan Sistem.
*   **Proses Utama**: Mendokumentasikan 12 use case utama dari fase pendaftaran akun hingga pelaporan finansial.
*   **Keputusan Penting (Decision Points)**: Menjelaskan alur alternatif (*alternative flow*) dan alur pengecualian (*exception flow*) saat terjadi kesalahan (misal: validasi email unik gagal, stok habis, file upload melebihi batas).
*   **Hubungan dengan Sistem yang Diusulkan**: Digunakan oleh tim pengujian (*QA Tester*) untuk menyusun skenario uji *Black Box Testing* guna menjamin keandalan sistem terhadap berbagai input masukan.

---

## 5. Detailed Activity Diagrams (Swimlanes)

*   **Tujuan Diagram**: Menyediakan detail alur aktivitas tingkat rendah dengan partisi lajur tanggung jawab (*swimlanes*) untuk memperjelas interaksi antara aktor manusia dan respon balik sistem.
*   **Aktor atau Komponen**: Pelanggan, Operator, Admin, dan Subsistem Laravel/React.
*   **Proses Utama**: Memetakan alur detail 12 fungsi bisnis utama.
*   **Keputusan Penting (Decision Points)**: Menggambarkan secara visual percabangan logis (kondisi percabangan diamond) pada level backend, seperti penguncian transaksi database (`DB::transaction`) dan penolakan pembayaran.
*   **Hubungan dengan Sistem yang Diusulkan**: Menjadi panduan bagi programmer dalam menyusun skrip *frontend* (React hooks/state) dan *backend* (pengendali/controller) untuk setiap modul.

---

## 6. Sequence Diagrams

*   **Tujuan Diagram**: Menggambarkan interaksi dinamis dan kronologis pengiriman pesan (*message passing*) antar komponen perangkat lunak (UI, Route, Controller, Form Request, Policy, Model, Database) dalam memproses aksi pengguna.
*   **Aktor atau Komponen**: Pengguna, React Page, Laravel Route, Controller, Form Request, Policy, Eloquent Model, dan MySQL Database.
*   **Proses Utama**: Memetakan 9 skenario transaksi krusial.
*   **Keputusan Penting (Decision Points)**: Menunjukkan posisi penanganan pengecualian (*exception handling*) dan rollback transaksi pada level basis data MySQL ketika terjadi kegagalan pemrosesan.
*   **Hubungan dengan Sistem yang Diusulkan**: Membuktikan penerapan arsitektur bersih (*Clean Architecture*) berbasis MVC (Model-View-Controller) pada kerangka kerja Laravel dan React Inertia.

---

## 7. Class Diagram

*   **Tujuan Diagram**: Memvisualisasikan rancangan struktur kelas statis sistem, tipe data atribut, operasi metode, serta hubungan ketergantungan relasional antarentitas objek.
*   **Aktor atau Komponen**: 10 Kelas Model Domain Utama (`User`, `Category`, `Product`, `Cart`, `CartItem`, `Order`, `OrderItem`, `Payment`, `OrderStatusHistory`, `StockMovement`).
*   **Proses Utama**: Merepresentasikan atribut properti database dan metode relasi asosiasi (seperti `orders()`, `product()`, `category()`).
*   **Keputusan Penting (Decision Points)**: Menunjukkan kardinalitas relasi antar objek (contoh: 1 objek `Order` berasosiasi secara unik dengan tepat 1 objek `Payment` [1 to 0..1], dan berasosiasi dengan banyak objek `OrderItem` [1 to 1..*]).
*   **Hubungan dengan Sistem yang Diusulkan**: Menjadi representasi logis dari perancangan kode program berorientasi objek (*Object-Oriented Programming*) pada framework Laravel.

---

## 8. Entity Relationship Diagram (ERD) & DBML

*   **Tujuan Diagram**: Merancang struktur data fisik penyimpanan, mendefinisikan tipe kolom database, indeks keunikan, serta menegakkan integritas referensial data.
*   **Aktor atau Komponen**: 10 Tabel fisik database beserta kolom-kolom penyusunnya.
*   **Proses Utama**: Menghubungkan tabel melalui kunci utama (*Primary Key*) dan kunci tamu (*Foreign Key*).
*   **Keputusan Penting (Decision Points)**: Penerapan kekangan integritas zaitun seperti `deleted_at` untuk soft-delete produk, `unique` indeks untuk nomor pesanan dan email, serta kardinalitas relasi tabel.
*   **Hubungan dengan Sistem yang Diusulkan**: Menjadi sumber kebenaran (*single source of truth*) dalam pembuatan berkas migrasi database Laravel (`database/migrations/`).

---

## 9. Logical Record Structure (LRS)

*   **Tujuan Diagram**: Menyajikan representasi logis dari tabel-tabel basis data yang telah dinormalisasi untuk mempermudah penulisan kueri relasional.
*   **Aktor atau Komponen**: Kardinalitas kotak rekaman 10 tabel utama.
*   **Proses Utama**: Pemetaan visual kunci utama ke kunci tamu lintas tabel.
*   **Keputusan Penting (Decision Points)**: Penggambaran relasi satu-ke-banyak (1:N) dan satu-ke-satu (1:1) tanpa memuat detail tipe data internal guna menjaga kejelasan diagram.
*   **Hubungan dengan Sistem yang Diusulkan**: Mempermudah penyusunan logika kueri gabungan (*JOIN Query*) atau relasi Eager Loading Eloquent di sisi server.

---

## 10. Deployment Diagram

*   **Tujuan Diagram**: Memvisualisasikan konfigurasi fisik infrastruktur perangkat keras dan penempatan perangkat lunak sistem pada lingkungan pengembangan lokal (*local development*).
*   **Aktor atau Komponen**: Client Device (Web Browser), Local Development Computer (Apache/PHP Herd, MySQL Database Server, Local Disk Storage, phpMyAdmin).
*   **Proses Utama**: Pemetaan protokol komunikasi HTTP/HTTPS antara peramban web klien dan server, serta koneksi internal socket TCP/IP antara PHP dan database MySQL (Port 3306).
*   **Keputusan Penting (Decision Points)**: Pemisahan peran phpMyAdmin sebagai alat administrasi terpisah, bukan bagian dari sistem utama.
*   **Hubungan dengan Sistem yang Diusulkan**: Menggambarkan arsitektur fisik tempat aplikasi diuji dan dijalankan untuk simulasi skripsi.

---

## 11. Component Architecture Diagram

*   **Tujuan Diagram**: Menjelaskan pembagian tanggung jawab arsitektur berlapis (*layered architecture*) sistem dan bagaimana data ditransfer melewati batas antar komponen.
*   **Aktor atau Komponen**: Lapisan React SPA UI, Inertia Bridge, Laravel Router, Controllers, Form Requests, Policies, Services, Eloquent Models, MySQL, dan Storage.
*   **Proses Utama**: Aliran permintaan (*request*) dari klik UI melewati validasi dan otorisasi hingga manipulasi database, dan aliran balik respons (*response*) berupa rendering props reaktif Inertia.
*   **Keputusan Penting (Decision Points)**: Penggunaan protokol Inertia.js sebagai jembatan JSON reaktif tanpa memerlukan REST API terpisah.
*   **Hubungan dengan Sistem yang Diusulkan**: Memberikan gambaran arsitektur tingkat tinggi (*high-level architecture*) yang menjelaskan keunggulan teknis pilihan teknologi yang digunakan.

---

## 12. Sitemap (Peta Situs)

*   **Tujuan Diagram**: Memetakan struktur navigasi halaman web dan mengorganisasikan arsitektur informasi berdasarkan hak akses peran pengguna.
*   **Aktor atau Komponen**: Halaman Publik, Portal Pelanggan, Portal Operator, dan Portal Admin.
*   **Proses Utama**: Hierarki pohon menu halaman dari tingkat tertinggi hingga halaman detail fungsional.
*   **Keputusan Penting (Decision Points)**: Pembatasan ketat rute internal (Operator & Admin) agar terpisah secara visual dan fungsional dari rute publik/pelanggan.
*   **Hubungan dengan Sistem yang Diusulkan**: Menjadi panduan perancangan komponen navigasi global seperti navigasi bar publik (`PublicNavbar`), bilah samping kerja (`DashboardSidebar`), dan menu dropdown profil.

---

## 13. User Flow

*   **Tujuan Diagram**: Menggambarkan secara linier langkah-langkah yang dilalui oleh pengguna dalam menyelesaikan suatu tugas spesifik dari titik awal hingga tujuan akhir tercapai.
*   **Aktor atau Komponen**: Tamu, Pelanggan, Operator, Admin, dan Halaman Antarmuka.
*   **Proses Utama**: Aliran langkah 6 skenario perjalanan pengguna dari registrasi, checkout, hingga verifikasi dan peninjauan laporan.
*   **Keputusan Penting (Decision Points)**: Penanganan kondisi gagal di tengah alur (seperti penolakan pembayaran atau kegagalan validasi berkas).
*   **Hubungan dengan Sistem yang Diusulkan**: Memastikan desain antarmuka pengguna memiliki kegunaan yang tinggi (*high usability*) dan alur kerja yang intuitif.

---

## 14. User Interface Wireframes

*   **Tujuan Dokumen**: Menyediakan rancangan tata letak dasar setiap halaman sistem sebelum elemen estetika diimplementasikan untuk memandu penataan tata letak visual.
*   **Aktor atau Komponen**: Layout halaman publik, pelanggan, dan dashboard kerja staf.
*   **Proses Utama**: Penempatan elemen tombol, tabel, ringkasan kartu metrik, formulir, dan indikator status.
*   **Keputusan Penting (Decision Points)**: Konsistensi gaya bertema *Botanical Heritage* dengan porsi ruang putih (*whitespace*) yang lapang untuk kenyamanan membaca.
*   **Hubungan dengan Sistem yang Diusulkan**: Menjadi acuan visual utama dalam menuliskan struktur HTML/TSX dan kelas utilitas CSS Tailwind di sisi *frontend*.
