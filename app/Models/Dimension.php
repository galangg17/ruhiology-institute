<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'instrument_id',
        'code',
        'name',
        'name_en',
        'description',
        'description_en',
        'order',
    ];

    public function getLocalizedNameAttribute(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $this->name ?? '';
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        if (app()->getLocale() === 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        return $this->description ?? '';
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class)->orderBy('order');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }
}
