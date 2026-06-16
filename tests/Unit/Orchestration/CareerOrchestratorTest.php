<?php

declare(strict_types=1);

namespace Tests\Unit\Orchestration;

use App\Orchestration\CareerOrchestrator;
use App\Repositories\CareerRepositoryInterface;
use App\Services\CareerMatchingEngine;
use App\Services\CareerRoadmapEngine;
use PHPUnit\Framework\TestCase;

final class CareerOrchestratorTest extends TestCase
{
    private function makeRepository(?array $dna): CareerRepositoryInterface
    {
        $repo = $this->createMock(CareerRepositoryInterface::class);
        $repo->method('getCareerDNA')->willReturn($dna);
        return $repo;
    }

    private function makeOrchestrator(?array $dna): CareerOrchestrator
    {
        return new CareerOrchestrator(
            $this->makeRepository($dna),
            new CareerMatchingEngine(),
            new CareerRoadmapEngine(),
        );
    }

    public function testRunReturnsEmptyResultWhenNoDNA(): void
    {
        $result = $this->makeOrchestrator(null)->run(1);

        $this->assertNull($result['dna']);
        $this->assertSame([], $result['matches']);
        $this->assertSame([], $result['roadmap']);
    }

    public function testRunReturnsDnaFromRepository(): void
    {
        $dna    = ['skills_matrix' => ['php' => 80], 'dna_score' => 80.0];
        $result = $this->makeOrchestrator($dna)->run(1);

        $this->assertSame($dna, $result['dna']);
    }

    public function testRunGeneratesMatchesWhenDNAExists(): void
    {
        $dna    = ['skills_matrix' => ['php' => 80, 'mysql' => 60, 'rest_api' => 70], 'dna_score' => 70.0];
        $result = $this->makeOrchestrator($dna)->run(1);

        $this->assertNotEmpty($result['matches']);
    }

    public function testRunGeneratesRoadmapWhenDNAExists(): void
    {
        $dna    = ['skills_matrix' => ['php' => 80], 'dna_score' => 80.0];
        $result = $this->makeOrchestrator($dna)->run(1);

        $this->assertArrayHasKey('phase_1', $result['roadmap']);
        $this->assertArrayHasKey('phase_2', $result['roadmap']);
        $this->assertArrayHasKey('phase_3', $result['roadmap']);
    }

    public function testRunSavesMatchingResultsToRepository(): void
    {
        $dna  = ['skills_matrix' => ['php' => 80], 'dna_score' => 80.0];
        $repo = $this->createMock(CareerRepositoryInterface::class);
        $repo->method('getCareerDNA')->willReturn($dna);
        $repo->expects($this->once())->method('saveMatchingResults');
        $repo->expects($this->once())->method('saveRoadmap');

        $orchestrator = new CareerOrchestrator($repo, new CareerMatchingEngine(), new CareerRoadmapEngine());
        $orchestrator->run(1);
    }
}
