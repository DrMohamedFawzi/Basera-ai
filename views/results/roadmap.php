<?php
declare(strict_types=1);

/** @var array $roadmap */

$phases = [
    1 => ['data' => $roadmap['phase_1'] ?? [], 'label' => 'الأساسيات', 'color' => 'step-primary',   'badge' => 'badge-primary'],
    2 => ['data' => $roadmap['phase_2'] ?? [], 'label' => 'البناء',     'color' => 'step-secondary', 'badge' => 'badge-secondary'],
    3 => ['data' => $roadmap['phase_3'] ?? [], 'label' => 'الاحتراف',   'color' => 'step-accent',    'badge' => 'badge-accent'],
];

$typeIcons = [
    'course'       => '📚',
    'project'      => '🛠️',
    'reading'      => '📖',
    'certification'=> '📋',
    'challenge'    => '🏆',
    'job'          => '💼',
];

function taskIcon(string $type): string {
    global $typeIcons;
    return $typeIcons[$type] ?? '📌';
}
?>

<div class="card bg-base-100 shadow-lg border border-base-200">
  <div class="card-body p-6 lg:p-8">

    <h2 class="text-2xl font-bold flex items-center gap-2 mb-2">
      <span>🗺️</span> خارطة الطريق المخصصة
    </h2>
    <?php
      $topCareer = $roadmap['phase_1']['_top_career'] ?? ($roadmap['_top_career'] ?? null);
    ?>
    <?php if ($topCareer): ?>
      <p class="text-base-content/50 text-sm mb-6">
        مسار مُصمَّم خصيصاً للوصول إلى:
        <span class="font-semibold text-primary"><?= htmlspecialchars((string)$topCareer) ?></span>
      </p>
    <?php else: ?>
      <p class="text-base-content/50 text-sm mb-6">أكمل الاختبار لتظهر خارطة الطريق الخاصة بك.</p>
    <?php endif; ?>

    <?php
      $hasAnyTasks = false;
      foreach ($phases as $ph) {
          if (!empty($ph['data']['tasks'])) { $hasAnyTasks = true; break; }
      }
    ?>

    <?php if (!$hasAnyTasks): ?>
      <div class="flex flex-col items-center py-12 text-base-content/40">
        <div class="text-5xl mb-4">🗺️</div>
        <p class="text-sm">لا توجد بيانات خارطة بعد — أكمل اختبار الميول أولاً.</p>
      </div>
    <?php else: ?>

      <!-- DaisyUI Steps row (horizontal, desktop) -->
      <ul class="steps steps-horizontal w-full mb-10 hidden sm:flex">
        <?php foreach ($phases as $num => $ph): ?>
          <li class="step <?= !empty($ph['data']['tasks']) ? $ph['color'] : '' ?> text-xs font-medium">
            <?= $ph['label'] ?>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Phase cards -->
      <div class="space-y-8">
        <?php foreach ($phases as $num => $ph): ?>
          <?php $tasks = $ph['data']['tasks'] ?? []; ?>
          <div>
            <div class="flex items-center gap-3 mb-4">
              <span class="w-8 h-8 brand-gradient text-white rounded-full flex items-center justify-center font-bold text-sm shrink-0">
                <?= $num ?>
              </span>
              <h3 class="font-bold text-lg"><?= $ph['label'] ?></h3>
              <span class="badge <?= $ph['badge'] ?> badge-sm ms-auto"><?= count($tasks) ?> مهام</span>
            </div>

            <?php if (empty($tasks)): ?>
              <p class="text-sm text-base-content/40 ps-11">لا توجد مهام لهذه المرحلة.</p>
            <?php else: ?>
              <div class="space-y-3 ps-11">
                <?php foreach ($tasks as $task): ?>
                  <?php
                    $type     = (string)($task['type']     ?? 'course');
                    $title    = (string)($task['title']    ?? '');
                    $priority = (string)($task['priority'] ?? '');
                    $priorityBadge = match($priority) {
                        'high'   => 'badge-error',
                        'medium' => 'badge-warning',
                        'low'    => 'badge-ghost',
                        default  => 'badge-ghost',
                    };
                    $priorityLabel = match($priority) {
                        'high'   => 'مهم',
                        'medium' => 'متوسط',
                        'low'    => 'إضافي',
                        default  => '',
                    };
                  ?>
                  <div class="flex items-start gap-3 p-3 rounded-xl border border-base-200 hover:bg-base-200 transition">
                    <span class="text-xl shrink-0 mt-0.5"><?= taskIcon($type) ?></span>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium leading-snug"><?= htmlspecialchars($title) ?></p>
                      <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="text-xs text-base-content/40"><?= htmlspecialchars($type) ?></span>
                        <?php if ($priorityLabel): ?>
                          <span class="badge <?= $priorityBadge ?> badge-xs"><?= $priorityLabel ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

    <?php endif; ?>
  </div>
</div>
