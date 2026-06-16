<?php

declare(strict_types=1);

// Autoload: composer vendor if available, otherwise PSR-4 manual fallback
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    spl_autoload_register(static function (string $class): void {
        $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    });
}

use App\Core\Env;
use App\Core\Session;
use App\Core\SimpleRouter;
use App\Core\Database;
use App\Controllers\AssessmentController;
use App\Controllers\AuthController;

Env::load(__DIR__ . '/../.env');
Session::start();

$router             = new SimpleRouter();
$assessmentCtrl     = new AssessmentController();
$authCtrl           = new AuthController();

// ── Auth guard (redirect to login if not authenticated) ──────────────────────
$guard = static function (): void {
    if (!Session::isLoggedIn()) {
        header('Location: index.php/login');
        exit;
    }
};

// ── Public routes ─────────────────────────────────────────────────────────────

$router->get('/login', static function () use ($authCtrl): void {
    if (Session::isLoggedIn()) {
        header('Location: index.php/');
        exit;
    }
    $authCtrl->showLogin();
});

$router->post('/login', static function () use ($authCtrl): void {
    $authCtrl->login();
});

$router->get('/register', static function () use ($authCtrl): void {
    if (Session::isLoggedIn()) {
        header('Location: index.php/');
        exit;
    }
    $authCtrl->showRegister();
});

$router->post('/register', static function () use ($authCtrl): void {
    $authCtrl->register();
});

$router->post('/logout', static function () use ($authCtrl): void {
    $authCtrl->logout();
});

// ── Protected UI routes ───────────────────────────────────────────────────────

$router->get('/', static function () use ($guard): void {
    $guard();
    include __DIR__ . '/../views/assessment/wizard.php';
});

$router->get('/results', static function () use ($guard): void {
    $guard();
    include __DIR__ . '/results.php';
});

// ── Protected API routes ──────────────────────────────────────────────────────

$router->post('/api/assessment/start', static function () use ($assessmentCtrl, $guard): void {
    $guard();
    $assessmentCtrl->start(Session::userId());
});

$router->post('/api/assessment/save', static function () use ($assessmentCtrl, $guard): void {
    $guard();
    $assessmentCtrl->saveResponse();
});

$router->get('/api/assessment/question/next', static function () use ($assessmentCtrl, $guard): void {
    $guard();
    $assessmentCtrl->getNextQuestion((int)($_GET['assessment_id'] ?? 0));
});

$router->post('/api/assessment/finalize', static function () use ($assessmentCtrl, $guard): void {
    $guard();
    $payload      = json_decode(file_get_contents('php://input') ?: '', true);
    $assessmentId = (int)($payload['assessment_id'] ?? 0);
    $assessmentCtrl->finalize($assessmentId, Session::userId());
});

$router->get('/api/assessment/restart', static function () use ($guard): void {
    $guard();
    $db     = Database::getConnection();
    $userId = Session::userId();
    // Mark all in-progress assessments as completed so start() creates a fresh one
    $db->prepare(
        "UPDATE user_assessments SET status='completed', completed_at=NOW()
         WHERE user_id=:user_id AND status='in_progress'"
    )->execute(['user_id' => $userId]);
    header('Location: index.php/');
    exit;
});

$router->post('/api/career/run', static function () use ($guard): void {
    $guard();
    header('Content-Type: application/json; charset=utf-8');

    $userId = Session::userId();
    $db     = Database::getConnection();

    $repository  = new \App\Repositories\CareerRepository($db);
    $matching    = new \App\Services\CareerMatchingEngine($db);
    $roadmapEng  = new \App\Services\CareerRoadmapEngine();
    $orchestrator = new \App\Orchestration\CareerOrchestrator($repository, $matching, $roadmapEng);

    $result = $orchestrator->run($userId);

    echo json_encode([
        'status'  => 'success',
        'dna'     => $result['dna'],
        'matches' => $result['matches'],
        'roadmap' => $result['roadmap'],
    ], JSON_UNESCAPED_UNICODE);
});

// ── Dispatch ──────────────────────────────────────────────────────────────────

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri    = $_SERVER['REQUEST_URI'] ?? '/';

echo $router->dispatch($method, $uri) ?? '';
