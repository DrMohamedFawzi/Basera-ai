<?php declare(strict_types=1); ?>

<?php
$steps = [
  ['num' => '01', 'title' => 'أنشئ حسابك',       'desc' => 'سجّل مجاناً في ثوانٍ',          'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
  ['num' => '02', 'title' => 'أجب على الاختبار', 'desc' => '١٩ سؤالاً مدروساً',             'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
  ['num' => '03', 'title' => 'احصل على DNA مهني', 'desc' => 'تحليل دقيق لمهاراتك',           'icon' => 'M9 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2h-2M9 3a2 2 0 002 2h2a2 2 0 002-2M9 3h6m-3 8v4m-2-2h4'],
  ['num' => '04', 'title' => 'استكشف التوافقات', 'desc' => 'أفضل ٥ مسارات مهنية لك',        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
  ['num' => '05', 'title' => 'اتبع خارطة طريقك', 'desc' => '٣ مراحل واضحة ومنظمة',         'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7'],
];
?>

<section id="how" class="py-20">
  <div class="max-w-6xl mx-auto px-4 lg:px-6">

    <div class="text-center mb-14 reveal">
      <div class="badge badge-secondary badge-outline mb-3">الطريقة</div>
      <h2 class="text-3xl lg:text-4xl font-black text-base-content mb-4">كيف تعمل بصيرة؟</h2>
      <p class="text-base-content/50 max-w-md mx-auto">
        خمس خطوات بسيطة تقودك من الحيرة إلى الوضوح المهني الكامل
      </p>
    </div>

    <div class="relative">
      <!-- Decorative connector line on desktop -->
      <div class="absolute top-10 inset-x-12 h-px bg-gradient-to-l from-transparent via-base-300 to-transparent hidden lg:block" aria-hidden="true"></div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
        <?php foreach ($steps as $step): ?>
        <div class="flex flex-col items-center text-center gap-4 reveal">
          <div class="relative">
            <div class="w-20 h-20 rounded-2xl bg-base-100 border-2 border-primary/20 flex items-center justify-center shadow-sm step-icon-ring">
              <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $step['icon'] ?>"/>
              </svg>
            </div>
            <span class="absolute -top-2 -end-2 w-6 h-6 brand-gradient text-white rounded-full text-xs flex items-center justify-center font-bold shadow">
              <?= $step['num'] ?>
            </span>
          </div>
          <div>
            <h3 class="font-bold text-base mb-1"><?= $step['title'] ?></h3>
            <p class="text-sm text-base-content/50"><?= $step['desc'] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>
