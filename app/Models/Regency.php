<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['province_id', 'code', 'name', 'type', 'status'];

    protected $appends = ['formatted_name'];

    public function getFormattedNameAttribute(): string
    {
        if (empty($this->name)) return '';
        if (empty($this->type)) return $this->name;
        if (\Illuminate\Support\Str::startsWith($this->name, [$this->type, 'Kota', 'Kabupaten', 'Kab.'])) {
            return $this->name;
        }
        return $this->type . ' ' . $this->name;
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function universities()
    {
        return $this->hasMany(University::class);
    }
}
