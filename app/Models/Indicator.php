<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'code',
        'name',
        'description',
        'order',
    ];

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
