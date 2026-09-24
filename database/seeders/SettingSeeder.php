<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'institute_name' => 'RUHIOLOGY INSTITUTE',
            'tagline' => 'Mengenal Diri. Mengembangkan Potensi. Menumbuhkan Ruh.',
            'founder_name' => 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D.',
            'founder_title' => 'Guru Besar Psikologi Pendidikan UIN Sulthan Thaha Saifuddin Jambi',
            
            // Hero Section Settings & Photos
            'hero_eyebrow' => '✨ Ruhiology Institute · Jambi, Indonesia',
            'hero_headline' => "Mengenal Diri.\nMengembangkan Potensi.\nMenumbuhkan Ruh.",
            'hero_narrative' => 'Ruhiology Institute adalah ekosistem pendidikan, assessment, training, konsultasi, dan pengembangan yang berfokus pada Kecerdasan Ruhiologi (Ruhiology Quotient / RQ) untuk membentuk manusia berkarakter paripurna.',
            'hero_card_image' => asset('images/settings/hero_card_1790081685.jpg'),
            'hero_card_badge_top' => '🌾 Ekosistem & Riset Ruhiologi',
            'hero_card_badge_bottom' => 'Pusat Inteligensi Ruh',
            'hero_card_title' => 'Menumbuhkan Potensi Manusia Paripurna',
            'hero_card_subtitle' => 'Pengembangan karakter berbasis nilai wahyu dan metodologi psikometri ilmiah.',
            
            // About Section Settings & Photos
            'about_image' => asset('images/settings/about_1790081685.jpg'),
            'about_image_tag' => 'DOKUMENTASI RISET & KARYA RUHIOLOGI',
            'about_image_caption' => 'Prof. Dr. Iskandar Nazari merumuskan konsep Ruhiology Quotient (RQ) sebagai navigasi potensi manusia.',
            'about_badge' => 'Filosofi & Kesadaran Ruh',
            'about_title' => 'Bukan Sekadar Ilmu: Menempatkan Ruh Sebagai Pusat Peradaban',
            'about_description' => 'Ruhiologi dipopulerkan dan dirumuskan sebagai respons atas paradigma psikologi modern yang cenderung membatasi potensi manusia sebatas pada aspek rasional-kognitif (IQ) dan sosio-emosional (EQ).',
            'about_quote' => 'Ruh bukan sekadar dorongan mistis, melainkan pusat inteligensi tertinggi (Ruhiology Quotient) yang mengendalikan orientasi nilai, kebersihan batin, intuisi kebenaran, serta komitmen etis dalam kehidupan nyata.',
            'about_feature_1' => 'Integrasi Wahyu & Sains',
            'about_feature_2' => 'Purifikasi Batin (Tazkiyah)',
            'about_feature_3' => 'Resiliensi Spiritual',

            // Contact & Social
            'contact_email' => 'info@ruhiologyinstitute.com',
            'contact_phone' => '+62 812-7411-0000',
            'contact_whatsapp' => '6281274110000',
            'address' => 'Kampus UIN Sulthan Thaha Saifuddin Jambi & Gedung Pusat Ruhiology Institute, Kota Jambi',
            'social_instagram' => 'https://instagram.com/ruhiologyinstitute',
            'social_youtube' => 'https://youtube.com/@ruhiologyinstitute',
            'social_facebook' => 'https://facebook.com/ruhiologyinstitute',
            'meta_title' => 'Ruhiology Institute - Pusat Ekosistem Kecerdasan Ruhiologi (RQ)',
            'meta_description' => 'Platform resmi pengembangan, assessment, pelatihan, publikasi, dan konsultasi Kecerdasan Ruhiologi (Ruhiology Quotient) oleh Prof. Dr. Iskandar Nazari.',
            'store_currency' => 'IDR',
            'payment_bank_name' => 'Bank Mandiri',
            'payment_account_number' => '110-00-1988273-1',
            'payment_account_holder' => 'RUHIOLOGY INSTITUTE INDONESIA',
        ];

        foreach ($settings as $key => $val) {
            Setting::set($key, $val, 'general');
        }
    }
}
