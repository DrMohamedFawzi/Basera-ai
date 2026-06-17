<?php
declare(strict_types=1);

/** @var array $dna */
$skills   = $dna['skills_matrix'] ?? [];
$score    = (int) round((float)($dna['dna_score'] ?? 0));

// Map score to level label
$level = match(true) {
    $score >= 80 => ['label' => 'متقدم', 'color' => 'text-success', 'badge' => 'badge-success'],
    $score >= 55 => ['label' => 'متوسط', 'color' => 'text-warning', 'badge' => 'badge-warning'],
    $score >= 30 => ['label' => 'مبتدئ', 'color' => 'text-info',    'badge' => 'badge-info'],
    default      => ['label' => 'مستجد',  'color' => 'text-base-content/50', 'badge' => 'badge-ghost'],
};
?>

<!-- ── DNA Hero ── -->
<div class="card bg-base-100 shadow-lg border border-base-200">
  <div class="card-body p-6 lg:p-8">

    <div class="flex flex-col sm:flex-row items-center gap-6 mb-8">
      <!-- Radial progress ring -->
      <div class="relative shrink-0">
        <div class="radial-progress text-primary font-bold text-2xl"
             style="--value:<?= $score ?>; --size:9rem; --thickness:0.5rem"
             role="progressbar"
             aria-valuenow="<?= $score ?>"
             aria-valuemin="0"
             aria-valuemax="100">
          <?= $score ?>%
        </div>
      </div>

      <div>
        <div class="flex items-center gap-2 mb-1">
          <h2 class="text-2xl font-bold">DNA المهني</h2>
          <span class="badge <?= $level['badge'] ?>"><?= $level['label'] ?></span>
        </div>
        <p class="text-base-content/50 text-sm leading-relaxed max-w-sm">
          يعكس هذا الرسم مستوى تطابق مهاراتك مع أعلى مسار مهني تناسبك.
          كلما ارتفع الرقم كلما كانت توصياتنا أكثر دقة.
        </p>
        <?php if ($score === 0): ?>
          <div class="alert alert-info mt-3 py-2 text-sm">
            أكمل الاختبار أولاً لتظهر نتائج الـ DNA.
          </div>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!empty($skills)): ?>
    <!-- ── Skills bars ── -->
    <h3 class="font-semibold text-base mb-4 flex items-center gap-2">
      <svg class="w-5 h-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2h-2M9 3a2 2 0 002 2h2a2 2 0 002-2M9 3h6m-3 8v4m-2-2h4"/>
      </svg>
      محاور المهارات
    </h3>
    <div class="space-y-4">
      <?php foreach ($skills as $skill => $lvl): ?>
        <?php if (str_starts_with((string)$skill, '_')) continue; ?>
        <?php
          $pct = min(100, max(0, (int)$lvl));
          $barColor = match(true) {
              $pct >= 75 => 'progress-success',
              $pct >= 45 => 'progress-primary',
              $pct >= 20 => 'progress-warning',
              default    => 'progress-error',
          };
          $skillLabel = match($skill) {
              'php'         => 'PHP',
              'javascript'  => 'JavaScript',
              'python'      => 'Python',
              'databases'   => 'قواعد البيانات',
              'css'         => 'CSS',
              'react'       => 'React',
              'devops'      => 'DevOps',
              'leadership'  => 'القيادة',
              'architecture'=> 'هندسة البرمجيات',
              'mobile'      => 'تطوير الجوال',
              'design'      => 'التصميم',
              'data'        => 'تحليل البيانات',
              default       => $skill,
          };
        ?>
        <div>
          <div class="flex justify-between text-sm mb-1">
            <span class="font-medium"><?= htmlspecialchars($skillLabel) ?></span>
            <span class="text-base-content/50"><?= $pct ?> / 100</span>
          </div>
          <progress class="progress <?= $barColor ?> w-full h-3 bar-anim"
                    value="<?= $pct ?>"
                    max="100"></progress>
        </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-8 text-base-content/40">
      <svg class="w-12 h-12 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2h-2M9 3a2 2 0 002 2h2a2 2 0 002-2M9 3h6m-3 8v4m-2-2h4"/>
      </svg>
      <p class="text-sm">لا توجد بيانات مهارات بعد — أكمل الاختبار أولاً.</p>
    </div>
    <?php endif; ?>

  </div>
</div>
