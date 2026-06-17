<?php declare(strict_types=1); ?>

<footer class="bg-base-content/95 text-base-100/70 py-12">
  <div class="max-w-6xl mx-auto px-4 lg:px-6">

    <!-- Top row: brand + link columns -->
    <div class="flex flex-col lg:flex-row justify-between gap-10 mb-10">

      <!-- Brand blurb -->
      <div class="max-w-xs">
        <a href="index.php/" class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 brand-gradient rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
          </div>
          <span class="font-bold text-xl text-base-100">بصيرة AI</span>
        </a>
        <p class="text-sm leading-relaxed">
          منصة ذكاء اصطناعي تساعد الشباب العربي على اكتشاف
          مساراتهم المهنية بثقة ووضوح.
        </p>
      </div>

      <!-- Link columns -->
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-8 text-sm">
        <div>
          <div class="font-semibold text-base-100 mb-3">المنتج</div>
          <ul class="space-y-2">
            <li><a href="#how"     class="hover:text-base-100 transition-colors">كيف تعمل؟</a></li>
            <li><a href="#careers" class="hover:text-base-100 transition-colors">المسارات</a></li>
            <li><a href="#why"     class="hover:text-base-100 transition-colors">لماذا بصيرة؟</a></li>
          </ul>
        </div>
        <div>
          <div class="font-semibold text-base-100 mb-3">الحساب</div>
          <ul class="space-y-2">
            <li><a href="index.php/register" class="hover:text-base-100 transition-colors">إنشاء حساب</a></li>
            <li><a href="index.php/login"    class="hover:text-base-100 transition-colors">تسجيل الدخول</a></li>
          </ul>
        </div>
        <div>
          <div class="font-semibold text-base-100 mb-3">الدعم</div>
          <ul class="space-y-2">
            <li><a href="#" class="hover:text-base-100 transition-colors">اتصل بنا</a></li>
            <li><a href="#" class="hover:text-base-100 transition-colors">سياسة الخصوصية</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Bottom row -->
    <div class="border-t border-base-100/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
      <div>© <?= date('Y') ?> بصيرة AI — جميع الحقوق محفوظة</div>
      <div class="flex items-center gap-1">
        صُنع بـ
        <svg class="w-3.5 h-3.5 text-red-400 mx-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
          <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
        </svg>
        للشباب العربي
      </div>
    </div>

  </div>
</footer>
