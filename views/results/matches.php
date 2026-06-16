<?php

declare(strict_types=1);

/** @var array $matches */
?>
<div class="card bg-base-100 shadow-xl border-t-4 border-secondary">
  <div class="card-body">
    <h2 class="card-title text-2xl">أفضل التوافقات (Matches)</h2>

    <?php if (empty($matches)): ?>
      <div class="alert alert-info mt-4">لا توجد نتائج مطابقة حالياً. أكمل رحلة Assessment أولاً.</div>
    <?php else: ?>
      <div class="space-y-3 mt-4">
        <?php foreach ($matches as $m): ?>
          <div class="flex items-center justify-between gap-4">
            <div>
              <div class="font-semibold text-lg"><?= htmlspecialchars((string)($m['career'] ?? '')) ?></div>
              <div class="text-sm opacity-70">Career</div>
            </div>
            <div class="text-right">
              <div class="badge badge-primary"><?= (int)($m['score'] ?? 0) ?>%</div>
            </div>
          </div>
          <progress class="progress progress-primary w-full" value="<?= (int)($m['score'] ?? 0) ?>" max="100"></progress>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</div>

