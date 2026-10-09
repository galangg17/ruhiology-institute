<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantSubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'detail_label',
        'order',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(ParticipantCategory::class, 'category_id');
    }
}
