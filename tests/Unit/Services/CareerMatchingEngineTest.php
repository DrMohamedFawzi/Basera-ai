<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\CareerMatchingEngine;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

final class CareerMatchingEngineTest extends TestCase
{
    private function makeEngine(?PDO $db = null): CareerMatchingEngine
    {
        return new CareerMatchingEngine($db);
    }

    // ── Hardcoded (no DB) ─────────────────────────────────────────────────────

    public function testReturnsUpToFiveResults(): void
    {
        $engine  = $this->makeEngine();
        $matches = $engine->generateMatches(['skills_matrix' => []]);
        $this->assertLessThanOrEqual(5, count($matches));
    }

    public function testResultsAreSortedByScoreDescending(): void
    {
        $engine  = $this->makeEngine();
        $matches = $engine->generateMatches([
            'skills_matrix' => ['php' => 80, 'mysql' => 70, 'rest_api' => 90],
        ]);
        for ($i = 1; $i < count($matches); $i++) {
            $this->assertGreaterThanOrEqual($matches[$i]['score'], $matches[$i - 1]['score']);
        }
    }

    public function testPerfectBackendSkillsGivesHighScore(): void
    {
        $engine  = $this->makeEngine();
        $matches = $engine->generateMatches([
            'skills_matrix' => ['php' => 100, 'mysql' => 100, 'rest_api' => 100],
        ]);

        $backend = array_filter($matches, fn($m) => $m['career'] === 'Backend Developer');
        $this->assertNotEmpty($backend);
        $this->assertSame(100, array_values($backend)[0]['score']);
    }

    public function testNoSkillsGivesLowScores(): void
    {
        $engine  = $this->makeEngine();
        $matches = $engine->generateMatches(['skills_matrix' => []]);
        foreach ($matches as $m) {
            $this->assertLessThanOrEqual(20, $m['score']);
        }
    }

    public function testEachResultHasRequiredKeys(): void
    {
        $engine  = $this->makeEngine();
        $matches = $engine->generateMatches(['skills_matrix' => ['php' => 50]]);
        foreach ($matches as $m) {
            $this->assertArrayHasKey('career', $m);
            $this->assertArrayHasKey('slug', $m);
            $this->assertArrayHasKey('score', $m);
        }
    }

    public function testScoreIsBetweenZeroAndHundred(): void
    {
        $engine  = $this->makeEngine();
        $matches = $engine->generateMatches(['skills_matrix' => ['php' => 50, 'mysql' => 80]]);
        foreach ($matches as $m) {
            $this->assertGreaterThanOrEqual(0, $m['score']);
            $this->assertLessThanOrEqual(100, $m['score']);
        }
    }

    // ── DB-driven ─────────────────────────────────────────────────────────────

    public function testLoadsRulesFromDatabase(): void
    {
        $rows = [
            [
                'slug'            => 'backend-developer',
                'name_en'         => 'Backend Developer',
                'required_skills' => '["php","mysql"]',
                'skill_weights'   => '{"php":4,"mysql":3}',
            ],
        ];

        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn($rows);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('query')->willReturn($stmt);

        $engine  = new CareerMatchingEngine($pdo);
        $matches = $engine->generateMatches([
            'skills_matrix' => ['php' => 100, 'mysql' => 100],
        ]);

        $this->assertNotEmpty($matches);
        $this->assertSame('Backend Developer', $matches[0]['career']);
        $this->assertSame(100, $matches[0]['score']);
    }

    public function testFallsBackToHardcodedWhenDbReturnsEmpty(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetchAll')->willReturn([]);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('query')->willReturn($stmt);

        $engine  = new CareerMatchingEngine($pdo);
        $matches = $engine->generateMatches(['skills_matrix' => []]);

        $this->assertNotEmpty($matches);
    }
}
