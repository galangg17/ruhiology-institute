<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'training_id',
        'batch_id',
        'user_id',
        'participant_name',
        'email',
        'phone',
        'institution',
        'price',
        'payment_status',
        'registration_status',
        'payment_proof_path',
        'admin_notes',
    ];

    public function training()
    {
        return $this->belongsTo(TrainingProgram::class, 'training_id');
    }

    public function batch()
    {
        return $this->belongsTo(TrainingBatch::class, 'batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
