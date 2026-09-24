<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'image',
        'trainer',
        'duration',
        'location',
        'is_online',
        'price',
        'quota',
        'status',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function batches()
    {
        return $this->hasMany(TrainingBatch::class, 'training_id');
    }

    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class, 'training_id');
    }
}
