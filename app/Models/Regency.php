<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regency extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['province_id', 'code', 'name', 'type', 'status'];

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
