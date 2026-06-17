<?php declare(strict_types=1); ?>

<?php
$stats = [
  ['target' => '+50000', 'display' => '+50,000', 'label' => 'مسار مهني مُحلَّل',    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
  ['target' => '+500',   'display' => '+500',     'label' => 'وظيفة وتخصص',          'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
  ['target' => '+120',   'display' => '+120',     'label' => 'مهارة قابلة للتطوير',  'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
  ['target' => '+10000', 'display' => '+10,000',  'label' => 'مستخدم في نمو مستمر',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
  ['target' => '247',    'display' => '24/7',     'label' => 'مساعد مهني ذكي',        'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
];
?>

<section class="py-10 border-b border-base-200">
  <div class="max-w-5xl mx-auto px-4 lg:px-6">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 text-center">

      <?php foreach ($stats as $stat): ?>
      <div class="flex flex-col items-center gap-2 reveal">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-1"
             style="background: oklch(55.78% 0.2268 264.05 / 0.08)">
          <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $stat['icon'] ?>"/>
          </svg>
        </div>
        <div class="text-2xl font-black gradient-text stat-value"
             data-target="<?= htmlspecialchars($stat['target']) ?>">
          <?= htmlspecialchars($stat['display']) ?>
        </div>
        <div class="text-xs text-base-content/50 leading-tight"><?= $stat['label'] ?></div>
      </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>
