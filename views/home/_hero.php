<?php declare(strict_types=1); ?>

<section class="landing-hero min-h-screen flex items-center pt-16 overflow-hidden relative">

  <!-- Decorative background glows -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none select-none" aria-hidden="true">
    <div class="absolute top-1/4 start-1/4 w-96 h-96 rounded-full opacity-10 hero-glow-1"></div>
    <div class="absolute bottom-1/4 end-1/4 w-80 h-80 rounded-full opacity-8 hero-glow-2"></div>
    <div class="absolute inset-0 hero-grid-bg"></div>
  </div>

  <div class="max-w-6xl mx-auto px-4 lg:px-6 py-20 lg:py-28 relative z-10">
    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

      <!-- ── Text column ── -->
      <div class="flex-1 text-center lg:text-start">

        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-white/10 text-white/80 border border-white/15 rounded-full px-4 py-1.5 text-xs font-medium mb-6">
          <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse" aria-hidden="true"></span>
          ذكاء اصطناعي لمسارك المهني
        </div>

        <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight mb-2">
          اكتشف مستقبلك المهني
        </h1>
        <h1 class="text-4xl lg:text-6xl font-black leading-tight mb-6 hero-gradient-text">
          قبل أن تختاره
        </h1>

        <p class="text-white/60 text-lg leading-relaxed max-w-xl mb-10">
          بصيرة تواكب شبابنا نحو أفضل القرارات التعليمية والمهنية،
          بناءً على شخصيتك ومهاراتك وطموحاتك — لا على الصدفة أو ضغط المجتمع.
        </p>

        <!-- CTAs -->
        <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
          <a href="index.php/register" class="btn btn-lg btn-hero-primary px-8 shadow-lg">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            ابدأ رحلتك مجاناً
          </a>
          <a href="#how" class="btn btn-lg btn-ghost text-white/80 border border-white/20 hover:bg-white/10 px-8">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            كيف تعمل؟
          </a>
        </div>

        <!-- Social proof -->
        <div class="mt-10 flex items-center gap-4 justify-center lg:justify-start">
          <div class="flex -space-x-2 rtl:space-x-reverse" aria-hidden="true">
            <?php
            $avatarColors   = ['bg-primary','bg-secondary','bg-accent','bg-warning','bg-success'];
            $avatarInitials = ['م','ا','س','ع','ف'];
            for ($i = 0; $i < 5; $i++):
            ?>
            <div class="w-8 h-8 <?= $avatarColors[$i] ?> rounded-full border-2 border-white/20 flex items-center justify-center text-white text-xs font-bold">
              <?= $avatarInitials[$i] ?>
            </div>
            <?php endfor; ?>
          </div>
          <p class="text-white/60 text-sm">
            <span class="text-white font-bold">+10,000</span> شخص يستخدم بصيرة الآن
          </p>
        </div>

      </div>

      <!-- ── Visual card column ── -->
      <div class="flex-1 flex justify-center lg:justify-end">
        <div class="relative w-72 h-72 lg:w-96 lg:h-96 floating" aria-hidden="true">

          <!-- Outer glow ring -->
          <div class="absolute inset-0 rounded-full glow-ring opacity-60"></div>

          <!-- Main card -->
          <div class="absolute inset-8 bg-white/5 backdrop-blur border border-white/10 rounded-3xl p-5 flex flex-col justify-between">

            <!-- DNA score header -->
            <div class="flex items-center justify-between">
              <div>
                <div class="text-white/50 text-xs mb-1">DNA المهني</div>
                <div class="text-white font-bold text-2xl">87%</div>
              </div>
              <!-- Mini ring SVG -->
              <div class="relative w-14 h-14">
                <svg class="w-14 h-14 -rotate-90" viewBox="0 0 56 56">
                  <circle cx="28" cy="28" r="22" fill="none" stroke="white" stroke-opacity="0.1" stroke-width="4"/>
                  <circle cx="28" cy="28" r="22" fill="none"
                          stroke="oklch(56.93% 0.1508 172.89)"
                          stroke-width="4"
                          stroke-dasharray="120 138"
                          stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                  <svg class="w-5 h-5 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2h-2M9 3a2 2 0 002 2h2a2 2 0 002-2M9 3h6"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Skill bars -->
            <div class="space-y-2">
              <?php
              $heroSkills = [
                ['label' => 'البرمجة', 'pct' => 88, 'color' => 'oklch(55.78% 0.2268 264.05)'],
                ['label' => 'التحليل', 'pct' => 74, 'color' => 'oklch(56.93% 0.1508 172.89)'],
                ['label' => 'القيادة', 'pct' => 61, 'color' => 'oklch(62.97% 0.2236 292.72)'],
              ];
              foreach ($heroSkills as $skill):
              ?>
              <div>
                <div class="flex justify-between text-white/50 text-xs mb-1">
                  <span><?= $skill['label'] ?></span>
                  <span><?= $skill['pct'] ?>%</span>
                </div>
                <div class="h-1.5 rounded-full bg-white/10">
                  <div class="h-full rounded-full" style="width:<?= $skill['pct'] ?>%; background:<?= $skill['color'] ?>"></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Top match pill -->
            <div class="bg-white/5 rounded-xl p-3 flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                   style="background: oklch(55.78% 0.2268 264.05 / 0.3)">
                <svg class="w-4 h-4" style="color: oklch(75% 0.15 264)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
              </div>
              <div>
                <div class="text-white/40 text-xs">الأعلى تطابقاً</div>
                <div class="text-white text-sm font-semibold">مطور برمجيات</div>
              </div>
              <div class="ms-auto text-emerald-400 font-bold text-sm">93%</div>
            </div>

          </div><!-- /card -->

          <!-- Floating badge: roadmap -->
          <div class="absolute -bottom-4 -start-4 bg-base-100 border border-base-200 rounded-2xl shadow-xl px-4 py-2.5 flex items-center gap-2">
            <div class="w-7 h-7 brand-gradient rounded-full flex items-center justify-center shrink-0">
              <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div>
              <div class="text-xs font-semibold text-base-content">خارطة طريقك جاهزة</div>
              <div class="text-xs text-base-content/40">٣ مراحل واضحة</div>
            </div>
          </div>

          <!-- Floating badge: quiz -->
          <div class="absolute -top-4 -end-4 bg-base-100 border border-base-200 rounded-2xl shadow-xl px-4 py-2.5 flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0"
                 style="background: oklch(56.93% 0.1508 172.89 / 0.15)">
              <svg class="w-3.5 h-3.5" style="color: oklch(56.93% 0.1508 172.89)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
              </svg>
            </div>
            <div>
              <div class="text-xs font-semibold text-base-content">١٩ سؤال فقط</div>
              <div class="text-xs text-base-content/40">اختبار الميول</div>
            </div>
          </div>

        </div>
      </div><!-- /visual column -->

    </div>
  </div>

  <!-- Wave separator -->
  <div class="absolute bottom-0 inset-x-0" aria-hidden="true">
    <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-10 lg:h-14 wave-fill">
      <path d="M0,60 C360,0 1080,60 1440,20 L1440,60 Z"/>
    </svg>
  </div>

</section>
