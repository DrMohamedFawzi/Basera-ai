<?php
declare(strict_types=1);
$pageTitle = 'إنشاء حساب';
$bodyClass = 'min-h-screen';
include __DIR__ . '/../layouts/head.php';
?>

<div class="min-h-screen flex">

  <!-- ── Hero panel ── -->
  <div class="hidden lg:flex lg:w-5/12 brand-gradient flex-col items-center justify-center p-12 text-white relative overflow-hidden">

    <div class="absolute -top-16 -start-16 w-64 h-64 rounded-full bg-white/5"></div>
    <div class="absolute -bottom-24 -end-12 w-80 h-80 rounded-full bg-white/5"></div>

    <div class="relative z-10 max-w-xs text-center">
      <div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
        </svg>
      </div>

      <h1 class="text-4xl font-bold mb-3">بصيرة AI</h1>
      <p class="text-white/80 text-lg mb-10 leading-relaxed">رحلتك المهنية تبدأ من هنا</p>

      <div class="space-y-3 text-start">
        <div class="flex items-center gap-3 text-sm">
          <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs shrink-0">١</span>
          أنشئ حسابك في ثوانٍ
        </div>
        <div class="flex items-center gap-3 text-sm">
          <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs shrink-0">٢</span>
          أجب على اختبار الميول
        </div>
        <div class="flex items-center gap-3 text-sm">
          <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs shrink-0">٣</span>
          احصل على تحليل DNA مهني فوري
        </div>
        <div class="flex items-center gap-3 text-sm">
          <span class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center font-bold text-xs shrink-0">٤</span>
          اتبع خارطة طريقك المخصصة
        </div>
      </div>
    </div>
  </div>

  <!-- ── Form panel ── -->
  <div class="flex-1 flex items-center justify-center bg-base-100 p-6 lg:p-12">
    <div class="w-full max-w-md">

      <div class="lg:hidden text-center mb-8">
        <div class="w-14 h-14 brand-gradient rounded-2xl flex items-center justify-center mx-auto mb-3">
          <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-primary">بصيرة AI</h1>
      </div>

      <h2 class="text-2xl font-bold text-base-content mb-1">أنشئ حسابك 🚀</h2>
      <p class="text-base-content/50 text-sm mb-8">ابدأ رحلتك المهنية الآن — مجانًا</p>

      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error mb-6 toast-anim">
          <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
          <span class="text-sm"><?= htmlspecialchars((string)$_GET['error']) ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" action="index.php/register" class="space-y-5">
        <?= \App\Core\Csrf::field() ?>

        <!-- Name -->
        <div class="form-control">
          <label class="label pb-1"><span class="label-text font-medium">الاسم الكامل</span></label>
          <label class="input input-bordered flex items-center gap-2 focus-within:input-primary">
            <svg class="w-4 h-4 opacity-40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <input type="text" name="name" class="grow" placeholder="محمد أحمد"
                   required autocomplete="name" />
          </label>
        </div>

        <!-- Email -->
        <div class="form-control">
          <label class="label pb-1"><span class="label-text font-medium">البريد الإلكتروني</span></label>
          <label class="input input-bordered flex items-center gap-2 focus-within:input-primary">
            <svg class="w-4 h-4 opacity-40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <input type="email" name="email" class="grow" placeholder="example@email.com"
                   required autocomplete="email" />
          </label>
        </div>

        <!-- Password -->
        <div class="form-control">
          <label class="label pb-1"><span class="label-text font-medium">كلمة المرور</span></label>
          <label class="input input-bordered flex items-center gap-2 focus-within:input-primary">
            <svg class="w-4 h-4 opacity-40 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <input type="password" name="password" class="grow" placeholder="٨ أحرف على الأقل"
                   required minlength="8" autocomplete="new-password" />
          </label>
          <label class="label pt-1"><span class="label-text-alt text-base-content/40">يجب أن تكون ٨ أحرف على الأقل</span></label>
        </div>

        <button type="submit" class="btn btn-primary w-full mt-2 shadow-sm">
          إنشاء الحساب
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
          </svg>
        </button>
      </form>

      <div class="divider text-base-content/30 my-6">أو</div>
      <p class="text-center text-sm text-base-content/60">
        لديك حساب؟
        <a href="index.php/login" class="link link-primary font-semibold">تسجيل الدخول</a>
      </p>
    </div>
  </div>

</div>

<?php include __DIR__ . '/../layouts/foot.php'; ?>
