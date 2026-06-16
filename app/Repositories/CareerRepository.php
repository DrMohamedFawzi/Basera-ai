<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class CareerRepository implements CareerRepositoryInterface
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getConnection();
    }

    public function getUserById(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // ── DNA ──────────────────────────────────────────────────────────────────

    public function saveCareerDNA(int $userId, array $dnaSnapshot): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO career_twins (user_id, skills_matrix, dna_score)
             VALUES (:user_id, :skills_matrix, :dna_score)
             ON DUPLICATE KEY UPDATE
               skills_matrix = VALUES(skills_matrix),
               dna_score     = VALUES(dna_score)"
        );

        $stmt->execute([
            'user_id'      => $userId,
            'skills_matrix' => json_encode($dnaSnapshot['skills_matrix'] ?? [], JSON_UNESCAPED_UNICODE),
            'dna_score'    => (float)($dnaSnapshot['dna_score'] ?? 0),
        ]);
    }

    public function getCareerDNA(int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT skills_matrix, dna_score FROM career_twins WHERE user_id = :user_id LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $matrix = json_decode((string)$row['skills_matrix'], true);

        return [
            'skills_matrix' => is_array($matrix) ? $matrix : [],
            'dna_score'     => (float)$row['dna_score'],
        ];
    }

    // ── Matches ───────────────────────────────────────────────────────────────

    public function saveMatchingResults(int $userId, array $matches): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO career_matches (user_id, career_slug, score)
             VALUES (:user_id, :career_slug, :score)
             ON DUPLICATE KEY UPDATE score = VALUES(score)"
        );

        foreach ($matches as $match) {
            $slug = $match['slug'] ?? $this->slugify((string)($match['career'] ?? ''));
            $stmt->execute([
                'user_id'     => $userId,
                'career_slug' => $slug,
                'score'       => (int)($match['score'] ?? 0),
            ]);
        }
    }

    public function getMatchingResults(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT career_slug, score FROM career_matches WHERE user_id = :user_id ORDER BY score DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll() ?: [];
    }

    // ── Roadmap ───────────────────────────────────────────────────────────────

    public function saveRoadmap(int $userId, array $roadmap): void
    {
        $topCareer = (string)($roadmap['_top_career'] ?? '');
        $slug      = $this->slugify($topCareer) ?: 'general';

        $stmt = $this->db->prepare(
            "INSERT INTO roadmaps (user_id, career_slug, phase_1, phase_2, phase_3)
             VALUES (:user_id, :career_slug, :phase_1, :phase_2, :phase_3)
             ON DUPLICATE KEY UPDATE
               phase_1 = VALUES(phase_1),
               phase_2 = VALUES(phase_2),
               phase_3 = VALUES(phase_3)"
        );

        $stmt->execute([
            'user_id'     => $userId,
            'career_slug' => $slug,
            'phase_1'     => json_encode($roadmap['phase_1'] ?? [], JSON_UNESCAPED_UNICODE),
            'phase_2'     => json_encode($roadmap['phase_2'] ?? [], JSON_UNESCAPED_UNICODE),
            'phase_3'     => json_encode($roadmap['phase_3'] ?? [], JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function getRoadmap(int $userId, string $careerSlug): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT phase_1, phase_2, phase_3 FROM roadmaps
             WHERE user_id = :user_id AND career_slug = :career_slug LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId, 'career_slug' => $careerSlug]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return [
            'phase_1' => json_decode((string)$row['phase_1'], true) ?? [],
            'phase_2' => json_decode((string)$row['phase_2'], true) ?? [],
            'phase_3' => json_decode((string)$row['phase_3'], true) ?? [],
        ];
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = (string)preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}
