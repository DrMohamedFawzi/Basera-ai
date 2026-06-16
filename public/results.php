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

$pageTitle = 'نتائجك المهنية';
$bodyClass = 'bg-base-200 min-h-screen';
include __DIR__ . '/../views/layouts/head.php';
?>

<?php include __DIR__ . '/../views/layouts/navbar.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-10">

  <div class="flex items-center justify-between gap-4 mb-8 flex-wrap">
    <div>
      <h1 class="text-3xl font-bold">نتائجك المهنية</h1>
      <p class="text-base-content/50 text-sm mt-1">تحليل شامل مبني على إجاباتك في اختبار الميول</p>
    </div>
    <a href="index.php/api/assessment/restart"
       class="btn btn-outline btn-sm"
       onclick="return confirm('سيبدأ اختبار جديد وستُمسح النتائج الحالية. هل أنت متأكد؟')">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
      </svg>
      إعادة الاختبار
    </a>
  </div>

  <?php if (!$dna && empty($matches)): ?>
    <div class="card bg-base-100 shadow-lg border border-base-200 text-center py-16">
      <div class="text-5xl mb-4">🎯</div>
      <h2 class="text-xl font-bold mb-2">لم تُكمل الاختبار بعد</h2>
      <p class="text-base-content/50 text-sm mb-6">أجب على جميع أسئلة الاختبار لتحصل على تحليلك المهني الكامل.</p>
      <div>
        <a href="index.php/" class="btn btn-primary">ابدأ الاختبار الآن</a>
      </div>
    </div>
  <?php else: ?>
    <div class="space-y-6">
      <?php include __DIR__ . '/../views/results/dna.php'; ?>
      <?php include __DIR__ . '/../views/results/matches.php'; ?>
      <?php include __DIR__ . '/../views/results/roadmap.php'; ?>
    </div>
  <?php endif; ?>

</main>

<?php include __DIR__ . '/../views/layouts/foot.php'; ?>
