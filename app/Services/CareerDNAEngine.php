<?php
declare(strict_types=1);

namespace App\Services;

class CareerDNAEngine
{
    private const MAX_SKILL_SCORE = 100;

    public function calculateOverallDnaScore(array $skillsMatrix): float
    {
        if (empty($skillsMatrix)) {
            return 0.0;
        }

        $total = array_sum($skillsMatrix);
        $count = count($skillsMatrix);

        return round(($total / ($count * self::MAX_SKILL_SCORE)) * 100, 2);
    }

    public function updateSkillLevelInMatrix(array $currentMatrix, int $skillId, int $pointsGain): array
    {
        $current = $currentMatrix[$skillId] ?? 0;
        $currentMatrix[$skillId] = min(self::MAX_SKILL_SCORE, $current + $pointsGain);
        return $currentMatrix;
    }

    public function buildDnaSnapshot(array $skillsMatrix): array
    {
        return [
            'skills_matrix' => $skillsMatrix,
            'dna_score' => $this->calculateOverallDnaScore($skillsMatrix),
            'max_skill_score' => self::MAX_SKILL_SCORE,
        ];
    }
}

