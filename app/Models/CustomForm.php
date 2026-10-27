<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CustomForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'header_image',
        'status',
        'is_public',
        'access_code',
        'closes_at',
        'success_message',
        'created_by',
        // Certificate Settings
        'has_certificate',
        'certificate_status',
        'certificate_released_at',
        'certificate_event_name',
        'certificate_event_date',
        'certificate_event_location',
        'certificate_total_jp',
        'certificate_number_pattern',
        'certificate_signer1_name',
        'certificate_signer1_role',
        'certificate_signer1_signature',
        'certificate_signer2_name',
        'certificate_signer2_role',
        'certificate_signer2_signature',
        'certificate_partner_logo',
        'certificate_curriculum',
        'certificate_name_field_id',
        'certificate_phone_field_id',
        'certificate_inst_field_id',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'has_certificate' => 'boolean',
        'closes_at' => 'datetime',
        'certificate_released_at' => 'datetime',
    ];

    public function fields()
    {
        return $this->hasMany(CustomFormField::class, 'custom_form_id')->orderBy('order', 'asc');
    }

    public function responses()
    {
        return $this->hasMany(CustomFormResponse::class, 'custom_form_id')->latest();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function nameField()
    {
        return $this->belongsTo(CustomFormField::class, 'certificate_name_field_id');
    }

    public function phoneField()
    {
        return $this->belongsTo(CustomFormField::class, 'certificate_phone_field_id');
    }

    public function instField()
    {
        return $this->belongsTo(CustomFormField::class, 'certificate_inst_field_id');
    }

    public function isAcceptingResponses(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }
        if ($this->closes_at && now()->gt($this->closes_at)) {
            return false;
        }
        return true;
    }

    public function isCertificateReleased(): bool
    {
        if (!$this->has_certificate) {
            return false;
        }
        return $this->certificate_status === 'released';
    }

    public function getParsedCurriculumAttribute(): array
    {
        if (empty($this->certificate_curriculum)) {
            return [];
        }
        if (is_array($this->certificate_curriculum)) {
            return $this->certificate_curriculum;
        }
        $decoded = json_decode($this->certificate_curriculum, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return [];
    }

    public function getCalculatedTotalJpAttribute(): string
    {
        if (!empty($this->certificate_total_jp)) {
            return $this->certificate_total_jp;
        }
        $curriculum = $this->parsed_curriculum;
        if (empty($curriculum)) {
            return '32 JP';
        }

        $sum = 0;
        foreach ($curriculum as $item) {
            $jpVal = $item['jp'] ?? 0;
            if (preg_match('/(\d+)/', (string)$jpVal, $m)) {
                $sum += (int)$m[1];
            }
        }
        return $sum > 0 ? "{$sum} Jam Pelajaran (JP)" : '32 JP';
    }

    public function generateCertificateNumber(int $seq): string
    {
        $seqPadded = str_pad((string)$seq, 3, '0', STR_PAD_LEFT);
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $monthRoman = $romanMonths[(int)date('n')] ?? date('m');
        $year = date('Y');

        if (!empty($this->certificate_number_pattern)) {
            return str_replace(
                ['{no}', '{bulan}', '{m}', '{tahun}', '{Y}'],
                [$seqPadded, $monthRoman, $monthRoman, $year, $year],
                $this->certificate_number_pattern
            );
        }

        $slugSlug = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->slug), 0, 8));
        return "{$seqPadded}/SERT-RQ/{$slugSlug}/{$monthRoman}/{$year}";
    }

    public function getPublicUrlAttribute(): string
    {
        return route('public.form.show', $this->slug);
    }

    public function getCertificateClaimUrlAttribute(): string
    {
        return route('public.form.claim_certificate', $this->slug);
    }

    public function getFormattedSuccessMessageAttribute(): string
    {
        $msg = $this->success_message ?: 'Jawaban Anda telah kami terima dan dicatat dengan aman di sistem Ruhiology Institute. Terima kasih atas partisipasi dan waktu yang telah Anda berikan.';
        return static::linkify($msg);
    }

    public function getFormattedDescriptionAttribute(): string
    {
        return static::linkify($this->description);
    }

    public static function linkify(?string $text): string
    {
        if (empty($text)) {
            return '';
        }

        $pattern = '/((?:https?:\/\/|www\.|chat\.whatsapp\.com|wa\.me)[^\s<]+)/i';
        $parts = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        $output = '';
        foreach ($parts as $part) {
            if (preg_match('/^(?:https?:\/\/|www\.|chat\.whatsapp\.com|wa\.me)/i', $part)) {
                $rawUrl = htmlspecialchars_decode($part, ENT_QUOTES);
                $href = $rawUrl;
                if (!preg_match('/^https?:\/\//i', $href)) {
                    $href = 'https://' . $href;
                }
                $escapedHref = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
                $escapedText = htmlspecialchars($rawUrl, ENT_QUOTES, 'UTF-8');

                if (str_contains($rawUrl, 'chat.whatsapp.com') || str_contains($rawUrl, 'wa.me') || str_contains($rawUrl, 'api.whatsapp.com')) {
                    $output .= '<span class="block my-3 text-center">'
                        . '<a href="' . $escapedHref . '" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-200 transition hover:scale-[1.02]">'
                        . '<svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>'
                        . '<span>Masuk ke Grup WhatsApp Peserta ↗</span>'
                        . '</a>'
                        . '</span>';
                } else {
                    $output .= '<a href="' . $escapedHref . '" target="_blank" rel="noopener noreferrer" class="text-primary-600 hover:text-primary-800 underline font-semibold break-all inline-flex items-center gap-1">'
                        . $escapedText
                        . ' <svg class="w-3.5 h-3.5 inline shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>'
                        . '</a>';
                }
            } else {
                $output .= nl2br(htmlspecialchars($part, ENT_QUOTES, 'UTF-8'));
            }
        }

        return $output;
    }

    public static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count;
            $count++;
        }
        return $slug;
    }
}
