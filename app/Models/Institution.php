<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'email',
        'phone',
        'address',
        'contact_person',
        'status',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
