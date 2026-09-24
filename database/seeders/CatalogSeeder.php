<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $cat1 = ProductCategory::create([
            'name' => 'Buku Utama Ruhiologi',
            'slug' => 'buku-utama-ruhiologi',
            'description' => 'Karya monograf komprehensif dan teori kecerdasan ruhiologi karya Prof. Dr. Iskandar Nazari.',
        ]);

        $cat2 = ProductCategory::create([
            'name' => 'Panduan & Modul Praktis',
            'slug' => 'panduan-modul-praktis',
            'description' => 'Modul praktis asesmen dan panduan self-coaching ruhiologi.',
        ]);

        $b1 = Product::create([
            'category_id' => $cat1->id,
            'title' => 'Ruhiology Quotient (RQ): Menempatkan Ruh Sebagai Pusat Potensi Kemanusiaan',
            'slug' => 'ruhiology-quotient-menempatkan-ruh-sebagai-pusat-potensi-kemanusiaan',
            'author' => 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.',
            'isbn' => '978-623-9876-01-2',
            'publisher' => 'Ruhiology Institute Press & UIN STS Press',
            'year' => 2024,
            'pages' => 348,
            'description' => 'Buku monumental karya Guru Besar Psikologi Pendidikan UIN Sulthan Thaha Saifuddin Jambi yang menguraikan kerangka epistimologis, dimensi empiris, dan aplikasi praktis Ruhiology Quotient (RQ) dalam dunia pendidikan modern.',
            'price' => 135000.00,
            'stock' => 150,
            'status' => 'published',
        ]);

        $b2 = Product::create([
            'category_id' => $cat2->id,
            'title' => 'Modul Panduan Self-Coaching & Purifikasi Mental Ruhiologi',
            'slug' => 'modul-panduan-self-coaching-purifikasi-mental-ruhiologi',
            'author' => 'Prof. Dr. Iskandar Nazari & Tim Pengembang Institute',
            'isbn' => '978-623-9876-05-9',
            'publisher' => 'Ruhiology Institute Press',
            'year' => 2025,
            'pages' => 180,
            'description' => 'Buku panduan praktis dilengkapi jurnal amalan harian, lembar evaluasi kesadaran ruhiologi, dan metode penanganan konflik batin mandiri.',
            'price' => 85000.00,
            'stock' => 200,
            'status' => 'published',
        ]);

        // Sample Order
        $order = Order::create([
            'order_number' => 'ORD-2026-0001',
            'customer_name' => 'Dr. Farida Ariani',
            'customer_email' => 'farida.ariani@gmail.com',
            'customer_phone' => '081299001122',
            'shipping_address' => 'Jl. Pattimura No. 45, Telanaipura, Kota Jambi 36122',
            'subtotal' => 220000.00,
            'shipping_fee' => 15000.00,
            'total_amount' => 235000.00,
            'payment_status' => 'paid',
            'order_status' => 'processing',
            'notes' => 'Tolong dibungkus rapi dengan bubble wrap.',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $b1->id,
            'product_title' => $b1->title,
            'price' => 135000.00,
            'quantity' => 1,
            'subtotal' => 135000.00,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $b2->id,
            'product_title' => $b2->title,
            'price' => 85000.00,
            'quantity' => 1,
            'subtotal' => 85000.00,
        ]);
    }
}
