<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['country_id', 'code', 'name', 'status'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function regencies()
    {
        return $this->hasMany(Regency::class);
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
