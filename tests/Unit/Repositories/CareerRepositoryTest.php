<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Repositories\CareerRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

final class CareerRepositoryTest extends TestCase
{
    private function makeStmt(mixed $fetchReturn = false, array $fetchAllReturn = []): PDOStatement
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('fetch')->willReturn($fetchReturn);
        $stmt->method('fetchAll')->willReturn($fetchAllReturn);
        return $stmt;
    }

    // ── getUserById ───────────────────────────────────────────────────────────

    public function testGetUserByIdReturnsNullWhenNotFound(): void
    {
        $stmt = $this->makeStmt(fetchReturn: false);
        $pdo  = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new CareerRepository($pdo);
        $this->assertNull($repo->getUserById(999));
    }

    public function testGetUserByIdReturnsArrayWhenFound(): void
    {
        $row  = ['id' => 1, 'name' => 'Test', 'email' => 't@t.com'];
        $stmt = $this->makeStmt(fetchReturn: $row);
        $pdo  = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo   = new CareerRepository($pdo);
        $result = $repo->getUserById(1);
        $this->assertSame($row, $result);
    }

    // ── getCareerDNA ──────────────────────────────────────────────────────────

    public function testGetCareerDNAReturnsNullWhenNotFound(): void
    {
        $stmt = $this->makeStmt(fetchReturn: false);
        $pdo  = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new CareerRepository($pdo);
        $this->assertNull($repo->getCareerDNA(1));
    }

    public function testGetCareerDNAParsesJsonAndReturnsFloat(): void
    {
        $row  = ['skills_matrix' => '{"php":80,"mysql":60}', 'dna_score' => '70.00'];
        $stmt = $this->makeStmt(fetchReturn: $row);
        $pdo  = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo   = new CareerRepository($pdo);
        $result = $repo->getCareerDNA(1);

        $this->assertNotNull($result);
        $this->assertSame(80, $result['skills_matrix']['php']);
        $this->assertSame(60, $result['skills_matrix']['mysql']);
        $this->assertSame(70.0, $result['dna_score']);
    }

    public function testGetCareerDNAHandlesCorruptJson(): void
    {
        $row  = ['skills_matrix' => 'not-json', 'dna_score' => '0'];
        $stmt = $this->makeStmt(fetchReturn: $row);
        $pdo  = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo   = new CareerRepository($pdo);
        $result = $repo->getCareerDNA(1);

        $this->assertNotNull($result);
        $this->assertSame([], $result['skills_matrix']);
    }

    // ── saveMatchingResults ───────────────────────────────────────────────────

    public function testSaveMatchingResultsCallsExecuteForEachMatch(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->exactly(2))->method('execute');

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo    = new CareerRepository($pdo);
        $matches = [
            ['career' => 'Backend Developer',  'slug' => 'backend-developer',  'score' => 80],
            ['career' => 'Frontend Developer', 'slug' => 'frontend-developer', 'score' => 60],
        ];
        $repo->saveMatchingResults(1, $matches);
    }

    public function testSaveMatchingResultsSlugifiesCareerNameWhenNoSlug(): void
    {
        $capturedParams = [];
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('execute')->willReturnCallback(function (array $params) use (&$capturedParams) {
            $capturedParams[] = $params;
            return true;
        });

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new CareerRepository($pdo);
        $repo->saveMatchingResults(1, [['career' => 'Backend Developer', 'score' => 85]]);

        $this->assertSame('backend-developer', $capturedParams[0]['career_slug']);
    }

    // ── saveRoadmap ───────────────────────────────────────────────────────────

    public function testSaveRoadmapExecutesUpsert(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute');

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo    = new CareerRepository($pdo);
        $roadmap = [
            '_top_career' => 'Backend Developer',
            'phase_1'     => ['tasks' => []],
            'phase_2'     => ['tasks' => []],
            'phase_3'     => ['tasks' => []],
        ];
        $repo->saveRoadmap(1, $roadmap);
    }

    // ── getRoadmap ────────────────────────────────────────────────────────────

    public function testGetRoadmapReturnsNullWhenNotFound(): void
    {
        $stmt = $this->makeStmt(fetchReturn: false);
        $pdo  = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo = new CareerRepository($pdo);
        $this->assertNull($repo->getRoadmap(1, 'backend-developer'));
    }

    public function testGetRoadmapParsesPhaseJson(): void
    {
        $row = [
            'phase_1' => '{"tasks":[{"title":"Task 1"}]}',
            'phase_2' => '{"tasks":[]}',
            'phase_3' => '{"tasks":[]}',
        ];
        $stmt = $this->makeStmt(fetchReturn: $row);
        $pdo  = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        $repo   = new CareerRepository($pdo);
        $result = $repo->getRoadmap(1, 'backend-developer');

        $this->assertNotNull($result);
        $this->assertSame('Task 1', $result['phase_1']['tasks'][0]['title']);
    }
}
