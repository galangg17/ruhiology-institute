<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendingInstitution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'category', 'location', 'notes', 'status'];
}
