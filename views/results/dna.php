<?php

declare(strict_types=1);

/** @var array $dna */
$skills = $dna['skills_matrix'] ?? [];
?>
<div class="card bg-base-100 shadow-xl border-t-4 border-primary">
  <div class="card-body">
    <h2 class="card-title text-2xl">DNA التوأم المهني</h2>
    <div class="mt-3">
      <div class="flex items-center justify-between">
        <span class="text-sm opacity-70">Score</span>
        <span class="font-bold"><?= htmlspecialchars((string)($dna['dna_score'] ?? 0)) ?></span>
      </div>
      <div class="radial-progress text-primary" style="--value:<?= (int)round((float)($dna['dna_score'] ?? 0)); ?>; --size:8rem; --thickness:0.35rem" role="progressbar">
        <?= (int)round((float)($dna['dna_score'] ?? 0)); ?>%
      </div>
    </div>

    <div class="mt-6">
      <h3 class="font-semibold mb-2">محاور المهارات (Skills Matrix)</h3>
      <div class="overflow-x-auto">
        <table class="table w-full">
          <thead>
            <tr>
              <th>المهارة</th>
              <th>المستوى</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($skills)): ?>
              <tr><td colspan="2" class="text-center opacity-70">لا توجد بيانات DNA بعد</td></tr>
            <?php else: ?>
              <?php foreach ($skills as $skill => $level): ?>
                <?php if (str_starts_with((string)$skill, '_')) continue; ?>
                <tr>
                  <td><?= htmlspecialchars((string)$skill) ?></td>
                  <td>
                    <progress class="progress progress-success w-56" value="<?= (int)$level ?>" max="100"></progress>
                    <span class="ml-2 font-semibold"><?= (int)$level ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

