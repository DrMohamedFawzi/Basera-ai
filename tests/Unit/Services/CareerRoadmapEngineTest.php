<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\CareerRoadmapEngine;
use PHPUnit\Framework\TestCase;

final class CareerRoadmapEngineTest extends TestCase
{
    private CareerRoadmapEngine $engine;

    protected function setUp(): void
    {
        $this->engine = new CareerRoadmapEngine();
    }

    private function dna(): array
    {
        return ['skills_matrix' => ['php' => 80], 'dna_score' => 80.0];
    }

    private function sampleMatches(string $topCareer = 'Backend Developer'): array
    {
        return [
            ['career' => $topCareer, 'slug' => 'backend-developer', 'score' => 85],
            ['career' => 'Frontend Developer', 'slug' => 'frontend-developer', 'score' => 40],
        ];
    }

    public function testReturnsThreePhases(): void
    {
        $roadmap = $this->engine->generateRoadmap($this->dna(), $this->sampleMatches());
        $this->assertArrayHasKey('phase_1', $roadmap);
        $this->assertArrayHasKey('phase_2', $roadmap);
        $this->assertArrayHasKey('phase_3', $roadmap);
    }

    public function testEachPhaseHasTasks(): void
    {
        $roadmap = $this->engine->generateRoadmap($this->dna(), $this->sampleMatches());
        $this->assertNotEmpty($roadmap['phase_1']['tasks']);
        $this->assertNotEmpty($roadmap['phase_2']['tasks']);
        $this->assertNotEmpty($roadmap['phase_3']['tasks']);
    }

    public function testTopCareerIsRecordedInRoadmap(): void
    {
        $roadmap = $this->engine->generateRoadmap($this->dna(), $this->sampleMatches('Frontend Developer'));
        $this->assertSame('Frontend Developer', $roadmap['_top_career']);
    }

    public function testTasksHaveTitleTypeAndPriority(): void
    {
        $roadmap = $this->engine->generateRoadmap($this->dna(), $this->sampleMatches());
        foreach (['phase_1', 'phase_2', 'phase_3'] as $phase) {
            foreach ($roadmap[$phase]['tasks'] as $task) {
                $this->assertArrayHasKey('title', $task);
                $this->assertArrayHasKey('type', $task);
                $this->assertArrayHasKey('priority', $task);
            }
        }
    }

    public function testEmptyMatchesFallsBackToDefault(): void
    {
        $roadmap = $this->engine->generateRoadmap($this->dna(), []);
        $this->assertArrayHasKey('phase_1', $roadmap);
    }
}
