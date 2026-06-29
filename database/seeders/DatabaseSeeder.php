<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\OrderStatusHistory;
use App\Models\StockMovement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // A. Ensure public storage directories exist
        $publicStoragePath = storage_path('app/public');
        $productsDestDir = $publicStoragePath . '/products';
        $proofsDestDir = $publicStoragePath . '/proofs';

        if (!is_dir($productsDestDir)) {
            mkdir($productsDestDir, 0755, true);
        }
        if (!is_dir($proofsDestDir)) {
            mkdir($proofsDestDir, 0755, true);
        }

        // B. Copy product images from seeder assets to public storage
        $productsSourceDir = database_path('seeders/assets/products');
        if (is_dir($productsSourceDir)) {
            $files = scandir($productsSourceDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    copy($productsSourceDir . '/' . $file, $productsDestDir . '/' . $file);
                }
            }
        }

        // C. Generate or copy sample proof image
        $proofSourceFile = database_path('seeders/assets/proofs/sample_proof.jpg');
        $proofDestFile = $proofsDestDir . '/sample_proof.jpg';

        if (!file_exists($proofDestFile)) {
            if (file_exists($proofSourceFile)) {
                copy($proofSourceFile, $proofDestFile);
            } elseif (function_exists('imagecreatetruecolor')) {
                // Generate beautiful dummy receipt using GD
                $im = imagecreatetruecolor(600, 800);
                $bg = imagecolorallocate($im, 243, 244, 246);
                $white = imagecolorallocate($im, 255, 255, 255);
                $green = imagecolorallocate($im, 5, 150, 105);
                $darkText = imagecolorallocate($im, 31, 41, 55);
                $grayText = imagecolorallocate($im, 107, 114, 128);
                $lineColor = imagecolorallocate($im, 229, 231, 235);

                imagefill($im, 0, 0, $bg);
                imagefilledrectangle($im, 50, 50, 550, 750, $white);
                imagerectangle($im, 50, 50, 550, 750, $lineColor);
                imagefilledrectangle($im, 50, 50, 550, 150, $green);

                imagestring($im, 5, 200, 75, "LITTLE JOY FLORIST", $white);
                imagestring($im, 4, 210, 105, "M-TRANSFER BERHASIL", $white);

                $y = 200;
                $details = [
                    "WAKTU TRANSAKSI" => date('d M Y H:i:s') . " WIB",
                    "KE REKENING" => "BCA - 1234567890",
                    "NAMA PENERIMA" => "LITTLE JOY FLORIST",
                    "BANK PENGIRIM" => "MANDIRI",
                    "NAMA PENGIRIM" => "CUSTOMER UTAMA",
                    "JUMLAH" => "Rp 575.000,00",
                    "BERITA" => "PESANAN #LJ-20260626-0002",
                    "STATUS" => "TRANSAKSI BERHASIL"
                ];

                foreach ($details as $key => $val) {
                    imagestring($im, 3, 80, $y, $key, $grayText);
                    if ($key === "STATUS") {
                        imagefilledrectangle($im, 80, $y + 20, 520, $y + 50, imagecolorallocate($im, 209, 250, 229));
                        imagestring($im, 4, 180, $y + 27, $val, $green);
                        $y += 60;
                    } else if ($key === "JUMLAH") {
                        imagestring($im, 5, 80, $y + 20, $val, $green);
                        $y += 50;
                    } else {
                        imagestring($im, 4, 80, $y + 20, $val, $darkText);
                        $y += 50;
                    }
                    imageline($im, 80, $y, 520, $y, $lineColor);
                    $y += 20;
                }

                imagestring($im, 2, 160, 710, "Terima kasih telah berbelanja di Little Joy!", $grayText);
                imagejpeg($im, $proofDestFile, 90);
                
                // Also save to source path for future commits
                if (is_dir(dirname($proofSourceFile))) {
                    imagejpeg($im, $proofSourceFile, 90);
                }
                imagedestroy($im);
            }
        }

        // 1. Seed Admin User
        User::factory()->create([
            'name' => 'Admin Little Joy',
            'email' => 'admin@littlejoy.com',
            'phone' => '08111111111',
            'role' => User::ROLE_ADMIN,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // 2. Seed Operator Users (2 operators)
        User::factory()->create([
            'name' => 'Operator Satu',
            'email' => 'operator1@littlejoy.com',
            'phone' => '08222222222',
            'role' => User::ROLE_OPERATOR,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Operator Dua',
            'email' => 'operator2@littlejoy.com',
            'phone' => '08333333333',
            'role' => User::ROLE_OPERATOR,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        // 3. Seed Primary Test Customer & 4 other Customers (5 total)
        User::factory()->create([
            'name' => 'Customer Utama',
            'email' => 'customer@example.com',
            'phone' => '08444444444',
            'role' => User::ROLE_CUSTOMER,
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);

        User::factory(4)->create([
            'role' => User::ROLE_CUSTOMER,
            'is_active' => true,
        ]);

        // 4. Seed 5 Specific Categories
        $categories = collect([
            ['name' => 'Hand Bouquet', 'description' => 'Rangkaian buket bunga tangan segar yang dibungkus dengan kertas wrapping premium.'],
            ['name' => 'Bloom Box', 'description' => 'Rangkaian bunga segar di dalam kotak eksklusif berbagai bentuk.'],
            ['name' => 'Flower Stand', 'description' => 'Karangan bunga papan atau standing flower untuk ucapan selamat, peresmian, maupun duka cita.'],
            ['name' => 'Vase Arrangement', 'description' => 'Rangkaian bunga meja indah yang ditata di dalam vas keramik atau kaca.'],
            ['name' => 'Orchid Plant', 'description' => 'Tanaman anggrek bulan premium di dalam pot, sangat anggun dan tahan lama.'],
        ])->map(function ($cat) {
            return Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_active' => true,
            ]);
        });

        // 5. Seed 20 Flower Products distributed across categories
        $flowerTemplates = [
            // Hand Bouquet
            ['name' => 'Classic Red Roses Bouquet', 'price' => 350000, 'cat' => 'Hand Bouquet'],
            ['name' => 'Pastel Carnations Bouquet', 'price' => 280000, 'cat' => 'Hand Bouquet'],
            ['name' => 'Baby Breath Sweetheart Bouquet', 'price' => 250000, 'cat' => 'Hand Bouquet'],
            ['name' => 'Luxury Hydrangea Hand Bouquet', 'price' => 450000, 'cat' => 'Hand Bouquet'],
            // Bloom Box
            ['name' => 'Pink Lily & Rose Bloom Box', 'price' => 550000, 'cat' => 'Bloom Box'],
            ['name' => 'Rustic Wildflowers Box', 'price' => 480000, 'cat' => 'Bloom Box'],
            ['name' => 'Red Velvet Roses Box', 'price' => 600000, 'cat' => 'Bloom Box'],
            ['name' => 'Cheerful Sunflower Box', 'price' => 420000, 'cat' => 'Bloom Box'],
            // Flower Stand
            ['name' => 'Grand Opening Standing Spray', 'price' => 1200000, 'cat' => 'Flower Stand'],
            ['name' => 'White Lilies Sympathy Stand', 'price' => 950000, 'cat' => 'Flower Stand'],
            ['name' => 'Elegant Congratulations Board', 'price' => 1500000, 'cat' => 'Flower Stand'],
            ['name' => 'Colorful Celebration Flower Stand', 'price' => 1100000, 'cat' => 'Flower Stand'],
            // Vase Arrangement
            ['name' => 'Elegant White Orchids in Ceramic Vase', 'price' => 850000, 'cat' => 'Vase Arrangement'],
            ['name' => 'Crimson Gerbera Table Vase', 'price' => 320000, 'cat' => 'Vase Arrangement'],
            ['name' => 'Royal Purple Tulips in Glass Vase', 'price' => 750000, 'cat' => 'Vase Arrangement'],
            ['name' => 'Mixed Fresh Blossoms Vase', 'price' => 500000, 'cat' => 'Vase Arrangement'],
            // Orchid Plant
            ['name' => 'Premium Single Stem Purple Orchid Pot', 'price' => 380000, 'cat' => 'Orchid Plant'],
            ['name' => 'Luxury Double Stem White Orchid Pot', 'price' => 700000, 'cat' => 'Orchid Plant'],
            ['name' => 'Cascade Yellow Orchid Plant', 'price' => 450000, 'cat' => 'Orchid Plant'],
            ['name' => 'Giant Orchid Trio arrangement', 'price' => 1050000, 'cat' => 'Orchid Plant'],
        ];

        $imagePathMap = [
            'Hand Bouquet' => 'products/hand-bouquet.png',
            'Bloom Box' => 'products/bloom-box.png',
            'Flower Stand' => 'products/flower-stand.png',
            'Vase Arrangement' => 'products/vase-arrangement.png',
            'Orchid Plant' => 'products/orchid-plant.png',
        ];

        foreach ($flowerTemplates as $item) {
            // Find the seeded category
            $category = $categories->firstWhere('name', $item['cat']);

            Product::create([
                'category_id' => $category->id,
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => "Rangkaian bunga segar premium {$item['name']}. Sangat cocok sebagai hadiah bagi orang tersayang untuk melengkapi momen spesial Anda. Bunga dipetik langsung dan dirangkai oleh florist bersertifikat kami.",
                'price' => $item['price'],
                'stock' => rand(5, 25),
                'image_path' => $imagePathMap[$item['cat']] ?? null,
                'is_active' => true,
            ]);
        }

        // 6. Seed Sample Orders, Payments, Histories, and Stock Movements
        $customer = User::where('email', 'customer@example.com')->first();
        $admin = User::where('role', User::ROLE_ADMIN)->first();
        $operator = User::where('role', User::ROLE_OPERATOR)->first();
        $products = Product::all();

        if ($customer && $products->isNotEmpty()) {
            // Helper to generate sequential order number
            $generateOrderNumber = function ($dateStr, $sequence) {
                return 'LJ-' . str_replace('-', '', $dateStr) . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            };

            // Order 1: Pending Payment
            $orderDate1 = now()->subDays(2);
            $order1 = Order::create([
                'order_number' => $generateOrderNumber($orderDate1->format('Y-m-d'), 1),
                'user_id' => $customer->id,
                'order_date' => $orderDate1,
                'delivery_date' => now()->addDays(1),
                'recipient_name' => 'Ahmad Rian',
                'recipient_phone' => '08555555555',
                'delivery_address' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
                'greeting_message' => 'Selamat Ulang Tahun! Semoga panjang umur dan sehat selalu.',
                'customer_note' => 'Mohon dikirimkan pagi hari sebelum jam 10.',
                'subtotal' => 350000,
                'delivery_fee' => 25000,
                'total' => 375000,
                'payment_status' => 'pending',
                'order_status' => 'pending_payment',
            ]);

            OrderItem::create([
                'order_id' => $order1->id,
                'product_id' => $products->first()->id,
                'product_name' => $products->first()->name,
                'unit_price' => $products->first()->price,
                'quantity' => 1,
                'subtotal' => $products->first()->price,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order1->id,
                'previous_status' => null,
                'current_status' => 'pending_payment',
                'note' => 'Pesanan berhasil dibuat.',
                'changed_by' => $customer->id,
            ]);

            // Order 2: Waiting Verification
            $orderDate2 = now()->subDays(1);
            $order2 = Order::create([
                'order_number' => $generateOrderNumber($orderDate2->format('Y-m-d'), 2),
                'user_id' => $customer->id,
                'order_date' => $orderDate2,
                'delivery_date' => now()->addDays(2),
                'recipient_name' => 'Siti Aminah',
                'recipient_phone' => '08777777777',
                'delivery_address' => 'Apartemen Medit, Tower B No. 12, Grogol, Jakarta Barat',
                'greeting_message' => 'Happy Anniversary! Love you always.',
                'subtotal' => 550000,
                'delivery_fee' => 25000,
                'total' => 575000,
                'payment_status' => 'waiting_verification',
                'order_status' => 'waiting_verification',
            ]);

            OrderItem::create([
                'order_id' => $order2->id,
                'product_id' => $products->skip(4)->first()->id,
                'product_name' => $products->skip(4)->first()->name,
                'unit_price' => $products->skip(4)->first()->price,
                'quantity' => 1,
                'subtotal' => $products->skip(4)->first()->price,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order2->id,
                'previous_status' => null,
                'current_status' => 'pending_payment',
                'note' => 'Pesanan berhasil dibuat.',
                'changed_by' => $customer->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order2->id,
                'previous_status' => 'pending_payment',
                'current_status' => 'waiting_verification',
                'note' => 'Pelanggan telah mengunggah bukti pembayaran.',
                'changed_by' => $customer->id,
            ]);

            Payment::create([
                'order_id' => $order2->id,
                'destination_bank' => 'BCA (123-456-7890 a/n Little Joy)',
                'sender_bank' => 'Mandiri',
                'account_holder_name' => 'Customer Utama',
                'amount' => 575000,
                'transfer_date' => $orderDate2->format('Y-m-d'),
                'proof_path' => 'proofs/sample_proof.jpg',
                'verification_status' => 'waiting_verification',
            ]);

            // Order 3: Completed (Fully processed with stock movements)
            $orderDate3 = now()->subDays(3);
            $targetProduct = $products->skip(8)->first();
            $qty = 1;
            
            $order3 = Order::create([
                'order_number' => $generateOrderNumber($orderDate3->format('Y-m-d'), 3),
                'user_id' => $customer->id,
                'order_date' => $orderDate3,
                'delivery_date' => now()->subDays(1),
                'recipient_name' => 'Budi Santoso',
                'recipient_phone' => '08999999999',
                'delivery_address' => 'Perumahan Puri Indah Blok C2 No. 5, Kembangan, Jakarta Barat',
                'greeting_message' => 'Selamat atas Pembukaan Toko Baru! Semoga sukses selalu.',
                'subtotal' => $targetProduct->price * $qty,
                'delivery_fee' => 25000,
                'total' => ($targetProduct->price * $qty) + 25000,
                'payment_status' => 'verified',
                'order_status' => 'completed',
                'completed_at' => now()->subDays(1),
            ]);

            OrderItem::create([
                'order_id' => $order3->id,
                'product_id' => $targetProduct->id,
                'product_name' => $targetProduct->name,
                'unit_price' => $targetProduct->price,
                'quantity' => $qty,
                'subtotal' => $targetProduct->price * $qty,
            ]);

            // History entries
            OrderStatusHistory::create([
                'order_id' => $order3->id,
                'previous_status' => null,
                'current_status' => 'pending_payment',
                'note' => 'Pesanan berhasil dibuat.',
                'changed_by' => $customer->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order3->id,
                'previous_status' => 'pending_payment',
                'current_status' => 'waiting_verification',
                'note' => 'Pelanggan telah mengunggah bukti pembayaran.',
                'changed_by' => $customer->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order3->id,
                'previous_status' => 'waiting_verification',
                'current_status' => 'paid',
                'note' => 'Pembayaran diverifikasi oleh operator.',
                'changed_by' => $operator->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order3->id,
                'previous_status' => 'paid',
                'current_status' => 'processing',
                'note' => 'Pesanan sedang diproses dan dirangkai.',
                'changed_by' => $operator->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order3->id,
                'previous_status' => 'processing',
                'current_status' => 'ready',
                'note' => 'Rangkaian bunga siap dikirim.',
                'changed_by' => $operator->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order3->id,
                'previous_status' => 'ready',
                'current_status' => 'shipped',
                'note' => 'Pesanan dikirimkan oleh kurir.',
                'changed_by' => $operator->id,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order3->id,
                'previous_status' => 'shipped',
                'current_status' => 'completed',
                'note' => 'Pesanan berhasil diterima oleh penerima.',
                'changed_by' => $operator->id,
            ]);

            Payment::create([
                'order_id' => $order3->id,
                'destination_bank' => 'BCA (123-456-7890 a/n Little Joy)',
                'sender_bank' => 'BCA',
                'account_holder_name' => 'Budi Santoso',
                'amount' => ($targetProduct->price * $qty) + 25000,
                'transfer_date' => $orderDate3->format('Y-m-d'),
                'proof_path' => 'proofs/sample_proof.jpg',
                'verification_status' => 'verified',
                'verified_by' => $operator->id,
                'verified_at' => now()->subDays(2),
            ]);

            // Stock movement and reduction
            $stockBefore = $targetProduct->stock;
            $stockAfter = $stockBefore - $qty;
            $targetProduct->update(['stock' => $stockAfter]);

            StockMovement::create([
                'product_id' => $targetProduct->id,
                'movement_type' => 'out',
                'quantity' => $qty,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => 'Order',
                'reference_id' => $order3->id,
                'note' => "Pengurangan stok otomatis setelah pembayaran pesanan #{$order3->order_number} diverifikasi.",
                'created_by' => $operator->id,
            ]);
        }
    }
}
