<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Orchestration\CareerOrchestrator;
use App\Repositories\CareerRepository;
use App\Services\CareerMatchingEngine;
use App\Services\CareerRoadmapEngine;

final class CareerController
{
    /**
     * POST /api/career/run
     * body: {"user_id": 1}
     */
    public function run(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $payload = json_decode(file_get_contents('php://input') ?: '', true);
        if (!is_array($payload)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON body'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $userId = (int)($payload['user_id'] ?? 0);
        if ($userId <= 0) {
            http_response_code(422);
            echo json_encode(['error' => 'user_id is required'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $repository = new CareerRepository();
        $matching = new CareerMatchingEngine();
        $roadmap = new CareerRoadmapEngine();
        $orchestrator = new CareerOrchestrator($repository, $matching, $roadmap);

        $result = $orchestrator->run($userId);

        echo json_encode([
            'status' => 'success',
            'dna' => $result['dna'],
            'matches' => $result['matches'],
            'roadmap' => $result['roadmap'],
        ], JSON_UNESCAPED_UNICODE);
    }
}

