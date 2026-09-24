<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'batch_name',
        'start_date',
        'end_date',
        'registration_open',
        'registration_close',
        'quota',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_open' => 'datetime',
        'registration_close' => 'datetime',
    ];

    public function training()
    {
        return $this->belongsTo(TrainingProgram::class, 'training_id');
    }

    public function registrations()
    {
        return $this->hasMany(TrainingRegistration::class, 'batch_id');
    }

    public function isOpen(): bool
    {
        if ($this->status !== 'open') return false;
        $now = now();
        if ($this->registration_open && $now->lt($this->registration_open)) return false;
        if ($this->registration_close && $now->gt($this->registration_close)) return false;
        return true;
    }
}
