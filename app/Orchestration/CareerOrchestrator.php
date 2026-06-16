<?php

declare(strict_types=1);

namespace App\Orchestration;

use App\Repositories\CareerRepository;
use App\Services\CareerMatchingEngine;
use App\Services\CareerRoadmapEngine;

/**
 * CareerOrchestrator
 * يشغل خط الأنابيب: Assessment -> DNA -> Matching -> Roadmap
 * (هنا سننفّذ مرحلة DNA->Matching->Roadmap اعتماداً على DNA الموجود في career_twins)
 */
final class CareerOrchestrator
{
    public function __construct(
        private readonly CareerRepository $careerRepository,
        private readonly CareerMatchingEngine $matchingEngine,
        private readonly CareerRoadmapEngine $roadmapEngine,
    ) {
    }

    /**
     * @return array{matches: array, roadmap: array, dna: array|null}
     */
    public function run(int $userId): array
    {
        $dna = $this->careerRepository->getCareerDNA($userId);
        if (!$dna) {
            return [
                'matches' => [],
                'roadmap' => [],
                'dna' => null,
            ];
        }

        $matches = $this->matchingEngine->generateMatches($dna);
        $roadmap = $this->roadmapEngine->generateRoadmap($dna, $matches);

        $this->careerRepository->saveMatchingResults($userId, $matches);
        $this->careerRepository->saveRoadmap($userId, $roadmap);

        return [
            'dna' => $dna,
            'matches' => $matches,
            'roadmap' => $roadmap,
        ];
    }
}

