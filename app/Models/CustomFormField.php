<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_form_id',
        'label',
        'description',
        'field_type',
        'options',
        'is_required',
        'placeholder',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'order' => 'integer',
    ];

    public function form()
    {
        return $this->belongsTo(CustomForm::class, 'custom_form_id');
    }

    public function answers()
    {
        return $this->hasMany(CustomFormResponseAnswer::class, 'field_id');
    }
}
