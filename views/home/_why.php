<?php declare(strict_types=1); ?>

<?php
$problems = [
  [
    'icon'  => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'title' => 'عدم معرفة الذات',
    'desc'  => 'لا تعرف قدراتك الحقيقية واهتماماتك — ما يناسبك فعلاً.',
    'color' => 'oklch(55.78% 0.2268 264.05)',
  ],
  [
    'icon'  => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'title' => 'معلومات غير دقيقة',
    'desc'  => 'معلومات مشتتة وغير موثوقة عن المهن والتخصصات المتاحة.',
    'color' => 'oklch(56.93% 0.1508 172.89)',
  ],
  [
    'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'title' => 'ضغط المجتمع',
    'desc'  => 'تأثير الأهل والأصدقاء والتوقعات الاجتماعية والأسرية.',
    'color' => 'oklch(62.97% 0.2236 292.72)',
  ],
  [
    'icon'  => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
    'title' => 'اختيارات عشوائية',
    'desc'  => 'قرارات مصيرية تُتخذ بدون بيانات أو منهج واضح.',
    'color' => 'oklch(76.87% 0.1867 69.66)',
  ],
];
?>

<section id="why" class="py-20 section-alt">
  <div class="max-w-6xl mx-auto px-4 lg:px-6">

    <div class="text-center mb-14 reveal">
      <div class="badge badge-primary badge-outline mb-3">المشكلة</div>
      <h2 class="text-3xl lg:text-4xl font-black text-base-content mb-4">
        لماذا يحتار معظم الشباب في اختيار مستقبلهم؟
      </h2>
      <p class="text-base-content/50 max-w-xl mx-auto">
        ٧٠٪ من الطلاب يختارون تخصصاتهم بناءً على عوامل خاطئة — بصيرة تغيّر هذه المعادلة.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($problems as $problem): ?>
      <div class="card bg-base-100 border border-base-200 shadow-sm card-hover reveal">
        <div class="card-body gap-4">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center"
               style="background: <?= $problem['color'] ?>20">
            <svg class="w-6 h-6" style="color: <?= $problem['color'] ?>"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="<?= $problem['icon'] ?>"/>
            </svg>
          </div>
          <h3 class="font-bold text-lg"><?= $problem['title'] ?></h3>
          <p class="text-sm text-base-content/60 leading-relaxed"><?= $problem['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
