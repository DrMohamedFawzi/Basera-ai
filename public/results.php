<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Session;
use App\Core\Database;
use App\Services\CareerMatchingEngine;
use App\Services\CareerRoadmapEngine;
use App\Repositories\CareerRepository;
use App\Orchestration\CareerOrchestrator;

$userId = Session::userId();

$db          = Database::getConnection();
$repository  = new CareerRepository($db);
$matching    = new CareerMatchingEngine($db);
$roadmapEng  = new CareerRoadmapEngine();
$orchestrator = new CareerOrchestrator($repository, $matching, $roadmapEng);

$result = $orchestrator->run($userId);

$dna        = $result['dna'] ?? null;
$matches    = $result['matches'] ?? [];
$roadmap    = $result['roadmap'] ?? [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="corporate">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>نتائج الرحلة - بصيرة AI</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

  <?php include __DIR__ . '/../views/layouts/navbar.php'; ?>

  <main class="max-w-6xl mx-auto p-6">
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-3xl font-bold">نتائجك المهنية</h1>
      <a href="index.php/api/assessment/restart" class="btn btn-outline btn-sm"
         onclick="return confirm('سيبدأ اختبار جديد. هل أنت متأكد؟')">
        إعادة الاختبار
      </a>
    </div>

    <div class="grid grid-cols-1 gap-6">
      <?php if ($dna): ?>
        <?php include __DIR__ . '/../views/results/dna.php'; ?>
      <?php else: ?>
        <div class="alert alert-info">
          لا يوجد DNA لهذا المستخدم بعد. أكمل اختبار الميول أولاً.
          <a href="index.php/" class="btn btn-sm btn-primary mr-4">ابدأ الاختبار</a>
        </div>
      <?php endif; ?>

      <?php include __DIR__ . '/../views/results/matches.php'; ?>
      <?php include __DIR__ . '/../views/results/roadmap.php'; ?>
    </div>
  </main>
</body>
</html>
