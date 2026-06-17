<?php
declare(strict_types=1);

/** @var array $roadmap */

$phases = [
    1 => ['data' => $roadmap['phase_1'] ?? [], 'label' => 'الأساسيات', 'color' => 'step-primary',   'badge' => 'badge-primary'],
    2 => ['data' => $roadmap['phase_2'] ?? [], 'label' => 'البناء',     'color' => 'step-secondary', 'badge' => 'badge-secondary'],
    3 => ['data' => $roadmap['phase_3'] ?? [], 'label' => 'الاحتراف',   'color' => 'step-accent',    'badge' => 'badge-accent'],
];

// SVG paths keyed by task type
$typeIconPaths = [
    'course'        => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    'project'       => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
    'reading'       => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    'certification' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
    'challenge'     => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
    'job'           => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    'default'       => 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z',
];

function taskIconSvg(string $type, array $paths): string {
    $d = $paths[$type] ?? $paths['default'];
    return '<svg class="w-4 h-4 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">'
         . '<path stroke-linecap="round" stroke-linejoin="round" d="' . $d . '"/>'
         . '</svg>';
}
?>

<div class="card bg-base-100 shadow-lg border border-base-200">
  <div class="card-body p-6 lg:p-8">

    <h2 class="text-2xl font-bold flex items-center gap-3 mb-2">
      <span class="w-9 h-9 brand-gradient rounded-lg flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"/>
        </svg>
      </span>
      خارطة الطريق المخصصة
    </h2>

    <?php $topCareer = $roadmap['phase_1']['_top_career'] ?? ($roadmap['_top_career'] ?? null); ?>
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
        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7"/>
        </svg>
        <p class="text-sm">لا توجد بيانات خارطة بعد — أكمل اختبار الميول أولاً.</p>
      </div>
    <?php else: ?>

      <!-- DaisyUI Steps -->
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
                    <?= taskIconSvg($type, $typeIconPaths) ?>
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
