<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'total_score',
        'rqi_score',
        'category_name',
        'max_score',
        'percentage',
        'overall_interpretation',
        'who5_raw_score',
        'who5_percentage',
        'who5_screening_note',
        'pre_post_diff',
        'scoring_version',
        'snapshot_data',
    ];

    protected $casts = [
        'snapshot_data' => 'array',
    ];

    public function submission()

    {
        return $this->belongsTo(AssessmentSubmission::class, 'submission_id');
    }

    public function dimensionResults()
    {
        return $this->hasMany(ResultDimension::class, 'result_id');
    }
}
