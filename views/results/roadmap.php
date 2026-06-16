<?php

declare(strict_types=1);

/** @var array $roadmap */
?>
<div class="card bg-base-100 shadow-xl border-t-4 border-accent">
  <div class="card-body">
    <h2 class="card-title text-2xl">Roadmap متكيف</h2>

    <?php
      $p1 = $roadmap['phase_1'] ?? [];
      $p2 = $roadmap['phase_2'] ?? [];
      $p3 = $roadmap['phase_3'] ?? [];
    ?>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
      <div class="border rounded-2xl p-4">
        <h3 class="font-bold text-lg mb-3">Phase 1</h3>
        <ul class="space-y-2">
          <?php foreach (($p1['tasks'] ?? []) as $t): ?>
            <li class="text-sm">• <?= htmlspecialchars((string)($t['title'] ?? '')) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="border rounded-2xl p-4">
        <h3 class="font-bold text-lg mb-3">Phase 2</h3>
        <ul class="space-y-2">
          <?php foreach (($p2['tasks'] ?? []) as $t): ?>
            <li class="text-sm">• <?= htmlspecialchars((string)($t['title'] ?? '')) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="border rounded-2xl p-4">
        <h3 class="font-bold text-lg mb-3">Phase 3</h3>
        <ul class="space-y-2">
          <?php foreach (($p3['tasks'] ?? []) as $t): ?>
            <li class="text-sm">• <?= htmlspecialchars((string)($t['title'] ?? '')) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

  </div>
</div>

