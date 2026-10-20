<?php

namespace App\Services;

use App\Models\Dimension;
use App\Models\Instrument;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Curated academic dictionary for Ruhiology & Psychological assessment terms.
     */
    protected static array $curatedDictionary = [
        // Dimensions
        'Pengenalan & Kesadaran Diri Hakiki' => 'Authentic Self-Awareness & Inner Consciousness',
        'Pengenalan Ketuhanan (God Spot)' => 'Divine Recognition (The God Spot)',
        'Ketaatan Ibadah' => 'Devotion in Worship & Spiritual Practice',
        'Perubahan Perilaku & Akhlak Karimah' => 'Behavioral Transformation & Noble Ethics',
        'Kesadaran Puncak Ketuhanan (God Light & Muraqabah)' => 'Peak Divine Consciousness (God Light & Muraqabah)',
        'Dimensi ke-6: Indeks Kesejahteraan Mental (WHO-5)' => 'Dimension 6: Mental Well-Being Index (WHO-5)',
        'Kesadaran Ruhaniah / Spiritual Awareness' => 'Spiritual Consciousness & Awareness',
        'Ketenangan Batin & Regulasi Diri' => 'Inner Peace & Self-Regulation',
        'Moralitas & Integritas Karakter' => 'Morality & Character Integrity',
        'Empati & Hubungan Transendental' => 'Empathy & Transcendental Relationships',
        'Orientasi Makna & Tujuan Hidup' => 'Meaning Orientation & Life Purpose',

        // Options (Likert Scales)
        'Sepanjang Waktu' => 'All of the time',
        'Sebagian Besar Waktu' => 'Most of the time',
        'Lebih dari Separuh Waktu' => 'More than half of the time',
        'Kurang dari Separuh Waktu' => 'Less than half of the time',
        'Kadang-kadang' => 'Some of the time',
        'Jarang' => 'Rarely',
        'Tidak Pernah' => 'At no time',
        'Sangat Sesuai' => 'Strongly Agree',
        'Sesuai' => 'Agree',
        'Kurang Sesuai' => 'Disagree',
        'Sangat Tidak Sesuai' => 'Strongly Disagree',
        'Netral' => 'Neutral',

        // Instruments
        'Instrumen Ruhiology Quotient (RQI) & WHO-5' => 'Ruhiology Quotient (RQI) & WHO-5 Mental Well-Being Assessment',
        'Asesmen Profil Kecerdasan Ruhaniyah Generasi Muda (RQI) dan Indeks Kesejahteraan Mental WHO-5' => 'Youth Spiritual Quotient Profile (RQI) and WHO-5 Well-Being Assessment Instrument',
    ];

    /**
     * Curated official translations for the 20 Instrument 1 questions.
     */
    public static array $curatedQuestions = [
        1 => "I am aware that I am not merely physical or defined by social status, but a soul undergoing growth, thus I perceive daily activities as a medium for spiritual cultivation.",
        2 => "When mental fatigue or device distractions (scrolling for hours) arise, I know when to apply the 'emergency brake' so my time and energy are not wasted.",
        3 => "When I feel discouraged, left behind, or insecure seeing others' achievements on social media, my heart remains composed knowing my intrinsic value is not determined by digital validation.",
        4 => "Amidst the rush and stress of daily routines, there is a 'serene inner sanctuary' within me that naturally yearns for peace through the remembrance of God.",
        5 => "When experiencing unexpected blessings or breakthroughs in life, my initial spontaneous response is deep humility and gratitude to God.",
        6 => "I feel God's presence as my true protector and guide, so I am never overwhelmed by anxiety when confronting uncertain futures or academic/career challenges.",
        7 => "I perform mandatory worship not merely as a monotonous routine or burden, but as a genuine necessity for my soul to stay aligned and recharged.",
        8 => "I regularly dedicate specific personal time to reflect, pray, or meditate intimately, feeling disconnected from spiritual vitality whenever I miss it.",
        9 => "My spiritual devotion serves as an anchor that prevents me from engaging in harmful conduct, dishonesty, or immoral temptations.",
        10 => "I feel an inner urge to be kind, polite, and forgiving toward parents, teachers, and peers, even when their attitudes disappoint me.",
        11 => "I consistently uphold honesty and integrity in academic work, examinations, and financial matters, regardless of whether anyone is monitoring me.",
        12 => "When conflicts, anger, or peer pressure tempt me to act aggressively, my spiritual consciousness immediately reminds me to exercise restraint.",
        13 => "I feel that God is constantly witnessing (Muraqabah) all my hidden intentions, private thoughts, and confidential conduct.",
        14 => "I experience profound meaning and spiritual flow when serving, helping underprivileged people, or contributing to society for the sake of God.",
        15 => "I feel that every life test, hardship, or disappointment is a loving divine purification designed to elevate my spiritual resilience.",
        // WHO-5 Official Items (Q16 - Q20)
        16 => "I have felt cheerful and in good spirits.",
        17 => "I have felt calm and relaxed.",
        18 => "I have felt active and vigorous.",
        19 => "I woke up feeling fresh and rested.",
        20 => "My daily life has been filled with things that interest me.",
    ];

    /**
     * Get guaranteed English translation for question with intelligent keyword matching.
     */
    public static function getQuestionEnglishFallback(Question $q): string
    {
        // 1. Match by order
        if (!empty($q->order) && isset(self::$curatedQuestions[$q->order])) {
            return self::$curatedQuestions[$q->order];
        }

        // 2. Match by id
        if (isset(self::$curatedQuestions[$q->id])) {
            return self::$curatedQuestions[$q->id];
        }

        // 3. Match by content keywords in question_text
        $text = mb_strtolower($q->question_text ?? '');
        if (str_contains($text, 'bukan sekadar fisik') || str_contains($text, 'pembentukan jiwa')) {
            return self::$curatedQuestions[1];
        }
        if (str_contains($text, 'godaan gawai') || str_contains($text, 'rem darurat')) {
            return self::$curatedQuestions[2];
        }
        if (str_contains($text, 'pencapaian orang lain') || str_contains($text, 'validasi dunia maya')) {
            return self::$curatedQuestions[3];
        }
        if (str_contains($text, 'ruang sunyi') || str_contains($text, 'mengingat tuhan')) {
            return self::$curatedQuestions[4];
        }
        if (str_contains($text, 'nikmat yang tak terduga') || str_contains($text, 'spontan yang muncul') || str_contains($text, 'keberhasilan')) {
            return self::$curatedQuestions[5];
        }
        if (str_contains($text, 'pelindung sejati') || str_contains($text, 'menghadapi masa depan') || str_contains($text, 'kecemasan')) {
            return self::$curatedQuestions[6];
        }
        if (str_contains($text, 'menunaikan ibadah') || str_contains($text, 'kebutuhan jiwa') || str_contains($text, 'formalitas')) {
            return self::$curatedQuestions[7];
        }
        if (str_contains($text, 'mengevaluasi diri') || str_contains($text, 'ada yang hampa') || str_contains($text, 'merenung')) {
            return self::$curatedQuestions[8];
        }
        if (str_contains($text, 'kedisiplinan ibadah') || str_contains($text, 'benteng otomatis') || str_contains($text, 'perilaku menyimpang')) {
            return self::$curatedQuestions[9];
        }
        if (str_contains($text, 'bersikap santun') || str_contains($text, 'memaafkan') || str_contains($text, 'mengecewakan')) {
            return self::$curatedQuestions[10];
        }
        if (str_contains($text, 'menjaga kejujuran') || str_contains($text, 'meski tidak ada yang mengawasi') || str_contains($text, 'integritas')) {
            return self::$curatedQuestions[11];
        }
        if (str_contains($text, 'amarah memuncak') || str_contains($text, 'menahan diri') || str_contains($text, 'konflik')) {
            return self::$curatedQuestions[12];
        }
        if (str_contains($text, 'selalu mengawasi') || str_contains($text, 'muraqabah') || str_contains($text, 'ruang privat')) {
            return self::$curatedQuestions[13];
        }
        if (str_contains($text, 'merasakan kehadiran tuhan') || str_contains($text, 'sendirian di kamar') || str_contains($text, 'larut malam')) {
            return self::$curatedQuestions[14];
        }
        if (str_contains($text, 'proses yang jujur') || str_contains($text, 'ujian hidup') || str_contains($text, 'keberkahan')) {
            return self::$curatedQuestions[15];
        }
        if (str_contains($text, 'bersemangat, ceria') || str_contains($text, 'ceria dan bersemangat') || str_contains($text, 'termotivasi')) {
            return self::$curatedQuestions[16];
        }
        if (str_contains($text, 'tenang, damai') || str_contains($text, 'tenang dan rileks') || str_contains($text, 'tidak terbebani')) {
            return self::$curatedQuestions[17];
        }
        if (str_contains($text, 'aktif, segar') || str_contains($text, 'aktif dan berenergi') || str_contains($text, 'bertenaga')) {
            return self::$curatedQuestions[18];
        }
        if (str_contains($text, 'bangun tidur pagi') || str_contains($text, 'segar dan beristirahat') || str_contains($text, 'istirahat yang cukup')) {
            return self::$curatedQuestions[19];
        }
        if (str_contains($text, 'bermakna dan memicu') || str_contains($text, 'hal-hal yang menarik') || str_contains($text, 'antusiasme')) {
            return self::$curatedQuestions[20];
        }

        return '';
    }

    /**
     * Translate text from source to target language.
     */
    public static function translate(string $text, string $source = 'id', string $target = 'en'): string
    {
        $trimmed = trim($text);
        if ($trimmed === '') {
            return '';
        }

        // 1. Check curated dictionary first
        if (isset(self::$curatedDictionary[$trimmed])) {
            return self::$curatedDictionary[$trimmed];
        }

        // 2. Query free translation endpoint
        try {
            $url = 'https://translate.googleapis.com/translate_a/single';
            $response = Http::timeout(6)->get($url, [
                'client' => 'gtx',
                'sl' => $source,
                'tl' => $target,
                'dt' => 't',
                'q' => $trimmed,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data) && isset($data[0]) && is_array($data[0])) {
                    $translated = '';
                    foreach ($data[0] as $segment) {
                        if (isset($segment[0])) {
                            $translated .= $segment[0];
                        }
                    }
                    if (!empty($translated)) {
                        return trim($translated);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Auto-translation failed for text: {$trimmed}. Error: " . $e->getMessage());
        }

        // Fallback to original text if translation service is unavailable
        return $trimmed;
    }

    /**
     * Automatically translate all existing Instruments, Dimensions, Questions, and Options.
     */
    public static function translateAllInstruments(): array
    {
        $stats = [
            'instruments' => 0,
            'dimensions' => 0,
            'questions' => 0,
            'options' => 0,
        ];

        // 1. Instruments
        $instruments = Instrument::all();
        foreach ($instruments as $inst) {
            $inst->name_en = self::translate($inst->name);
            if ($inst->description) {
                $inst->description_en = self::translate($inst->description);
            }
            $inst->save();
            $stats['instruments']++;
        }

        // 2. Dimensions
        $dimensions = Dimension::all();
        foreach ($dimensions as $dim) {
            $dim->name_en = self::translate($dim->name);
            if ($dim->description) {
                $dim->description_en = self::translate($dim->description);
            }
            $dim->save();
            $stats['dimensions']++;
        }

        // 3. Questions
        $questions = Question::all();
        foreach ($questions as $q) {
            if (isset(self::$curatedQuestions[$q->id])) {
                $q->question_text_en = self::$curatedQuestions[$q->id];
            } else {
                $q->question_text_en = self::translate($q->question_text);
            }
            $q->save();
            $stats['questions']++;
        }

        // 4. Options
        $options = QuestionOption::all();
        foreach ($options as $opt) {
            $opt->option_text_en = self::translate($opt->option_text);
            $opt->save();
            $stats['options']++;
        }

        return $stats;
    }
}
