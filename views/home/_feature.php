<?php declare(strict_types=1); ?>

<?php
$featurePoints = [
  ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'text' => 'تحليل موثوق مبني على ١٩ سؤالاً علمياً'],
  ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z',                                                                                                                                                                                                                                                                    'text' => 'نتائج فورية بدون انتظار'],
  ['icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0L9 7',                                                                                                                           'text' => 'خارطة طريق شخصية من ٣ مراحل'],
];

$dnaSkills = [
  ['label' => 'البرمجة والتطوير',  'pct' => 88, 'class' => 'progress-primary'],
  ['label' => 'حل المشكلات',       'pct' => 81, 'class' => 'progress-secondary'],
  ['label' => 'التحليل المنطقي',   'pct' => 74, 'class' => 'progress-accent'],
  ['label' => 'القيادة والتواصل',  'pct' => 61, 'class' => 'progress-warning'],
];

$topMatches = [
  ['career' => 'مطور برمجيات', 'pct' => 93, 'badge' => 'badge-primary',   'rank_bg' => 'bg-amber-400',  'rank_text' => 'text-amber-900'],
  ['career' => 'مهندس بيانات', 'pct' => 87, 'badge' => 'badge-secondary', 'rank_bg' => 'bg-slate-300',  'rank_text' => 'text-slate-700'],
  ['career' => 'محلل أنظمة',   'pct' => 79, 'badge' => 'badge-accent',    'rank_bg' => 'bg-orange-300', 'rank_text' => 'text-orange-900'],
];
?>

<section class="py-20">
  <div class="max-w-6xl mx-auto px-4 lg:px-6">
    <div class="flex flex-col lg:flex-row items-center gap-12">

      <!-- ── Text column ── -->
      <div class="flex-1 order-2 lg:order-1 reveal">
        <div class="badge badge-primary badge-outline mb-4">DNA المهني</div>
        <h2 class="text-3xl lg:text-4xl font-black mb-6">
          بصيرة لا تسألك ماذا تريد أن تصبح...<br>
          <span class="gradient-text">بل تساعدك على اكتشاف ما يناسبك فعلاً.</span>
        </h2>
        <p class="text-base-content/60 leading-relaxed mb-8">
          نظام ذكي يبني ملفاً رقمياً مهنياً بناءً على استجاباتك،
          ويقدم لك إرشادات وخطوات مخصصة لتحقيق أهدافك.
        </p>

        <div class="space-y-4 mb-10">
          <?php foreach ($featurePoints as $point): ?>
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                 style="background: oklch(55.78% 0.2268 264.05 / 0.1)">
              <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $point['icon'] ?>"/>
              </svg>
            </div>
            <span class="text-sm font-medium"><?= $point['text'] ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <a href="index.php/register" class="btn btn-primary shadow-sm">
          اكتشف توأمك المهني
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
          </svg>
        </a>
      </div>

      <!-- ── DNA mockup card ── -->
      <div class="flex-1 order-1 lg:order-2 flex justify-center reveal">
        <div class="w-full max-w-sm bg-base-100 border border-base-200 rounded-2xl shadow-xl p-6" aria-hidden="true">

          <!-- Header row -->
          <div class="flex items-center justify-between mb-6">
            <div>
              <div class="text-xs text-base-content/40 mb-0.5">ملفك المهني</div>
              <div class="font-bold text-lg">DNA التوأم</div>
            </div>
            <div class="radial-progress text-primary font-bold text-sm"
                 style="--value:87; --size:4rem; --thickness:0.35rem">87%</div>
          </div>

          <!-- Skill bars -->
          <div class="space-y-3 mb-6">
            <?php foreach ($dnaSkills as $skill): ?>
            <div>
              <div class="flex justify-between text-xs mb-1">
                <span class="text-base-content/70"><?= $skill['label'] ?></span>
                <span class="font-medium"><?= $skill['pct'] ?>%</span>
              </div>
              <progress class="progress <?= $skill['class'] ?> w-full h-2 bar-anim"
                        value="<?= $skill['pct'] ?>" max="100"></progress>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Top matches -->
          <div class="border-t border-base-200 pt-4">
            <div class="text-xs text-base-content/40 mb-3">أفضل التوافقات</div>
            <div class="space-y-2">
              <?php foreach ($topMatches as $idx => $match): ?>
              <div class="flex items-center gap-2">
                <span class="w-5 h-5 <?= $match['rank_bg'] ?> <?= $match['rank_text'] ?> rounded-full flex items-center justify-center text-xs font-bold shrink-0">
                  <?= $idx + 1 ?>
                </span>
                <span class="text-sm flex-1"><?= $match['career'] ?></span>
                <span class="badge <?= $match['badge'] ?> badge-sm"><?= $match['pct'] ?>%</span>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
