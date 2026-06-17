<?php
declare(strict_types=1);

/** @var array $matches */

$rankColors = [
    0 => ['bg' => 'bg-amber-400',  'text' => 'text-amber-900', 'ring' => 'text-amber-500'],
    1 => ['bg' => 'bg-slate-300',  'text' => 'text-slate-700', 'ring' => 'text-slate-400'],
    2 => ['bg' => 'bg-orange-300', 'text' => 'text-orange-900','ring' => 'text-orange-400'],
];
?>

<div class="card bg-base-100 shadow-lg border border-base-200">
  <div class="card-body p-6 lg:p-8">

    <h2 class="text-2xl font-bold flex items-center gap-3 mb-6">
      <span class="w-9 h-9 brand-gradient rounded-lg flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
      </span>
      أفضل التوافقات المهنية
    </h2>

    <?php if (empty($matches)): ?>
      <div class="flex flex-col items-center py-12 text-base-content/40">
        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <p class="text-sm">أكمل اختبار الميول أولاً لتظهر التوافقات.</p>
      </div>
    <?php else: ?>
      <div class="space-y-5">
        <?php foreach ($matches as $i => $m): ?>
          <?php
            $score    = min(100, max(0, (int)($m['score'] ?? 0)));
            $career   = htmlspecialchars((string)($m['career'] ?? ''));
            $rank     = $rankColors[$i] ?? null;
            $ringColor = match(true) {
                $score >= 80 => 'text-success',
                $score >= 55 => 'text-warning',
                $score >= 30 => 'text-info',
                default      => 'text-base-content/30',
            };
            $barColor = match(true) {
                $score >= 80 => 'progress-success',
                $score >= 55 => 'progress-primary',
                $score >= 30 => 'progress-warning',
                default      => 'progress-error',
            };
          ?>
          <div class="flex items-center gap-4">

            <!-- Rank badge -->
            <div class="shrink-0 w-9 flex justify-center">
              <?php if ($rank): ?>
                <span class="w-8 h-8 <?= $rank['bg'] ?> <?= $rank['text'] ?> rounded-full flex items-center justify-center font-bold text-sm">
                  <?= $i + 1 ?>
                </span>
              <?php else: ?>
                <span class="w-8 h-8 bg-base-200 text-base-content/40 rounded-full flex items-center justify-center font-bold text-sm">
                  <?= $i + 1 ?>
                </span>
              <?php endif; ?>
            </div>

            <!-- Score ring -->
            <div class="shrink-0">
              <div class="radial-progress <?= $ringColor ?> font-semibold text-xs"
                   style="--value:<?= $score ?>; --size:3.5rem; --thickness:0.3rem">
                <?= $score ?>%
              </div>
            </div>

            <!-- Career details -->
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2 flex-wrap mb-1.5">
                <span class="font-semibold text-base truncate"><?= $career ?></span>
                <?php if ($i === 0): ?>
                  <span class="badge badge-primary badge-sm">الأفضل تطابقاً</span>
                <?php endif; ?>
              </div>
              <progress class="progress <?= $barColor ?> w-full h-2 bar-anim"
                        value="<?= $score ?>"
                        max="100"></progress>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</div>
