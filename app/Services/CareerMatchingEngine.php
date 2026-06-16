<?php

declare(strict_types=1);

namespace App\Services;

/**
 * CareerMatchingEngine
 *
 * محرك يولد قائمة أعلى مسارات مناسبة بناءً على DNA snapshot.
 *
 * الملاحظة: هذا التنفيذ مبدئي للمحرك فقط (لا يعتمد على DB هنا).
 */
final class CareerMatchingEngine
{
    /**
     * @param array $dna JSON-decoded snapshot (مثال: ['skills_matrix'=>['php'=>80], 'dna_score'=>55.5])
     * @return array<int, array{career:string, score:int}>
     */
    public function generateMatches(array $dna): array
    {
        $skills = $dna['skills_matrix'] ?? [];
        if (!is_array($skills)) {
            $skills = [];
        }

        // Prototype mapping: skill_key -> best careers
        // في المرحلة القادمة سيتم استبدالها ببيانات DB + embeddings.
        $careerRules = [
            'Backend Developer' => [
                'required' => ['php', 'mysql', 'rest_api'],
                'weights'  => ['php' => 4, 'mysql' => 3, 'rest_api' => 2],
            ],
            'Software Architect' => [
                'required' => ['system_design', 'rest_api', 'leadership'],
                'weights'  => ['system_design' => 4, 'rest_api' => 3, 'leadership' => 2],
            ],
            'Frontend Developer' => [
                'required' => ['javascript', 'html', 'css'],
                'weights'  => ['javascript' => 4, 'html' => 3, 'css' => 2],
            ],
        ];

        $results = [];

        foreach ($careerRules as $career => $rule) {
            $required = $rule['required'];
            $weights = $rule['weights'] ?? [];

            $sumWeighted = 0.0;
            $sumMax = 0.0;
            $missing = 0;

            foreach ($required as $skillKey) {
                $skillLevel = (int)($skills[$skillKey] ?? 0); // 0..100
                $weight = (int)($weights[$skillKey] ?? 1);
                $sumWeighted += ($skillLevel * $weight);
                $sumMax += (100 * $weight);

                if ($skillLevel <= 0) {
                    $missing++;
                }
            }

            $ratio = $sumMax > 0 ? ($sumWeighted / $sumMax) : 0.0; // 0..1

            // Reduce score slightly when many required skills are missing
            $penalty = min(0.25, $missing * 0.07);

            $score = (int)round(max(0, min(100, ($ratio - $penalty) * 100)));

            $results[] = [
                'career' => $career,
                'score' => $score,
            ];
        }

        usort($results, static fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($results, 0, 5);
    }
}

