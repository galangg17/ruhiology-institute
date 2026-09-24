<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'dimension_id',
        'score',
        'max_score',
        'percentage',
        'interpretation',
    ];

    public function result()
    {
        return $this->belongsTo(AssessmentResult::class, 'result_id');
    }

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }
}
