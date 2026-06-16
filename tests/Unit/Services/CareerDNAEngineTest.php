<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\CareerDNAEngine;
use PHPUnit\Framework\TestCase;

final class CareerDNAEngineTest extends TestCase
{
    private CareerDNAEngine $engine;

    protected function setUp(): void
    {
        $this->engine = new CareerDNAEngine();
    }

    public function testOverallScoreIsZeroForEmptyMatrix(): void
    {
        $score = $this->engine->calculateOverallDnaScore([]);
        $this->assertSame(0.0, $score);
    }

    public function testOverallScoreWithSinglePerfectSkill(): void
    {
        $score = $this->engine->calculateOverallDnaScore(['php' => 100]);
        $this->assertSame(100.0, $score);
    }

    public function testOverallScoreAveragesSkills(): void
    {
        // (80 + 60) / (2 * 100) * 100 = 70
        $score = $this->engine->calculateOverallDnaScore(['php' => 80, 'mysql' => 60]);
        $this->assertSame(70.0, $score);
    }

    public function testOverallScoreRoundsToTwoDecimals(): void
    {
        // (33 + 66 + 100) / (3 * 100) * 100 = 66.33...
        $score = $this->engine->calculateOverallDnaScore(['a' => 33, 'b' => 66, 'c' => 100]);
        $this->assertSame(66.33, $score);
    }

    public function testUpdateSkillAddsPoints(): void
    {
        $matrix = ['php' => 50];
        $result = $this->engine->updateSkillLevelInMatrix($matrix, 'php', 20);
        $this->assertSame(70, $result['php']);
    }

    public function testUpdateSkillCapsAt100(): void
    {
        $matrix = ['php' => 90];
        $result = $this->engine->updateSkillLevelInMatrix($matrix, 'php', 50);
        $this->assertSame(100, $result['php']);
    }

    public function testUpdateSkillCreatesNewKey(): void
    {
        $matrix = [];
        $result = $this->engine->updateSkillLevelInMatrix($matrix, 'javascript', 40);
        $this->assertSame(40, $result['javascript']);
    }

    public function testBuildDnaSnapshotHasRequiredKeys(): void
    {
        $matrix   = ['php' => 80, 'mysql' => 60];
        $snapshot = $this->engine->buildDnaSnapshot($matrix);

        $this->assertArrayHasKey('skills_matrix', $snapshot);
        $this->assertArrayHasKey('dna_score', $snapshot);
        $this->assertArrayHasKey('max_skill_score', $snapshot);
        $this->assertSame($matrix, $snapshot['skills_matrix']);
        $this->assertSame(100, $snapshot['max_skill_score']);
        $this->assertSame(70.0, $snapshot['dna_score']);
    }
}
