<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'icon',
        'description',
        'order',
        'status',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class, 'category', 'name');
    }

    public static function getAllActive()
    {
        try {
            return static::where('status', 'active')->orderBy('order', 'asc')->get();
        } catch (\Throwable $e) {
            return collect([
                (object)['id' => 1, 'name' => 'Pelajar', 'icon' => '🏫'],
                (object)['id' => 2, 'name' => 'Mahasiswa/i', 'icon' => '🎓'],
                (object)['id' => 3, 'name' => 'Umum', 'icon' => '👤'],
            ]);
        }
    }
}
