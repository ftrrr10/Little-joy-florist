# Deskripsi Detail Use Case - Sistem Informasi Pemesanan Little Joy Jakarta

Dokumen ini berisi penjelasan terstruktur mengenai 12 *use case* utama yang diimplementasikan dalam **Sistem Informasi Pemesanan Bunga Little Joy Jakarta**. Format penulisan disesuaikan dengan kaidah penulisan skripsi akademik bidang ilmu komputer/sistem informasi.

---

## 1. Registrasi Pelanggan (Customer Registration)

*   **Nama Use Case**: Registrasi Pelanggan
*   **Aktor**: Tamu (*Guest*)
*   **Tujuan**: Membuat akun baru di sistem agar dapat melakukan transaksi pemesanan bunga.
*   **Kondisi Awal (Precondition)**: Tamu belum masuk ke dalam sistem dan mengakses halaman registrasi.
*   **Pemicu (Trigger)**: Tamu mengklik tombol "Daftar" pada navigasi bar utama.
*   **Alur Utama (Main Flow)**:
    1. Tamu mengakses halaman registrasi.
    2. Sistem menampilkan formulir registrasi yang meminta input Nama Lengkap, Email, Nomor Telepon, Alamat Lengkap, Kata Sandi, dan Konfirmasi Kata Sandi.
    3. Tamu mengisi data yang diminta dan menekan tombol "Daftar".
    4. Sistem melakukan verifikasi email unik dan kekuatan kata sandi.
    5. Sistem membuat akun baru dengan peran (*role*) `customer` dan status `is_active` bernilai `true`.
    6. Sistem secara otomatis mengautentikasi pengguna dan mengarahkannya ke halaman Beranda dengan pesan sukses.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **4a. Email Sudah Terdaftar**: Sistem mendeteksi email telah terdaftar di database, menghentikan pendaftaran, dan menampilkan pesan error *"Email sudah terdaftar"*.
    *   **4b. Kata Sandi Tidak Cocok**: Konfirmasi kata sandi tidak sesuai dengan kata sandi utama, sistem menampilkan pesan error *"Konfirmasi kata sandi tidak cocok"*.
*   **Kondisi Akhir (Postcondition)**: Akun pelanggan baru tersimpan di basis data dan pelanggan dalam kondisi masuk (*logged-in*).

---

## 2. Melakukan Login (Login)

*   **Nama Use Case**: Melakukan Login
*   **Aktor**: Tamu (*Guest*), Pelanggan (*Customer*), Operator, Admin
*   **Tujuan**: Mengautentikasi identitas pengguna untuk mengakses fitur-fitur sesuai peran (*role*) masing-masing.
*   **Kondisi Awal (Precondition)**: Pengguna belum masuk ke dalam sistem.
*   **Pemicu (Trigger)**: Pengguna mengklik tombol "Masuk" pada navigasi bar utama.
*   **Alur Utama (Main Flow)**:
    1. Pengguna membuka halaman login.
    2. Sistem menampilkan formulir login yang meminta input Email dan Kata Sandi.
    3. Pengguna mengisi data dan menekan tombol "Masuk".
    4. Sistem melakukan pemeriksaan kesesuaian data dengan basis data.
    5. Sistem memeriksa status keaktifan akun (`is_active`).
    6. Sistem mengautentikasi pengguna dan mengarahkannya ke halaman tujuan:
        *   Pelanggan diarahkan ke Beranda atau halaman transaksi sebelumnya.
        *   Operator diarahkan ke Dashboard Operator.
        *   Admin diarahkan ke Dashboard Admin.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **4a. Kredensial Salah**: Sistem mendeteksi email atau kata sandi tidak cocok, menghentikan login, dan menampilkan pesan error *"Kredensial tidak cocok dengan data kami"*.
    *   **5a. Akun Dinonaktifkan**: Sistem mendeteksi `is_active = false`, menghentikan login, mengeluarkan sesi, dan menampilkan pesan error *"Akun Anda dinonaktifkan. Silakan hubungi admin"*.
*   **Kondisi Akhir (Postcondition)**: Pengguna masuk ke dalam sistem dengan sesi aktif sesuai hak akses perannya.

---

## 3. Melihat Katalog Bunga (View Product Catalogue)

*   **Nama Use Case**: Melihat Katalog Bunga
*   **Aktor**: Tamu (*Guest*), Pelanggan (*Customer*)
*   **Tujuan**: Menjelajahi, mencari, dan memfilter produk rangkaian bunga yang tersedia di Little Joy Jakarta.
*   **Kondisi Awal (Precondition)**: Pengguna mengakses website Little Joy.
*   **Pemicu (Trigger)**: Pengguna mengklik menu "Koleksi" pada navigasi bar utama.
*   **Alur Utama (Main Flow)**:
    1. Pengguna membuka halaman katalog.
    2. Sistem mengambil seluruh data produk aktif (`is_active = true` dan tidak dihapus secara logis/soft-deleted) beserta kategorinya dari basis data.
    3. Sistem menampilkan daftar produk dalam bentuk kartu grid visual yang memuat gambar, nama, kategori, harga, dan indikator stok.
    4. Pengguna dapat melakukan pencarian berdasarkan nama produk atau menyaring berdasarkan kategori tertentu.
    5. Sistem memperbarui daftar produk secara dinamis sesuai filter yang dipilih.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **3a. Produk Kosong**: Jika tidak ada produk yang memenuhi kriteria filter/pencarian, sistem menampilkan visualisasi halaman kosong (*Empty State*).
*   **Kondisi Akhir (Postcondition)**: Pengguna mendapatkan informasi detail visual dari rangkaian bunga yang dicari.

---

## 4. Mengelola Keranjang Belanja (Manage Cart)

*   **Nama Use Case**: Mengelola Keranjang Belanja
*   **Aktor**: Pelanggan (*Customer*), Operator, Admin
*   **Tujuan**: Menambah, mengubah kuantitas, menghapus, atau mengosongkan item rangkaian bunga sebelum proses checkout.
*   **Kondisi Awal (Precondition)**: Pengguna telah login ke dalam sistem.
*   **Pemicu (Trigger)**: Pengguna menekan tombol "Masukkan Ke Keranjang" pada halaman detail produk.
*   **Alur Utama (Main Flow)**:
    1. Pengguna memilih jumlah produk di halaman detail dan menekan "Masukkan Ke Keranjang".
    2. Sistem memverifikasi ketersediaan stok fisik produk di basis data.
    3. Sistem membuat atau mengambil keranjang aktif milik pengguna.
    4. Jika produk belum ada di keranjang, sistem membuat baris item baru. Jika sudah ada, sistem menjumlahkan kuantitasnya.
    5. Sistem mengalihkan pengguna ke halaman keranjang belanja dan menampilkan rincian subtotal, biaya pengiriman flat (Rp25.000), dan total harga secara dinamis.
*   **Alur Alternatif (Alternative Flow)**:
    *   **Perubahan Kuantitas**: Pengguna mengubah kuantitas di halaman keranjang via tombol tambah/kurang. Sistem memperbarui subtotal secara otomatis setelah memvalidasi batas stok.
    *   **Penghapusan Item**: Pengguna menekan ikon tempat sampah pada item. Sistem menghapus item dari keranjang dan memperbarui total.
*   **Alur Pengecualian (Exception Flow)**:
    *   **2a. Kuantitas Melebihi Stok**: Pengguna mencoba menambahkan kuantitas melebihi stok tersedia. Sistem menolak dan menampilkan pesan error *"Stok tidak mencukupi. Stok tersedia: [stok]"*.
*   **Kondisi Akhir (Postcondition)**: Data keranjang belanja diperbarui secara presisi di basis data (`carts` dan `cart_items`).

---

## 5. Melakukan Checkout (Checkout)

*   **Nama Use Case**: Melakukan Checkout
*   **Aktor**: Pelanggan (*Customer*), Operator, Admin
*   **Tujuan**: Membuat transaksi pesanan baru dari item yang ada di dalam keranjang belanja.
*   **Kondisi Awal (Precondition)**: Keranjang belanja pengguna tidak kosong dan pengguna telah login.
*   **Pemicu (Trigger)**: Pengguna menekan tombol "Lanjutkan ke Checkout" di halaman keranjang belanja.
*   **Alur Utama (Main Flow)**:
    1. Pengguna membuka halaman checkout.
    2. Sistem mengunci baris produk di database (`lockForUpdate`) untuk menghindari konflik stok saat transaksi konkuren.
    3. Sistem menyajikan ringkasan pesanan dan formulir data pengiriman.
    4. Pengguna mengisi data Nama Penerima, Nomor Telepon Penerima, Alamat Pengiriman, Tanggal Pengiriman, Pesan di Kartu Ucapan, dan Catatan Tambahan.
    5. Pengguna menekan tombol "Konfirmasi Pesanan".
    6. Sistem memverifikasi kembali bahwa harga produk tidak berubah dan stok produk mencukupi di dalam transaksi basis data (`DB::transaction`).
    7. Sistem membuat nomor pesanan unik berurutan dengan format `LJ-YYYYMMDD-XXXX`.
    8. Sistem menyimpan data pesanan (`orders`) dan membuat salinan (*snapshot*) produk serta harga satuan ke tabel item pesanan (`order_items`).
    9. Sistem mengosongkan keranjang belanja pengguna dan mencatat riwayat status awal sebagai `pending_payment`.
    10. Sistem mengalihkan pengguna ke halaman sukses checkout yang menampilkan instruksi transfer bank manual.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **6a. Kegagalan Validasi Stok (Konkurensi)**: Jika stok tiba-tiba habis karena dibeli pengguna lain sesaat sebelum klik konfirmasi, sistem membatalkan transaksi (*rollback*), mengembalikan pengguna ke keranjang, dan menampilkan pesan kesalahan stok.
    *   **6b. Tanggal Pengiriman di Masa Lalu**: Pengguna memasukkan tanggal sebelum hari ini, sistem menampilkan pesan error *"Tanggal pengiriman minimal hari ini"*.
*   **Kondisi Akhir (Postcondition)**: Transaksi pesanan tersimpan di basis data, stok belum berkurang (baru berkurang setelah pembayaran diverifikasi), dan keranjang kosong.

---

## 6. Mengunggah Bukti Pembayaran (Upload Payment Proof)

*   **Nama Use Case**: Mengunggah Bukti Pembayaran
*   **Aktor**: Pelanggan (*Customer*)
*   **Tujuan**: Mengirimkan informasi transfer bank dan mengunggah foto bukti fisik transfer sebagai syarat verifikasi pesanan.
*   **Kondisi Awal (Precondition)**: Pelanggan memiliki pesanan dengan status `pending_payment` atau `rejected`.
*   **Pemicu (Trigger)**: Pelanggan menekan tombol "Bayar Sekarang" atau "Upload Bukti Pembayaran" di halaman riwayat pesanan.
*   **Alur Utama (Main Flow)**:
    1. Pelanggan membuka formulir unggah bukti pembayaran.
    2. Formulir meminta input Bank Asal, Bank Tujuan (BCA/Mandiri), Nama Pemilik Rekening, Nominal Transfer, Tanggal Transfer, dan Berkas Bukti Transfer (gambar).
    3. Pelanggan mengisi data, memilih berkas gambar bukti, dan menekan tombol "Kirim Pembayaran".
    4. Sistem memverifikasi berkas (format gambar harus JPG/JPEG/PNG/WebP, ukuran maksimal 2MB).
    5. Sistem menyimpan berkas ke penyimpanan aman (`storage/app/public/payment_proofs/`) dan mencatat data transaksi di tabel `payments`.
    6. Sistem mengubah status pembayaran pesanan menjadi `waiting_verification` dan memicu riwayat status pesanan.
    7. Sistem menampilkan pesan sukses dan mengalihkan kembali ke halaman detail pesanan.
*   **Alur Alternatif (Alternative Flow)**:
    *   **Mengunggah Ulang**: Jika pembayaran sebelumnya ditolak (`rejected`), pelanggan dapat mengunggah bukti baru. Sistem secara otomatis menghapus berkas bukti lama dari disk fisik sebelum menyimpan bukti baru.
*   **Alur Pengecualian (Exception Flow)**:
    *   **4a. Berkas Tidak Valid**: Berkas berukuran > 2MB atau bukan berformat gambar. Sistem menolak dan menampilkan pesan kesalahan berkas.
*   **Kondisi Akhir (Postcondition)**: Berkas tersimpan di disk server, baris data tercatat di tabel `payments`, dan status pesanan diperbarui menjadi `waiting_verification`.

---

## 7. Melakukan Verifikasi Pembayaran (Verify Payment)

*   **Nama Use Case**: Melakukan Verifikasi Pembayaran
*   **Aktor**: Operator, Admin
*   **Tujuan**: Menyetujui bukti pembayaran pesanan yang sah, mengurangi stok produk, dan mengubah status pesanan menjadi lunas.
*   **Kondisi Awal (Precondition)**: Pesanan berstatus `waiting_verification` dan bukti pembayaran telah diunggah.
*   **Pemicu (Trigger)**: Operator menekan tombol "Verifikasi Pembayaran" di halaman detail pesanan dashboard.
*   **Alur Utama (Main Flow)**:
    1. Operator membuka detail pesanan di dashboard kerja.
    2. Operator memverifikasi kesesuaian gambar bukti transfer dengan mutasi rekening bank Little Joy.
    3. Operator menekan tombol "Verifikasi Pembayaran".
    4. Sistem membuka transaksi basis data dan mengunci baris produk terkait (`lockForUpdate`).
    5. Sistem memverifikasi kembali ketersediaan stok fisik produk.
    6. Sistem memperbarui status pembayaran menjadi `verified` di tabel `payments`.
    7. Sistem mengubah status pesanan menjadi `paid` dan mencatat waktu lunas (`completed_at` jika relevan).
    8. Sistem mengurangi stok produk secara otomatis di tabel `products`.
    9. Sistem menulis catatan mutasi stok keluar (`out`) ke tabel `stock_movements`.
    10. Sistem menulis riwayat transisi status ke `order_status_histories`.
    11. Sistem memperbarui halaman detail dengan visualisasi status lunas.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **5a. Stok Habis Saat Verifikasi**: Jika stok produk habis sebelum operator memverifikasi (misal karena penyesuaian stok manual), sistem membatalkan transaksi (*rollback*) dan menampilkan pesan kesalahan stok kepada operator.
*   **Kondisi Akhir (Postcondition)**: Status pesanan berubah menjadi `paid`, status pembayaran `verified`, stok produk berkurang, dan mutasi tercatat di inventori.

---

## 8. Melakukan Penolakan Pembayaran (Reject Payment)

*   **Nama Use Case**: Melakukan Penolakan Pembayaran
*   **Aktor**: Operator, Admin
*   **Tujuan**: Menolak bukti pembayaran yang tidak valid (misal: nominal kurang, gambar palsu, bank tidak cocok) dengan menyertakan alasan penolakan.
*   **Kondisi Awal (Precondition)**: Pesanan berstatus `waiting_verification`.
*   **Pemicu (Trigger)**: Operator menekan tombol "Tolak Pembayaran" di halaman detail pesanan dashboard.
*   **Alur Utama (Main Flow)**:
    1. Operator melihat bukti pembayaran yang tidak sesuai di halaman detail pesanan.
    2. Operator menekan tombol "Tolak Pembayaran".
    3. Sistem memunculkan modal dialog permintaan alasan penolakan (*rejection note*).
    4. Operator mengetikkan alasan penolakan secara jelas (wajib diisi) dan menekan "Konfirmasi Tolak".
    5. Sistem memperbarui status di tabel `payments` menjadi `rejected` dan menyimpan catatan alasan penolakan.
    6. Sistem memperbarui status pesanan menjadi `rejected` (atau kembali ke state menunggu unggah ulang).
    7. Sistem mencatat riwayat transisi status di `order_status_histories`.
    8. Sistem mengirimkan pembaruan status ke sisi pelanggan.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **4a. Alasan Penolakan Kosong**: Operator tidak mengisi alasan penolakan. Sistem menahan pengiriman dan menampilkan validasi *"Alasan penolakan wajib diisi"*.
*   **Kondisi Akhir (Postcondition)**: Pembayaran ditandai sebagai ditolak, alasan penolakan tersimpan, dan pelanggan diberikan hak untuk mengunggah bukti baru.

---

## 9. Mengelola Status Pesanan (Update Order Status)

*   **Nama Use Case**: Mengelola Status Pesanan
*   **Aktor**: Operator, Admin
*   **Tujuan**: Memperbarui status kemajuan pengerjaan dan pengiriman pesanan bunga dari satu tahapan ke tahapan berikutnya.
*   **Kondisi Awal (Precondition)**: Pesanan telah berstatus lunas (`paid`).
*   **Pemicu (Trigger)**: Operator memilih opsi status baru pada dropdown status di halaman detail pesanan.
*   **Alur Utama (Main Flow)**:
    1. Operator membuka detail pesanan aktif.
    2. Operator memilih status berikutnya dari dropdown status yang tersedia sesuai aturan transisi:
        *   `paid` &rarr; `processing` (Bunga mulai dirangkai oleh florist).
        *   `processing` &rarr; `ready` (Bunga selesai dirangkai & siap dikirim).
        *   `ready` &rarr; `shipped` (Bunga diserahkan ke kurir untuk dikirim).
        *   `shipped` &rarr; `completed` (Bunga telah diterima di alamat tujuan).
    3. Operator dapat menambahkan catatan operasional staf (*operator note*) jika diperlukan.
    4. Operator menekan tombol "Perbarui Status".
    5. Sistem melakukan validasi apakah transisi status tersebut sah secara logika alur.
    6. Sistem memperbarui kolom `order_status` di tabel `orders`.
    7. Sistem menulis riwayat perubahan status lengkap dengan catatan operasional dan ID staf ke tabel `order_status_histories`.
    8. Sistem menyegarkan halaman detail pesanan untuk merefleksikan status terbaru.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **5a. Transaksi Status Ilegal**: Percobaan mengubah status di luar jalur sah (misal dari `paid` langsung ke `completed`). Sistem menolak dan melempar pesan kesalahan logika.
*   **Kondisi Akhir (Postcondition)**: Kolom status pesanan terupdate di database dan terekam secara historis di log audit sistem.

---

## 10. Mengelola Produk Bunga (Manage Product)

*   **Nama Use Case**: Mengelola Produk Bunga
*   **Aktor**: Admin
*   **Tujuan**: Melakukan operasi CRUD (Tambah, Tampil, Ubah, Hapus secara logis) pada data master produk bunga.
*   **Kondisi Awal (Precondition)**: Admin telah login dan berada di halaman kelola produk.
*   **Pemicu (Trigger)**: Admin mengklik tombol "Tambah Produk" atau "Edit" pada baris produk.
*   **Alur Utama (Main Flow)**:
    *   **Operasi Tambah (Create)**:
        1. Admin membuka form produk baru, mengisi Nama, Kategori, Deskripsi, Harga, Stok Awal, Status Aktif, dan mengunggah foto produk.
        2. Sistem memvalidasi input (harga & stok &ge; 0, file gambar < 2MB).
        3. Sistem menyimpan foto produk ke disk publik dan mencatat data produk ke tabel `products`.
    *   **Operasi Ubah (Update)**:
        1. Admin membuka form edit produk, mengubah data yang diinginkan (termasuk mengganti foto).
        2. Jika foto diganti, sistem menghapus file foto lama dari penyimpanan fisik dan menyimpan file foto baru.
        3. Sistem memperbarui baris data produk.
    *   **Operasi Hapus (Delete - Soft Delete)**:
        1. Admin menekan tombol "Hapus" pada salah satu produk.
        2. Sistem melakukan penghapusan logis (*Soft Delete*) dengan mengisi kolom `deleted_at` di tabel `products`.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **2a. Input Negatif**: Admin menginput harga atau stok di bawah 0. Sistem membatalkan penyimpanan dan menampilkan validasi error *"Nilai tidak boleh negatif"*.
*   **Kondisi Akhir (Postcondition)**: Data master produk di basis data terupdate tanpa merusak data snapshot produk pada pesanan yang sudah berjalan sebelumnya.

---

## 11. Mengelola Inventori & Penyesuaian Stok (Manage Stock)

*   **Nama Use Case**: Mengelola Inventori & Penyesuaian Stok
*   **Aktor**: Operator, Admin
*   **Tujuan**: Melakukan penyesuaian stok produk secara manual (karena bunga layu, penambahan pasokan segar, atau koreksi audit fisik) dan mencatatnya dalam kartu stok.
*   **Kondisi Awal (Precondition)**: Pengguna telah login sebagai staf dan mengakses halaman ringkasan stok.
*   **Pemicu (Trigger)**: Pengguna menekan tombol "Sesuaikan Stok" pada baris produk.
*   **Alur Utama (Main Flow)**:
    1. Pengguna membuka form penyesuaian stok.
    2. Sistem menampilkan data stok saat ini.
    3. Pengguna memilih jenis penyesuaian (Tambah/Kurang), memasukkan jumlah unit selisih, dan menulis catatan penjelasan wajib.
    4. Pengguna menekan tombol "Simpan Penyesuaian".
    5. Sistem menjalankan transaksi basis data (`DB::transaction`) dan mengunci baris produk (`lockForUpdate`).
    6. Sistem menghitung stok akhir (Stok Sebelum &plusmn; Jumlah Selisih).
    7. Sistem memastikan stok akhir tidak bernilai negatif.
    8. Sistem memperbarui kolom `stock` pada tabel `products`.
    9. Sistem menulis data mutasi ke tabel `stock_movements` (mencatat tipe gerakan `adjustment`, stok sebelum, stok sesudah, kuantitas selisih, alasan penyesuaian, dan ID staf pembuat).
    10. Sistem menampilkan pesan sukses dan memperbarui tabel ringkasan stok.
*   **Alur Alternatif (Alternative Flow)**: -
*   **Alur Pengecualian (Exception Flow)**:
    *   **7a. Stok Akhir Negatif**: Jika pengurangan manual menghasilkan angka di bawah 0, sistem menolak transaksi, membatalkan perubahan, dan menampilkan pesan error *"Kalkulasi stok menghasilkan nilai negatif"*.
    *   **9a. Catatan Kosong**: Pengguna tidak menulis catatan alasan penyesuaian. Sistem menolak menyimpan dan menampilkan validasi *"Catatan penyesuaian wajib diisi"*.
*   **Kondisi Akhir (Postcondition)**: Stok fisik produk terupdate di database dan terekam secara kronologis di log kartu stok audit inventori.

---

## 12. Melihat Laporan Penjualan (View Sales Report)

*   **Nama Use Case**: Melihat Laporan Penjualan
*   **Aktor**: Admin
*   **Tujuan**: Memantau perkembangan omset, total transaksi, volume bunga terjual, produk terlaris, dan mengekspor data laporan ke format cetak atau spreadsheet.
*   **Kondisi Awal (Precondition)**: Admin telah login dan berada di halaman laporan keuangan.
*   **Pemicu (Trigger)**: Admin mengklik menu "Laporan Keuangan" pada navigasi bar dashboard.
*   **Alur Utama (Main Flow)**:
    1. Admin membuka halaman laporan penjualan.
    2. Sistem secara default memuat data penjualan 30 hari terakhir.
    3. Admin dapat menyaring laporan menggunakan filter: Rentang Tanggal Mulai, Tanggal Selesai, Status Pesanan, dan Status Pembayaran.
    4. Sistem melakukan kalkulasi metrik secara dinamis:
        *   Menghitung total pendapatan bersih (*realized revenue* dari pesanan lunas/selesai).
        *   Menghitung jumlah total transaksi sukses.
        *   Menghitung total volume produk terjual.
        *   Menyusun peringkat produk terlaris (*best-selling products*).
    5. Sistem menampilkan visualisasi grafik tren penjualan dan tabel daftar transaksi rinci.
*   **Alur Alternatif (Alternative Flow)**:
    *   **Ekspor Laporan (CSV)**: Admin menekan tombol "Ekspor CSV". Sistem mengalirkan data laporan langsung dari basis data dengan enkripsi UTF-8 BOM agar kompatibel dengan Excel.
    *   **Cetak Laporan (Print)**: Admin menekan tombol "Cetak Laporan". Sistem memicu antarmuka cetak bawaan browser menggunakan stylesheet cetak khusus (`@media print`) yang menyembunyikan sidebar/navigasi dan memformat laporan dalam bentuk lembar dokumen formal.
*   **Alur Pengecualian (Exception Flow)**: -
*   **Kondisi Akhir (Postcondition)**: Admin mendapatkan pemahaman analitis mengenai performa bisnis florist Little Joy Jakarta dan memiliki salinan fisik/digital data keuangan.
