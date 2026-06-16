<?php

declare(strict_types=1);

namespace App\Services;

use PDO;

final class CareerMatchingEngine
{
    public function __construct(private readonly ?PDO $db = null)
    {
    }

    /**
     * @param array $dna ['skills_matrix' => ['php' => 80, ...], 'dna_score' => 55.5]
     * @return array<int, array{career: string, slug: string, score: int}>
     */
    public function generateMatches(array $dna): array
    {
        $skills      = $dna['skills_matrix'] ?? [];
        $careerRules = $this->db !== null ? $this->loadRulesFromDb() : $this->hardcodedRules();

        $results = [];

        foreach ($careerRules as $rule) {
            $required = $rule['required'];
            $weights  = $rule['weights'];

            $sumWeighted = 0.0;
            $sumMax      = 0.0;
            $missing     = 0;

            foreach ($required as $skillKey) {
                $level  = (int)($skills[$skillKey] ?? 0);
                $weight = (int)($weights[$skillKey] ?? 1);
                $sumWeighted += $level * $weight;
                $sumMax      += 100 * $weight;

                if ($level <= 0) {
                    $missing++;
                }
            }

            $ratio   = $sumMax > 0 ? ($sumWeighted / $sumMax) : 0.0;
            $penalty = min(0.25, $missing * 0.07);
            $score   = (int)round(max(0, min(100, ($ratio - $penalty) * 100)));

            $results[] = [
                'career' => $rule['name_en'],
                'slug'   => $rule['slug'],
                'score'  => $score,
            ];
        }

        usort($results, static fn($a, $b) => $b['score'] <=> $a['score']);

        return array_slice($results, 0, 5);
    }

    /** @return array<int, array{slug:string, name_en:string, required:array, weights:array}> */
    private function loadRulesFromDb(): array
    {
        $stmt = $this->db->query(
            'SELECT slug, name_en, required_skills, skill_weights FROM careers WHERE active = 1 ORDER BY name_en'
        );

        $rules = [];
        foreach ($stmt->fetchAll() as $row) {
            $rules[] = [
                'slug'    => $row['slug'],
                'name_en' => $row['name_en'],
                'required' => json_decode($row['required_skills'], true) ?? [],
                'weights'  => json_decode($row['skill_weights'], true) ?? [],
            ];
        }

        return $rules ?: $this->hardcodedRules();
    }

    /** @return array<int, array{slug:string, name_en:string, required:array, weights:array}> */
    private function hardcodedRules(): array
    {
        return [
            [
                'slug'    => 'backend-developer',
                'name_en' => 'Backend Developer',
                'required' => ['php', 'mysql', 'rest_api'],
                'weights'  => ['php' => 4, 'mysql' => 3, 'rest_api' => 2],
            ],
            [
                'slug'    => 'software-architect',
                'name_en' => 'Software Architect',
                'required' => ['system_design', 'rest_api', 'leadership'],
                'weights'  => ['system_design' => 4, 'rest_api' => 3, 'leadership' => 2],
            ],
            [
                'slug'    => 'frontend-developer',
                'name_en' => 'Frontend Developer',
                'required' => ['javascript', 'html', 'css'],
                'weights'  => ['javascript' => 4, 'html' => 3, 'css' => 2],
            ],
        ];
    }
}
