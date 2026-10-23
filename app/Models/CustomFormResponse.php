<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomFormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_form_id',
        'response_code',
        'ip_address',
        'user_agent',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(CustomForm::class, 'custom_form_id');
    }

    public function answers()
    {
        return $this->hasMany(CustomFormResponseAnswer::class, 'response_id');
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'RSP-' . strtoupper(Str::random(8));
        } while (static::where('response_code', $code)->exists());

        return $code;
    }
}
