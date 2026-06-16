<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

/**
 * CareerRepository
 *
 * مستودع مبدئي لإدارة:
 * - جلب بيانات مستخدم
 * - حفظ DNA
 * - حفظ Matching نتائج
 * - حفظ Roadmaps
 */
final class CareerRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    /**
     * @return array|null (مصفوفة raw user data)
     */
    public function getUserById(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * حفظ/تحديث Career DNA snapshot.
     *
     * @param array $dnaSnapshot
     */
    public function saveCareerDNA(int $userId, array $dnaSnapshot): void
    {
        $skillsMatrix = $dnaSnapshot['skills_matrix'] ?? [];
        $dnaScore = (float)($dnaSnapshot['dna_score'] ?? 0);

        $stmt = $this->db->prepare(
            "
            INSERT INTO career_twins (user_id, skills_matrix, dna_score)
            VALUES (:user_id, :skills_matrix, :dna_score)
            ON DUPLICATE KEY UPDATE
              skills_matrix = VALUES(skills_matrix),
              dna_score = VALUES(dna_score)
            "
        );

        $stmt->execute([
            'user_id' => $userId,
            'skills_matrix' => json_encode($skillsMatrix, JSON_UNESCAPED_UNICODE),
            'dna_score' => $dnaScore,
        ]);
    }

    /**
     * @return array|null DNA snapshot
     */
    public function getCareerDNA(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT skills_matrix, dna_score FROM career_twins WHERE user_id = :user_id LIMIT 1');
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        $skillsMatrix = json_decode((string)$row['skills_matrix'], true);
        if (!is_array($skillsMatrix)) {
            $skillsMatrix = [];
        }

        return [
            'skills_matrix' => $skillsMatrix,
            'dna_score' => (float)$row['dna_score'],
        ];
    }

    /**
     * حفظ Matching النتائج (prototype: تخزين JSON داخل career_twins عبر حقل مؤقت مستقبلاً).
     *
     * بما أننا لم ننشئ جداول matching/roadmaps بعد حسب تعليماتك، سنخزنها حالياً
     * داخل سجل DNA عبر تحديث skills_matrix كـ extension غير مُثالي.
     *
     * في المرحلة التالية سننشئ career_matches/roadmaps tables.
     */
    public function saveMatchingResults(int $userId, array $matches): void
    {
        $dna = $this->getCareerDNA($userId);
        if (!$dna) {
            // لو لا يوجد dna لا نستطيع حفظ matching
            return;
        }

        $skillsMatrix = $dna['skills_matrix'];
        // إضافة مفتاح خاص (prototype)
        $skillsMatrix['_matches_prototype'] = $matches;

        $stmt = $this->db->prepare(
            "
            UPDATE career_twins
            SET skills_matrix = :skills_matrix
            WHERE user_id = :user_id
            "
        );

        $stmt->execute([
            'skills_matrix' => json_encode($skillsMatrix, JSON_UNESCAPED_UNICODE),
            'user_id' => $userId,
        ]);
    }

    /**
     * @param array $roadmap
     */
    public function saveRoadmap(int $userId, array $roadmap): void
    {
        $dna = $this->getCareerDNA($userId);
        if (!$dna) {
            return;
        }

        $skillsMatrix = $dna['skills_matrix'];
        // إضافة مفتاح خاص (prototype)
        $skillsMatrix['_roadmap_prototype'] = $roadmap;

        $stmt = $this->db->prepare(
            "
            UPDATE career_twins
            SET skills_matrix = :skills_matrix
            WHERE user_id = :user_id
            "
        );

        $stmt->execute([
            'skills_matrix' => json_encode($skillsMatrix, JSON_UNESCAPED_UNICODE),
            'user_id' => $userId,
        ]);
    }
}

