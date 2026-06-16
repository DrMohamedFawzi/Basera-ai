<?php

declare(strict_types=1);

// Simple front controller for development/testing

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\SimpleRouter;
use App\Controllers\AssessmentController;

$router = new SimpleRouter();
$controller = new AssessmentController();

// روابط واجهة المستخدم (UI Routes)
$router->get('/', static function (): void {
    // عند الدخول للرابط الرئيسي، نعرض صفحة الاختبار
    include __DIR__ . '/../views/assessment/wizard.php';
});

$router->get('/results', static function (): void {
    // تمكين تمرير user_id من query string
    include __DIR__ . '/results.php';
});


$router->post('/api/assessment/save', static function () use ($controller): void {
    $controller->saveResponse();
});

$router->get('/api/assessment/question/next', static function () use ($controller): void {
    $categoryId = (int)($_GET['category_id'] ?? 1);
    $controller->getNextQuestion($categoryId);
});

$router->post('/api/assessment/finalize', static function () use ($controller): void {
    $payload = json_decode(file_get_contents('php://input') ?: '', true);
    $assessmentId = (int)($payload['assessment_id'] ?? 0);
    $userId = (int)($payload['user_id'] ?? 0);
    $controller->finalize($assessmentId, $userId);
});

$router->post('/api/career/run', static function () {
    $payload = json_decode(file_get_contents('php://input') ?: '', true);
    $userId = (int)($payload['user_id'] ?? 0);

    header('Content-Type: application/json; charset=utf-8');

    if ($userId <= 0) {
        http_response_code(422);
        echo json_encode(['error' => 'user_id is required'], JSON_UNESCAPED_UNICODE);
        return;
    }

    $repository = new \App\Repositories\CareerRepository();
    $matching = new \App\Services\CareerMatchingEngine();
    $roadmap = new \App\Services\CareerRoadmapEngine();
    $orchestrator = new \App\Orchestration\CareerOrchestrator($repository, $matching, $roadmap);

    $result = $orchestrator->run($userId);

    echo json_encode([
        'status' => 'success',
        'dna' => $result['dna'],
        'matches' => $result['matches'],
        'roadmap' => $result['roadmap'],
    ], JSON_UNESCAPED_UNICODE);
});

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

$router->dispatch($method, $uri);
