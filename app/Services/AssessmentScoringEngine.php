<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;

final class AssessmentScoringEngine
{
    public function __construct(
        private readonly PDO $db,
        private readonly CareerDNAEngine $dnaEngine,
    ) {
    }

    /**
     * يحدد إن كان جدول career_dna موجوداً.
     */
    private function hasCareerDnaTable(): bool
    {
        $stmt = $this->db->query("SHOW TABLES LIKE 'career_dna'");
        return (bool)($stmt->fetch());
    }


    public static function createDefault(): self
    {
        return new self(Database::getConnection(), new CareerDNAEngine());
    }

    /**
     * يحسب نقاط الـ DNA snapshot من إجابات المستخدم خلال assessment.
     *
     * @return array{matrix: array<string,int>, overall: float}
     */
    public function process(int $assessmentId, int $userId): array
    {
        $stmt = $this->db->prepare(
            "
            SELECT
              qo.skill_key AS skill_key,
              SUM(qo.score_value) AS total_score
            FROM user_responses ur
            JOIN question_options qo ON qo.id = ur.option_id
            WHERE ur.assessment_id = :assessment_id
            GROUP BY qo.skill_key
            "
        );
        $stmt->execute(['assessment_id' => $assessmentId]);

        $rows = $stmt->fetchAll();
        $skillsMatrix = [];
        foreach ($rows as $row) {
            $key = (string)($row['skill_key'] ?? '');
            if ($key === '') {
                continue;
            }
            $skillsMatrix[$key] = (int)($row['total_score'] ?? 0);
        }

        $overall = $this->dnaEngine->calculateOverallDnaScore($skillsMatrix);

        // إذا كانت career_dna موجودة استخدمها، وإلا استخدم الجدول القديم career_twins
        if ($this->hasCareerDnaTable()) {
            $upsert = $this->db->prepare(
                "
                INSERT INTO career_dna (user_id, skills_matrix, dna_score)
                VALUES (:user_id, :skills_matrix, :dna_score)
                ON DUPLICATE KEY UPDATE
                  skills_matrix = VALUES(skills_matrix),
                  dna_score = VALUES(dna_score)
                "
            );
        } else {
            $upsert = $this->db->prepare(
                "
                INSERT INTO career_twins (user_id, skills_matrix, dna_score)
                VALUES (:user_id, :skills_matrix, :dna_score)
                ON DUPLICATE KEY UPDATE
                  skills_matrix = VALUES(skills_matrix),
                  dna_score = VALUES(dna_score)
                "
            );
        }

        $upsert->execute([
            'user_id' => $userId,
            'skills_matrix' => json_encode($skillsMatrix, JSON_UNESCAPED_UNICODE),
            'dna_score' => $overall,
        ]);


        return [
            'matrix' => $skillsMatrix,
            'overall' => $overall,
        ];
    }
}

