<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Repositories\CareerRepository;
use App\Services\CareerMatchingEngine;
use App\Services\CareerRoadmapEngine;
use App\Orchestration\CareerOrchestrator;

$userId = (int)($_GET['user_id'] ?? 1);

$repository = new CareerRepository();
$matching = new CareerMatchingEngine();
$roadmap = new CareerRoadmapEngine();
$orchestrator = new CareerOrchestrator($repository, $matching, $roadmap);

$result = $orchestrator->run($userId);

dna_attach: $result;

$dna = $result['dna'] ?? null;
$matches = $result['matches'] ?? [];
$roadmapData = $result['roadmap'] ?? [];

// Basic RTL HTML
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="corporate">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>نتائج الرحلة - بصيرة AI</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">
  <div class="navbar bg-base-100 shadow-lg px-8">
    <div class="flex-1">
      <a class="text-2xl font-bold text-primary">بصيرة AI</a>
    </div>
    <div class="flex-none">
      <div class="badge badge-primary">Results</div>
    </div>
  </div>

  <main class="max-w-6xl mx-auto p-6">
    <div class="mb-6">
      <h1 class="text-3xl font-bold">رحلة: Assessment → DNA → Matching → Roadmap</h1>
      <div class="text-sm opacity-70 mt-1">user_id: <?= (int)$userId ?></div>
    </div>

    <div class="grid grid-cols-1 gap-6">
      <?php if ($dna): ?>
        <?php include __DIR__ . '/../views/results/dna.php'; ?>
      <?php else: ?>
        <div class="alert alert-info">لا يوجد DNA لهذه الّـuser بعد. أكمل اختبار الميول أولاً.</div>
      <?php endif; ?>

      <?php
        $matches = $matches;
        include __DIR__ . '/../views/results/matches.php';
      ?>

      <?php
        $roadmap = $roadmapData;
        include __DIR__ . '/../views/results/roadmap.php';
      ?>
    </div>
  </main>
</body>
</html>

