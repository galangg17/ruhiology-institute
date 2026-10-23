<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CustomForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'header_image',
        'status',
        'is_public',
        'access_code',
        'closes_at',
        'success_message',
        'created_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'closes_at' => 'datetime',
    ];

    public function fields()
    {
        return $this->hasMany(CustomFormField::class, 'custom_form_id')->orderBy('order', 'asc');
    }

    public function responses()
    {
        return $this->hasMany(CustomFormResponse::class, 'custom_form_id')->latest();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isAcceptingResponses(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }
        if ($this->closes_at && now()->gt($this->closes_at)) {
            return false;
        }
        return true;
    }

    public function getPublicUrlAttribute(): string
    {
        return route('public.form.show', $this->slug);
    }

    public static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count;
            $count++;
        }
        return $slug;
    }
}
