<?php declare(strict_types=1); ?>

<header id="landing-navbar"
        class="fixed top-0 inset-x-0 z-50 bg-base-100/90 backdrop-blur border-b border-base-200/60 transition-all duration-200">
  <div class="max-w-6xl mx-auto px-4 lg:px-6 h-16 flex items-center justify-between gap-4">

    <!-- Brand -->
    <a href="index.php/" class="flex items-center gap-2 shrink-0">
      <div class="w-8 h-8 brand-gradient rounded-lg flex items-center justify-center shadow-sm">
        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
        </svg>
      </div>
      <span class="font-bold text-xl">بصيرة <span class="gradient-text">AI</span></span>
    </a>

    <!-- Desktop nav -->
    <nav class="hidden lg:flex items-center gap-7 text-sm font-medium text-base-content/60">
      <a href="#how"     class="hover:text-primary transition-colors">كيف تعمل؟</a>
      <a href="#careers" class="hover:text-primary transition-colors">المسارات المهنية</a>
      <a href="#why"     class="hover:text-primary transition-colors">لماذا بصيرة؟</a>
    </nav>

    <!-- Auth actions -->
    <div class="flex items-center gap-3">
      <a href="index.php/login"    class="btn btn-ghost btn-sm hidden sm:inline-flex">تسجيل الدخول</a>
      <a href="index.php/register" class="btn btn-primary btn-sm shadow-sm">ابدأ مجاناً</a>
    </div>

  </div>
</header>
