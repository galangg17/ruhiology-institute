<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'record_type',
        'record_id',
        'changes_json',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
