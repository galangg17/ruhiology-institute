<?php

namespace App\Services;

use App\Models\Instrument;
use App\Models\Question;
use App\Models\ScoringRule;

class ScoringService
{
    /**
     * Calculate score for a single question response based on raw value and scoring direction.
     */
    public function calculateQuestionScore(Question $question, int $rawValue, ?ScoringRule $rule = null): float
    {
        if ($question->scoring_direction !== 'reverse') {
            return (float) $rawValue;
        }

        if ($rule && !empty($rule->reverse_mapping) && isset($rule->reverse_mapping[$rawValue])) {
            return (float) $rule->reverse_mapping[$rawValue];
        }

        $min = $rule->scale_min ?? 1;
        $max = $rule->scale_max ?? 5;
        return (float) (($max + $min) - $rawValue);
    }

    /**
     * Get overall and dimension interpretation level string.
     */
    public function getInterpretation(float $percentage, ?ScoringRule $rule = null): string
    {
        if ($rule && !empty($rule->interpretation_ranges)) {
            foreach ($rule->interpretation_ranges as $range) {
                if ($percentage >= $range['min'] && $percentage <= $range['max']) {
                    return $range['level'] . (!empty($range['description']) ? ' - ' . $range['description'] : '');
                }
            }
        }

        // Standard Default Interpretation Thresholds
        if ($percentage >= 85) {
            return 'Sangat Tinggi (Optimal) - Potensi Ruhiologi berkembang sangat matang dan konsisten.';
        } elseif ($percentage >= 70) {
            return 'Tinggi (Baik) - Memiliki kesadaran dan kecerdasan Ruhiologi yang solid.';
        } elseif ($percentage >= 50) {
            return 'Sedang (Cukup) - Kesadaran Ruhiologi cukup baik namun memerlukan pendampingan & latihan terstruktur.';
        } else {
            return 'Perlu Pengembangan - Potensi dasar memerlukan penguatan fondasi nilai dan pembiasaan berkelanjutan.';
        }
    }
}
