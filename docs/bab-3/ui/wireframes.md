# Spesifikasi Rancangan Antarmuka (Wireframes) - Little Joy Jakarta

Dokumen ini memuat spesifikasi tata letak (*layout*) antarmuka *low-to-medium fidelity* untuk seluruh halaman sistem **Little Joy Florist Jakarta**. Rancangan ini mengacu pada bahasa desain **Botanical Heritage** yang premium, bersih, dan bertumpu pada tipografi elegan serta pembagian ruang yang proporsional (*generous whitespace*).

---

## 1. Bahasa Desain & Elemen Visual (Botanical Heritage Design Tokens)

*   **Palet Warna Utama**:
    *   `bg-primary`: Hijau Hutan Pekat / Emerald Zamrud (`#022C22` / `#064E3B`). Digunakan untuk sidebar internal, tombol aksi utama publik, dan header penting.
    *   `bg-secondary`: Emas Mawar / Rose Soft (`#A16207` / HSL hangat). Digunakan untuk lencana, teks penekanan, dan link navigasi aktif.
    *   `bg-brandBackground`: Krem Lembut / Off-white (`#F7F4EB`). Digunakan sebagai warna latar belakang halaman utama.
    *   `border-brandOutline`: Garis Abu-abu Zaitun Soft (`#EFECE2`). Digunakan untuk pembatas tabel dan kartu.
*   **Tipografi**:
    *   *Headings (Judul)*: Font Serif Premium (`Libre Caslon Text` atau sejenis) yang terkesan klasik dan berwibawa.
    *   *Body (Isi)*: Font Sans-serif Bersih (`Plus Jakarta Sans` atau `Inter`) untuk keterbacaan tinggi di layar desktop dan mobile.
*   **Gaya Kontainer**: Membulat lebar (`rounded-2xl` / `rounded-3xl`) dengan bayangan halus untuk kesan modern dan elegan tanpa garis tepi hitam yang kasar.

---

## 2. Wireframe Portal Publik (Public Portal)

### A. Landing Page (Beranda)
```text
+-----------------------------------------------------------------------------------------+
| [Little Joy Jakarta]      Beranda   Koleksi   Tentang Kami         [Cari bunga...]  (🛒) [A] |
+-----------------------------------------------------------------------------------------+
|                                                                                         |
|  +-----------------------------------------------------------------------------------+  |
|  |                                                                                   |  |
|  |  * Premium Florist Jakarta                                                        |  |
|  |                                                                                   |  |
|  |  SENI MERANGKAI KEBAHAGIAAN                                                        |  |
|  |  Dirangkai dengan Jiwa                                                            |  |
|  |  Untuk Momen Spesial Anda.                                                        |  |
|  |                                                                                   |  |
|  |  Hadirkan kebahagiaan sejati melalui keindahan botani terbaik...                  |  |
|  |                                                                                   |  |
|  |  [ JELAJAHI KATALOG -> ]     [ Tentang Kami ]                                     |  |
|  |                                                                                   |  |
|  +-----------------------------------------------------------------------------------+  |
|                                                                                         |
|  KOLEKSI EKSKLUSIF                                                                      |
|  Pilih Berdasarkan Kategori Bunga                                                        |
|  +--------------+  +--------------+  +--------------+  +--------------+  +--------------+ |
|  | Hand Bouquet |  |  Bloom Box   |  | Flower Stand |  | Vase Arr.    |  | Orchid Plant | |
|  | [Foto]       |  | [Foto]       |  | [Foto]       |  | [Foto]       |  | [Foto]       | |
|  | Lihat ->     |  | Lihat ->     |  | Lihat ->     |  | Lihat ->     |  | Lihat ->     | |
|  +--------------+  +--------------+  +--------------+  +--------------+  +--------------+ |
|                                                                                         |
|  PRODUK UNGGULAN                                                                        |
|  Rangkaian Terlaris Minggu Ini                                        Lihat Semua ->    |
|  +------------+   +------------+   +------------+   +------------+                      |
|  | [Foto]     |   | [Foto]     |   | [Foto]     |   | [Foto]     |                      |
|  | Tulip Gold |   | Rose Box   |   | Lily White |   | Orchid Pot |                      |
|  | Rp375.000  |   | Rp575.000  |   | Rp850.000  |   | Rp1.250.000|                      |
|  +------------+   +------------+   +------------+   +------------+                      |
+-----------------------------------------------------------------------------------------+
```

### B. Katalog Produk (Product Catalogue)
```text
+-----------------------------------------------------------------------------------------+
| [Little Joy Jakarta]      Beranda   Koleksi   Tentang Kami         [Cari bunga...]  (🛒) [A] |
+-----------------------------------------------------------------------------------------+
|  Koleksi Bunga Kami / Halaman Katalog                                                   |
|                                                                                         |
|  +-----------------------+  +--------------------------------------------------------+  |
|  | FILTER KATALOG        |  | Menampilkan 20 produk rangkaian bunga                   |  |
|  |                       |  | Urutkan: [ Terpopuler v ]                              |  |
|  | Cari: [............]  |  +--------------------------------------------------------+  |
|  |                       |  |                                                        |  |
|  | Kategori:             |  |  +------------+   +------------+   +------------+      |  |
|  | [x] Semua             |  |  | [Foto]     |   | [Foto]     |   | [Foto]     |      |  |
|  | [ ] Hand Bouquet      |  |  | Buket Rose |   | Bloom Tulip|   | Lily Pot   |      |  |
|  | [ ] Bloom Box         |  |  | Rp450.000  |   | Rp650.000  |   | Rp950.000  |      |  |
|  |                       |  |  | (Tersedia) |   | (Tersedia) |   | (Tersedia) |      |  |
|  | Ketersediaan:         |  |  +------------+   +------------+   +------------+      |  |
|  | (o) Semua             |  |                                                        |  |
|  | ( ) Hanya Tersedia    |  |  +------------+   +------------+   +------------+      |  |
|  |                       |  |  | [Foto]     |   | [Foto]     |   | [Foto]     |      |  |
|  | [ BERSIHKAN FILTER ]  |  |  | Gold Stand |   | White Rose |   | Orchid Box |      |  |
|  +-----------------------+  |  | Rp1.500.000|   | Rp520.000  |   | Rp800.000  |      |  |
|                             |  | (Tersedia) |   | (Habis)    |   | (Tersedia) |      |  |
|                             |  +------------+   +------------+   +------------+      |  |
|                             |                                                        |  |
|                             |  Halaman:  [1]   [2]   [3]   [Next ->]                 |  |
|                             +--------------------------------------------------------+  |
+-----------------------------------------------------------------------------------------+
```

### C. Detail Produk (Product Detail)
```text
+-----------------------------------------------------------------------------------------+
| [Little Joy Jakarta]      Beranda   Koleksi   Tentang Kami         [Cari bunga...]  (🛒) [A] |
+-----------------------------------------------------------------------------------------+
|  <- Kembali ke Katalog                                                                  |
|                                                                                         |
|  +-----------------------------------+  +--------------------------------------------+  |
|  |                                   |  | Lencana: Kategori Bunga   Badge: Tersedia  |  |
|  |                                   |  |                                            |  |
|  |                                   |  | Nama: Sweet Romance Hand Bouquet           |  |
|  |                                   |  |                                            |  |
|  |                                   |  | Harga: Rp 575.000                          |  |
|  |              [ FOTO ]             |  | ------------------------------------------ |  |
|  |                                   |  | DESKRIPSI RANGKAIAN:                       |  |
|  |         Rangkaian Detail          |  | Rangkaian indah 20 tangkai mawar merah     |  |
|  |                                   |  | segar premium melambangkan cinta sejati... |  |
|  |                                   |  |                                            |  |
|  |                                   |  | Jumlah:  [ - ]  [ 1 ]  [ + ]   Stok: 8     |  |
|  |                                   |  |                                            |  |
|  |                                   |  | [ MASUKKAN KE KERANJANG ]                  |  |
|  +-----------------------------------+  +--------------------------------------------+  |
+-----------------------------------------------------------------------------------------+
```

---

## 3. Wireframe Portal Pelanggan (Customer Portal)

### A. Keranjang Belanja (Shopping Cart)
```text
+-----------------------------------------------------------------------------------------+
| [Little Joy Jakarta]      Beranda   Koleksi   Tentang Kami         [Cari bunga...]  (🛒) [A] |
+-----------------------------------------------------------------------------------------+
|  Keranjang Belanja Saya                                                                 |
|                                                                                         |
|  +--------------------------------------------------+  +-----------------------------+  |
|  | DAFTAR ITEM DI KERANJANG                         |  | RINGKASAN BELANJA           |  |
|  |                                                  |  |                             |  |
|  | [x] [Foto] Sweet Romance Bouquet                 |  | Subtotal:      Rp  575.000  |  |
|  |     Harga: Rp575.000                             |  | Biaya Kirim:   Rp   25.000  |  |
|  |     Jumlah: [ - ] [ 1 ] [ + ]  Total: Rp575.000  |  |                             |  |
|  |     [Hapus]                                      |  | --------------------------- |  |
|  |                                                  |  | Grand Total:   Rp  600.000  |  |
|  | [x] [Foto] Majestic Orchid Pot                   |  |                             |  |
|  |     Harga: Rp1.250.000                           |  | [ PROSES CHECKOUT ]         |  |
|  |     Jumlah: [ - ] [ 1 ] [ + ]  Total: Rp1.250.000|  |                             |  |
|  |     [Hapus]                                      |  | [ ] Kosongkan Keranjang     |  |
|  +--------------------------------------------------+  +-----------------------------+  |
+-----------------------------------------------------------------------------------------+
```

### B. Checkout & Checkout Success
```text
+-----------------------------------------------------------------------------------------+
|  FORMULIR CHECKOUT PESANAN                                                              |
|                                                                                         |
|  +--------------------------------------------------+  +-----------------------------+  |
|  | DATA PENGIRIMAN                                  |  | RINGKASAN PESANAN           |  |
|  | Nama Penerima      : [........................]  |  |                             |  |
|  | No. Telp Penerima  : [........................]  |  | 1x Sweet Romance Bouquet    |  |
|  | Alamat Pengiriman  : [........................]  |  |    Total: Rp 575.000        |  |
|  | Tanggal Pengiriman : [ DD / MM / YYYY ]          |  |                             |  |
|  |                                                  |  | Subtotal   : Rp 575.000     |  |
|  | KARTU UCAPAN & CATATAN                           |  | Ongkir     : Rp  25.000     |  |
|  | Isi Pesan Kartu    : [........................]  |  | Total      : Rp 600.000     |  |
|  | Catatan Tambahan   : [........................]  |  |                             |  |
|  |                                                  |  | [ KONFIRMASI PESANAN ]      |  |
|  +--------------------------------------------------+  +-----------------------------+  |
+-----------------------------------------------------------------------------------------+

[ CHECKOUT SUKSES BANNER ]
Nomor Pesanan Anda: #LJ-20260627-0001
Silakan Lakukan Transfer Bank Manual Sebesar: Rp 600.000 ke rekening BCA: 123-456-7890 (a.n Little Joy)
[ UPLOAD BUKTI PEMBAYARAN ]  [ LIHAT RIWAYAT PESANAN ]
```

### C. Riwayat Pesanan & Detail Pesanan
```text
+-----------------------------------------------------------------------------------------+
| [Little Joy Jakarta]      Beranda   Koleksi   Tentang Kami         [Cari bunga...]  (🛒) [A] |
+-----------------------------------------------------------------------------------------+
|  Riwayat Transaksi Pemesanan Bunga                                                      |
|                                                                                         |
|  [ Semua ]  [ Belum Bayar ]  [ Menunggu Verifikasi ]  [ Diproses ]  [ Selesai ]         |
|                                                                                         |
|  +-----------------------------------------------------------------------------------+  |
|  | No. Pesanan: #LJ-20260627-0001      Tanggal: 27 Juni 26     Total: Rp 600.000     |  |
|  | Status Order: [Menunggu Verifikasi]                     Status Bayar: [Menunggu]  |  |
|  | Penerima    : Ahmad Rian                                                          |  |
|  | [ Lihat Detail Pesanan ]                                                          |  |
|  +-----------------------------------------------------------------------------------+  |
|  | No. Pesanan: #LJ-20260624-0003      Tanggal: 24 Juni 26     Total: Rp 1.225.000   |  |
|  | Status Order: [Selesai]                                 Status Bayar: [Lunas]     |  |
|  | Penerima    : Budi Santoso                                                        |  |
|  | [ Lihat Detail Pesanan ]                                                          |  |
|  +-----------------------------------------------------------------------------------+  |
+-----------------------------------------------------------------------------------------+
```

---

## 4. Wireframe Portal Dashboard (Admin & Operator)

### A. Dashboard Utama (Overview)
```text
+-----------------------------------------------------------------------------------------+
| [LITTLE JOY] |  Kelola Pesanan Masuk              [Search...]    (🔔) (⚙️)  Admin Little Joy|
|              |                                                             STORE MANAGER|
| - DASHBOARD  |  +--------------------+  +--------------------+  +--------------------+  |
| - PESANAN    |  | TOTAL OMSET (HIJAU) |  | PESANAN HARI INI   |  | STOK KRITIS        |  |
| - PRODUK     |  | Rp 14.850.000       |  | 12 Pesanan         |  | 3 Produk Terbatas  |  |
|              |  | (+12% dr bln lalu)  |  | Target harian: 80% |  | [Lihat Produk ->]  |  |
| - KATEGORI   |  +--------------------+  +--------------------+  +--------------------+  |
| - OPERATOR   |                                                                          |
| - PELANGGAN  |  PROSES TRANSAKSI AKTIF                                                  |
| - LAPORAN    |  [ 3 Belum Bayar ]  [ 2 Menunggu Verifikasi ]  [ 4 Diproses ]  [ 1 Dikirim ]|
|              |                                                                          |
| +----------+ |  +-----------------------------------+  +-----------------------------+  |
| | + PRODUK | |  | TREN PENJUALAN MINGGUAN           |  | PESANAN TERBARU             |  |
| +----------+ |  | Grafik Batang Recharts (Hijau)    |  | #0001 - Ahmad  - Rp600.000  |  |
|              |  |                                   |  | #0002 - Siti   - Rp375.000  |  |
| [A] Admin    |  | Sen Sel Rab Kam Jum Sab Min       |  | #0003 - Budi   - Rp850.000  |  |
| Store Manager|  +-----------------------------------+  +-----------------------------+  |
+-----------------------------------------------------------------------------------------+
```

### B. Kelola Stok & Mutasi (Stock Overview)
```text
+-----------------------------------------------------------------------------------------+
| [LITTLE JOY] |  Ringkasan Stok & Inventori Bunga                       Admin Little Joy |
|              |                                                             STORE MANAGER |
| - DASHBOARD  |  +--------------------------------------------------------------------+  |
| - PESANAN    |  | DAFTAR STOK PRODUK AKTIF                                           |  |
| - PRODUK     |  | Bunga               Kategori        Stok    Status      Aksi       |  |
|              |  | Sweet Romance       Hand Bouquet    8       Tersedia    [Adjust]   |  |
| - KATEGORI   |  | Majestic Orchid     Orchid Plant    2       Kritis      [Adjust]   |  |
| - OPERATOR   |  | Aurora Blossom      Vase Arr.       0       Habis       [Adjust]   |  |
| - PELANGGAN  |  +--------------------------------------------------------------------+  |
| - LAPORAN    |                                                                          |
|              |  +--------------------------------------------------------------------+  |
| +----------+ |  | RIWAYAT LOG MUTASI STOK (AUDIT)                                    |  |
| | + TRANSAK| |  | Tanggal     Produk          Tipe    Selisih Catatan      Staf      |  |
| +----------+ |  | 27 Jun 26   Sweet Romance   out     -1      Order #0001  System    |  |
|              |  | 26 Jun 26   Majestic Orchid adj     +5      Pasokan Baru Admin     |  |
| [A] Admin    |  | 25 Jun 26   Aurora Blossom  out     -2      Bunga Layu   Operator  |  |
| Store Manager|  +--------------------------------------------------------------------+  |
+-----------------------------------------------------------------------------------------+
```

### C. Laporan Keuangan (Sales Report)
```text
+-----------------------------------------------------------------------------------------+
| [LITTLE JOY] |  Laporan Penjualan & Pendapatan                         Admin Little Joy |
|              |                                                             STORE MANAGER |
| - DASHBOARD  |  +--------------------------------------------------------------------+  |
| - PESANAN    |  | FILTER LAPORAN                                                     |  |
| - PRODUK     |  | Tgl Mulai: [27/05/2026]  Tgl Selesai: [27/06/2026]   Status: [Semua] |  |
|              |  | [ TERAPKAN FILTER ]               [ EKSPOR CSV ]   [ CETAK LAPORAN]|  |
| - KATEGORI   |  +--------------------------------------------------------------------+  |
| - OPERATOR   |                                                                          |
| - PELANGGAN  |  +--------------------------------------------------------------------+  |
| - LAPORAN    |  | RINGKASAN KEUANGAN                                                 |  |
|              |  | Total Omset: Rp14.850.000   Transaksi: 42   Volume Terjual: 58 Pcs |  |
| +----------+ |  +--------------------------------------------------------------------+  |
| | + TRANSAK| |                                                                          |
| +----------+ |  +--------------------------------------------------------------------+  |
|              |  | DETAIL TRANSAKSI                                                   |  |
| [A] Admin    |  | No. Pesanan   Tanggal     Pelanggan     Produk      Total       Status |  |
| Store Manager|  | #LJ-0001      27 Jun 26   Ahmad Rian    Sweet Rom.  Rp 600.000  Lunas  |  |
|              |  | #LJ-0002      26 Jun 26   Siti Aminah   Tulip Gold  Rp 375.000  Lunas  |  |
|              |  +--------------------------------------------------------------------+  |
+-----------------------------------------------------------------------------------------+
```
