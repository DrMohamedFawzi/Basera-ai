<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Services\AssessmentScoringEngine;

final class AssessmentController
{
    public function saveResponse(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $payload = json_decode(file_get_contents('php://input') ?: '', true);
        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON body'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $assessmentId = (int)($payload['assessment_id'] ?? 0);
        $questionId = (int)($payload['question_id'] ?? 0);
        $optionId = (int)($payload['option_id'] ?? 0);

        if ($assessmentId <= 0 || $questionId <= 0 || $optionId <= 0) {
            http_response_code(422);
            echo json_encode(['error' => 'assessment_id, question_id, option_id are required'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $db = Database::getConnection();

        // upsert: لكل سؤال إجابة واحدة ضمن نفس assessment
        $stmt = $db->prepare(
            "
            INSERT INTO user_responses (assessment_id, question_id, option_id)
            VALUES (:assessment_id, :question_id, :option_id)
            ON DUPLICATE KEY UPDATE option_id = VALUES(option_id)
            "
        );

        $stmt->execute([
            'assessment_id' => $assessmentId,
            'question_id' => $questionId,
            'option_id' => $optionId,
        ]);

        echo json_encode(['status' => 'saved'], JSON_UNESCAPED_UNICODE);
    }

    public function finalize(int $assessmentId, int $userId): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($assessmentId <= 0 || $userId <= 0) {
            http_response_code(422);
            echo json_encode(['error' => 'Invalid assessmentId/userId'], JSON_UNESCAPED_UNICODE);
            return;
        }

        // حساب snapshot وإدخاله في career_twins
        $engine = AssessmentScoringEngine::createDefault();
        $snapshot = $engine->process($assessmentId, $userId);

        $db = Database::getConnection();
        $stmt = $db->prepare(
            "
            UPDATE user_assessments
            SET status='completed', completed_at=NOW()
            WHERE id=:id
            "
        );
        $stmt->execute(['id' => $assessmentId]);

        echo json_encode([
            'status' => 'completed',
            'dna_snapshot' => $snapshot,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * واجهة جاهزة لإظهار سؤال واحد (للتجربة).
     */
    public function getNextQuestion(int $categoryId): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $db = Database::getConnection();

        $stmt = $db->prepare(
            "
            SELECT q.id, q.question_text, q.order_num
            FROM questions q
            WHERE q.category_id = :category_id
            ORDER BY q.order_num ASC, q.id ASC
            LIMIT 1
            "
        );
        $stmt->execute(['category_id' => $categoryId]);
        $q = $stmt->fetch();

        if (!$q) {
            echo json_encode(['question' => null], JSON_UNESCAPED_UNICODE);
            return;
        }

        $optStmt = $db->prepare(
            "
            SELECT id, option_text, skill_key, score_value
            FROM question_options
            WHERE question_id = :question_id
            ORDER BY id ASC
            "
        );
        $optStmt->execute(['question_id' => (int)$q['id']]);
        $options = $optStmt->fetchAll();

        echo json_encode([
            'question' => [
                'id' => (int)$q['id'],
                'text' => $q['question_text'],
                'options' => array_map(static function ($row) {
                    return [
                        'id' => (int)$row['id'],
                        'text' => $row['option_text'],
                        'skill_key' => $row['skill_key'],
                        'score_value' => (int)$row['score_value'],
                    ];
                }, $options)
            ]
        ], JSON_UNESCAPED_UNICODE);
    }
}

