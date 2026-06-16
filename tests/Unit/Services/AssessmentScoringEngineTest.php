<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\AssessmentScoringEngine;
use App\Services\CareerDNAEngine;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

final class AssessmentScoringEngineTest extends TestCase
{
    private function makeStmt(array $fetchAllReturn = [], mixed $fetchReturn = false): PDOStatement
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetchAll')->willReturn($fetchAllReturn);
        $stmt->method('fetch')->willReturn($fetchReturn);
        return $stmt;
    }

    public function testProcessAggregatesSkillScores(): void
    {
        $responseRows = [
            ['skill_key' => 'php',   'total_score' => 80],
            ['skill_key' => 'mysql', 'total_score' => 60],
        ];

        // First prepare: SELECT responses → returns rows
        // Second prepare: INSERT INTO career_twins → returns true
        $responseStmt = $this->makeStmt(fetchAllReturn: $responseRows);
        $upsertStmt   = $this->makeStmt();

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')
            ->willReturnOnConsecutiveCalls($responseStmt, $upsertStmt);

        $engine  = new AssessmentScoringEngine($pdo, new CareerDNAEngine());
        $result  = $engine->process(1, 1);

        $this->assertSame(80, $result['matrix']['php']);
        $this->assertSame(60, $result['matrix']['mysql']);
        $this->assertSame(70.0, $result['overall']);
    }

    public function testProcessIgnoresEmptySkillKeys(): void
    {
        $responseRows = [
            ['skill_key' => '',    'total_score' => 50],
            ['skill_key' => 'css', 'total_score' => 90],
        ];

        $responseStmt = $this->makeStmt(fetchAllReturn: $responseRows);
        $upsertStmt   = $this->makeStmt();

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')
            ->willReturnOnConsecutiveCalls($responseStmt, $upsertStmt);

        $engine = new AssessmentScoringEngine($pdo, new CareerDNAEngine());
        $result = $engine->process(1, 1);

        $this->assertArrayNotHasKey('', $result['matrix']);
        $this->assertArrayHasKey('css', $result['matrix']);
    }

    public function testProcessReturnsZeroOverallForNoResponses(): void
    {
        $responseStmt = $this->makeStmt(fetchAllReturn: []);
        $upsertStmt   = $this->makeStmt();

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')
            ->willReturnOnConsecutiveCalls($responseStmt, $upsertStmt);

        $engine = new AssessmentScoringEngine($pdo, new CareerDNAEngine());
        $result = $engine->process(1, 1);

        $this->assertSame([], $result['matrix']);
        $this->assertSame(0.0, $result['overall']);
    }
}
