<?php declare(strict_types=1); ?>

<?php
$categories = [
  ['label' => 'تقنية ورقمية',    'pct' => 92, 'color' => 'oklch(55.78% 0.2268 264.05)', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
  ['label' => 'إبداعية',          'pct' => 86, 'color' => 'oklch(62.97% 0.2236 292.72)', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
  ['label' => 'إدارية وأعمال',    'pct' => 89, 'color' => 'oklch(56.93% 0.1508 172.89)', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
  ['label' => 'علمية وصحية',      'pct' => 87, 'color' => 'oklch(67.15% 0.1706 150.65)', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
  ['label' => 'مهنية وحرفية',     'pct' => 83, 'color' => 'oklch(76.87% 0.1867 69.66)',  'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
  ['label' => 'إنسانية واجتماعية','pct' => 82, 'color' => 'oklch(64.39% 0.2107 25.33)',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
];
?>

<section id="careers" class="py-20 section-alt">
  <div class="max-w-6xl mx-auto px-4 lg:px-6">

    <div class="text-center mb-14 reveal">
      <div class="badge badge-accent badge-outline mb-3">المسارات</div>
      <h2 class="text-3xl lg:text-4xl font-black text-base-content mb-4">
        استكشف جميع المسارات المهنية
      </h2>
      <p class="text-base-content/50 max-w-md mx-auto">
        بصيرة تغطي مئات المهن عبر ستة مجالات رئيسية
      </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
      <?php foreach ($categories as $cat): ?>
      <div class="card bg-base-100 border border-base-200 shadow-sm card-hover text-center reveal">
        <div class="card-body items-center gap-3 py-6">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center"
               style="background: <?= $cat['color'] ?>20">
            <svg class="w-6 h-6 transition-transform group-hover:scale-110"
                 style="color: <?= $cat['color'] ?>"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="<?= $cat['icon'] ?>"/>
            </svg>
          </div>
          <div class="font-semibold text-sm"><?= $cat['label'] ?></div>
          <div class="text-xs text-base-content/40">تطابق حتى</div>
          <div class="font-black text-lg" style="color: <?= $cat['color'] ?>"><?= $cat['pct'] ?>%</div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
