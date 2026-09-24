<?php

namespace App\Services;

class RqiScoringEngine
{
    /**
     * Calculate item score taking reverse scoring into account.
     */
    public function calculateItemScore(int $rawValue, string $direction = 'normal'): int
    {
        if ($direction === 'reverse') {
            return 6 - $rawValue;
        }

        return $rawValue;
    }

    /**
     * Determine RQI category level based on total raw score (15 - 75).
     */
    public function getCategoryLevel(float $totalScore): array
    {
        if ($totalScore >= 65) {
            return [
                'level' => 5,
                'name' => 'Level 5: Enlightened Soul (Jiwa Terpancar Sempurna)',
                'short_name' => 'Enlightened Soul',
                'level_code' => 'ENLIGHTENED_SOUL',
                'score_range' => '65 – 75',
                'description' => 'Level puncak RQ! Integritas moral mutlak di ruang privat maupun publik, pasrah total ke Tuhan, dan vibes-nya bener-bener bawa keberkahan buat orang sekitar.'
            ];
        }

        if ($totalScore >= 54) {
            return [
                'level' => 4,
                'name' => 'Level 4: Mindful Youth (Jiwa Tenang & Terjaga)',
                'short_name' => 'Mindful Youth',
                'level_code' => 'MINDFUL_YOUTH',
                'score_range' => '54 – 64',
                'description' => 'Udah zen banget. Self-awareness tinggi, anti-prokrastinasi, dan ibadah berasa jadi mental detox yang bikin adem. God Light mulai aktif ngebimbing pilihan hidup.'
            ];
        }

        if ($totalScore >= 41) {
            return [
                'level' => 3,
                'name' => 'Level 3: Developing Soul (Jiwa Berproses Stabil)',
                'short_name' => 'Developing Soul',
                'level_code' => 'DEVELOPING_SOUL',
                'score_range' => '41 – 53',
                'description' => 'On track! Fondasi God Spot dan ketaatan ibadah udah lumayan konsisten. Akhlak sosial mulai kerasa green flag-nya, tinggal ngelatih ketenangan pas lagi banyak tekanan.'
            ];
        }

        if ($totalScore >= 27) {
            return [
                'level' => 2,
                'name' => 'Level 2: Awakening Pilgrim (Jiwa Mulai Tergugah)',
                'short_name' => 'Awakening Pilgrim',
                'level_code' => 'AWAKENING_PILGRIM',
                'score_range' => '27 – 40',
                'description' => 'Mulai sadar kalau hidup butuh arah yang bener. Sadar diri dan niat ibadah mulai tumbuh, tapi rem batin masih sering jebol kalau kena godaan atau stres harian.'
            ];
        }

        return [
            'level' => 1,
            'name' => 'Level 1: Spiritual Lowbat (Jiwa Kelelahan)',
            'short_name' => 'Spiritual Lowbat',
            'level_code' => 'SPIRITUAL_LOWBAT',
            'score_range' => '15 – 26',
            'description' => 'Batin kamu lagi burnout parah dan kecanduan distraksi digital. Koneksi God Spot lowbat, gampang insecure, dan butuh digital-spiritual detox secepatnya.'
        ];
    }

    /**
     * Calculate WHO-5 index score and screening category (0 - 25 raw score).
     */
    public function calculateWho5Score(int $rawScore): array
    {
        $rawScore = max(0, min(25, $rawScore));
        $percentage = $rawScore * 4;

        if ($rawScore >= 20) {
            $cat = [
                'level' => 5,
                'name' => 'Level 5: Thriving & Energetic (Super Bahagia)',
                'short_name' => 'Thriving & Energetic',
                'percentage_range' => '80% – 100%',
                'description' => 'Mental well-being kamu lagi puncak-puncak persiapannya! Super bersemangat, ceria, tidur nyenyak, dan benar-benar bisa menikmati setiap momen hidup dengan penuh rasa syukur.',
                'badge_color' => 'bg-emerald-500 text-white border-emerald-400',
                'needs_attention' => false
            ];
        } elseif ($rawScore >= 16) {
            $cat = [
                'level' => 4,
                'name' => 'Level 4: Good Vibe & Fresh (Sejahtera)',
                'short_name' => 'Good Vibe & Fresh',
                'percentage_range' => '64% – 76%',
                'description' => 'Keren! Vibes kamu positif, energi buat jalanin aktivitas harian aman, dan kualitas tidur juga oke. Hidup terasa jauh lebih bermakna dan flow-nya dapet banget.',
                'badge_color' => 'bg-sky-600 text-white border-sky-400',
                'needs_attention' => false
            ];
        } elseif ($rawScore >= 11) {
            $cat = [
                'level' => 3,
                'name' => 'Level 3: Moderate Well-Being (Cukup Stabil)',
                'short_name' => 'Moderate Well-Being',
                'percentage_range' => '44% – 56%',
                'description' => 'Kondisi mental kamu lumayan stabil, tapi kadang masih gampang goyah kalau lagi banyak tekanan. Masih bisa enjoy hari-hari walau sesekali ngerasa capek fisik atau pikiran.',
                'badge_color' => 'bg-amber-500 text-slate-950 border-amber-300',
                'needs_attention' => false
            ];
        } elseif ($rawScore >= 6) {
            $cat = [
                'level' => 2,
                'name' => 'Level 2: Low Energy (Sering Cemas)',
                'short_name' => 'Low Energy',
                'percentage_range' => '24% – 40%',
                'description' => 'Lagi sering overthinking dan gampang cemas. Baterai sosial dan fisik kamu sering habis sebelum waktunya. Kurang-kurangin memforsir diri dan perbanyak me time yang positif.',
                'badge_color' => 'bg-orange-600 text-white border-orange-400',
                'needs_attention' => true
            ];
        } else {
            $cat = [
                'level' => 1,
                'name' => 'Level 1: Mental Exhausted (Burnout Parah)',
                'short_name' => 'Mental Exhausted',
                'percentage_range' => '0% – 20%',
                'description' => 'Mental kamu lagi di titik terendah alias burnout parah. Energi habis, susah tidur nyenyak, dan hari-hari terasa berat banget. Saatnya prioritize rest dan cari support system!',
                'badge_color' => 'bg-rose-600 text-white border-rose-400',
                'needs_attention' => true
            ];
        }

        return array_merge([
            'raw_score' => $rawScore,
            'max_raw' => 25,
            'percentage' => $percentage,
            'screening_note' => $cat['description']
        ], $cat);
    }
}
