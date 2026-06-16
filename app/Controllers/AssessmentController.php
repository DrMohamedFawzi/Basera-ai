<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Services\AssessmentScoringEngine;

final class AssessmentController
{
    /**
     * GET or create an in-progress assessment for the user.
     * Returns JSON: { assessment_id: int, total_questions: int, answered_count: int }
     */
    public function start(int $userId): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($userId <= 0) {
            http_response_code(422);
            echo json_encode(['error' => 'Invalid user_id'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $db = Database::getConnection();

        // Resume existing in-progress assessment if any
        $stmt = $db->prepare(
            "SELECT id FROM user_assessments WHERE user_id = :user_id AND status = 'in_progress' LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $assessmentId = (int)$existing['id'];
        } else {
            $stmt = $db->prepare(
                "INSERT INTO user_assessments (user_id, status, current_step) VALUES (:user_id, 'in_progress', 1)"
            );
            $stmt->execute(['user_id' => $userId]);
            $assessmentId = (int)$db->lastInsertId();
        }

        $totals = $this->getQuestionCounts($db, $assessmentId);

        echo json_encode([
            'assessment_id'  => $assessmentId,
            'total_questions' => $totals['total'],
            'answered_count'  => $totals['answered'],
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Save a single answer. Body: { assessment_id, question_id, option_id }
     */
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
        $questionId   = (int)($payload['question_id'] ?? 0);
        $optionId     = (int)($payload['option_id'] ?? 0);

        if ($assessmentId <= 0 || $questionId <= 0 || $optionId <= 0) {
            http_response_code(422);
            echo json_encode(
                ['error' => 'assessment_id, question_id, option_id are required'],
                JSON_UNESCAPED_UNICODE
            );
            return;
        }

        $db = Database::getConnection();

        $stmt = $db->prepare(
            "INSERT INTO user_responses (assessment_id, question_id, option_id)
             VALUES (:assessment_id, :question_id, :option_id)
             ON DUPLICATE KEY UPDATE option_id = VALUES(option_id)"
        );
        $stmt->execute([
            'assessment_id' => $assessmentId,
            'question_id'   => $questionId,
            'option_id'     => $optionId,
        ]);

        $totals = $this->getQuestionCounts($db, $assessmentId);

        echo json_encode([
            'status'          => 'saved',
            'total_questions' => $totals['total'],
            'answered_count'  => $totals['answered'],
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Returns the next unanswered question for this assessment.
     * GET param: assessment_id
     */
    public function getNextQuestion(int $assessmentId): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($assessmentId <= 0) {
            http_response_code(422);
            echo json_encode(['error' => 'assessment_id is required'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT q.id, q.question_text, q.order_num, q.category_id,
                    c.name_ar AS category_name
             FROM questions q
             JOIN assessment_categories c ON c.id = q.category_id
             WHERE q.id NOT IN (
                 SELECT question_id FROM user_responses WHERE assessment_id = :assessment_id
             )
             ORDER BY q.category_id ASC, q.order_num ASC, q.id ASC
             LIMIT 1"
        );
        $stmt->execute(['assessment_id' => $assessmentId]);
        $q = $stmt->fetch();

        if (!$q) {
            $totals = $this->getQuestionCounts($db, $assessmentId);
            echo json_encode([
                'question'       => null,
                'total_questions' => $totals['total'],
                'answered_count'  => $totals['answered'],
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $optStmt = $db->prepare(
            "SELECT id, option_text FROM question_options
             WHERE question_id = :question_id ORDER BY id ASC"
        );
        $optStmt->execute(['question_id' => (int)$q['id']]);
        $options = $optStmt->fetchAll();

        $totals = $this->getQuestionCounts($db, $assessmentId);

        echo json_encode([
            'question' => [
                'id'            => (int)$q['id'],
                'text'          => $q['question_text'],
                'category_name' => $q['category_name'],
                'options'       => array_map(static fn($r) => [
                    'id'   => (int)$r['id'],
                    'text' => $r['option_text'],
                ], $options),
            ],
            'total_questions' => $totals['total'],
            'answered_count'  => $totals['answered'],
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Finalize the assessment and trigger DNA calculation.
     */
    public function finalize(int $assessmentId, int $userId): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($assessmentId <= 0 || $userId <= 0) {
            http_response_code(422);
            echo json_encode(['error' => 'Invalid assessmentId/userId'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $engine   = AssessmentScoringEngine::createDefault();
        $snapshot = $engine->process($assessmentId, $userId);

        $db = Database::getConnection();
        $db->prepare(
            "UPDATE user_assessments SET status='completed', completed_at=NOW() WHERE id=:id"
        )->execute(['id' => $assessmentId]);

        echo json_encode([
            'status'       => 'completed',
            'dna_snapshot' => $snapshot,
        ], JSON_UNESCAPED_UNICODE);
    }

    private function getQuestionCounts(\PDO $db, int $assessmentId): array
    {
        $totalStmt = $db->query('SELECT COUNT(*) FROM questions');
        $total     = (int)$totalStmt->fetchColumn();

        $answeredStmt = $db->prepare(
            'SELECT COUNT(*) FROM user_responses WHERE assessment_id = :assessment_id'
        );
        $answeredStmt->execute(['assessment_id' => $assessmentId]);
        $answered = (int)$answeredStmt->fetchColumn();

        return ['total' => $total, 'answered' => $answered];
    }
}
