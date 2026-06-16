<?php
declare(strict_types=1);

/** @var array $matches */

$medals = ['🥇', '🥈', '🥉'];
?>

<div class="card bg-base-100 shadow-lg border border-base-200">
  <div class="card-body p-6 lg:p-8">

    <h2 class="text-2xl font-bold flex items-center gap-2 mb-6">
      <span>🎯</span> أفضل التوافقات المهنية
    </h2>

    <?php if (empty($matches)): ?>
      <div class="flex flex-col items-center py-12 text-base-content/40">
        <div class="text-5xl mb-4">🎯</div>
        <p class="text-sm">أكمل اختبار الميول أولاً لتظهر التوافقات.</p>
      </div>
    <?php else: ?>
      <div class="space-y-5">
        <?php foreach ($matches as $i => $m): ?>
          <?php
            $score     = min(100, max(0, (int)($m['score'] ?? 0)));
            $career    = htmlspecialchars((string)($m['career'] ?? ''));
            $medal     = $medals[$i] ?? null;
            $ringColor = match(true) {
                $score >= 80 => 'text-success',
                $score >= 55 => 'text-warning',
                $score >= 30 => 'text-info',
                default      => 'text-base-content/30',
            };
            $barColor  = match(true) {
                $score >= 80 => 'progress-success',
                $score >= 55 => 'progress-primary',
                $score >= 30 => 'progress-warning',
                default      => 'progress-error',
            };
          ?>
          <div class="flex items-center gap-4">

            <!-- Rank / medal -->
            <div class="shrink-0 text-2xl w-9 text-center">
              <?= $medal ?? '<span class="text-base font-bold text-base-content/30">' . ($i + 1) . '</span>' ?>
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
