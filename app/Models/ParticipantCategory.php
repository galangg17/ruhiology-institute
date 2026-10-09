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

    public function subCategories()
    {
        return $this->hasMany(ParticipantSubCategory::class, 'category_id')->where('status', 'active')->orderBy('order', 'asc');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'category', 'name');
    }

    public static function getAllActive()
    {
        try {
            return static::where('status', 'active')
                ->with(['subCategories'])
                ->orderBy('order', 'asc')
                ->get();
        } catch (\Throwable $e) {
            return collect([]);
        }
    }
}
