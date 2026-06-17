<?php
declare(strict_types=1);
$pageTitle = 'اختبار الميول المهنية';
$bodyClass = 'bg-base-200 min-h-screen';
include __DIR__ . '/../layouts/head.php';
?>

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<main class="max-w-2xl mx-auto px-4 py-10">

  <!-- Header card -->
  <div class="card bg-base-100 shadow-lg border border-base-200 mb-6">
    <div class="card-body py-5 px-6">
      <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
          <h1 class="text-xl font-bold">اختبار الميول المهنية</h1>
          <p class="text-sm text-base-content/50 mt-0.5">أجب بصدق — لا توجد إجابات صح أو خطأ</p>
        </div>
        <!-- Question counter badge -->
        <div class="flex items-center gap-2">
          <span class="badge badge-primary badge-lg font-bold" id="q-counter">—</span>
          <span class="badge badge-ghost" id="category-chip"></span>
        </div>
      </div>
      <!-- Progress bar -->
      <div class="mt-4">
        <progress class="progress progress-primary w-full h-2" value="0" max="100" id="progress-bar"></progress>
        <div class="flex justify-between text-xs text-base-content/40 mt-1">
          <span id="progress-label">جارٍ التحميل...</span>
          <span id="progress-pct">0%</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Question card -->
  <div class="card bg-base-100 shadow-lg border border-base-200">
    <div class="card-body p-6 lg:p-8">

      <!-- Question text -->
      <h2 class="text-lg font-semibold leading-relaxed mb-6" id="question-text">
        <span class="loading loading-dots loading-sm opacity-40"></span>
      </h2>

      <!-- Options -->
      <div class="space-y-3" id="options-container">
        <!-- Skeleton placeholders while loading -->
        <div class="h-14 rounded-xl bg-base-200 animate-pulse"></div>
        <div class="h-14 rounded-xl bg-base-200 animate-pulse"></div>
        <div class="h-14 rounded-xl bg-base-200 animate-pulse"></div>
        <div class="h-14 rounded-xl bg-base-200 animate-pulse"></div>
      </div>

      <!-- Toast / feedback -->
      <div id="toast-success" class="alert alert-success mt-6 hidden toast-anim">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span>تم حفظ الإجابة</span>
      </div>
      <div id="toast-error" class="alert alert-error mt-6 hidden toast-anim">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span id="toast-error-text">خطأ</span>
      </div>

      <!-- Next button -->
      <div class="mt-8">
        <button id="btn-next"
                class="btn btn-primary w-full"
                disabled>
          <span id="btn-label">التالي</span>
          <svg id="btn-icon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14"/>
          </svg>
          <span id="btn-spinner" class="loading loading-spinner loading-sm hidden"></span>
        </button>
      </div>

    </div>
  </div>

</main>

<script>
(function () {
  'use strict';

  let assessmentId      = null;
  let currentQuestionId = null;
  let selectedOptionId  = null;

  const $ = id => document.getElementById(id);

  const els = {
    bar:       $('progress-bar'),
    label:     $('progress-label'),
    pct:       $('progress-pct'),
    counter:   $('q-counter'),
    category:  $('category-chip'),
    qText:     $('question-text'),
    options:   $('options-container'),
    nextBtn:   $('btn-next'),
    btnLabel:  $('btn-label'),
    btnIcon:   $('btn-icon'),
    btnSpin:   $('btn-spinner'),
    toastOk:   $('toast-success'),
    toastErr:  $('toast-error'),
    toastMsg:  $('toast-error-text'),
  };

  /* ── helpers ── */

  function setProgress(answered, total) {
    const pct = total > 0 ? Math.round((answered / total) * 100) : 0;
    els.bar.value   = pct;
    els.pct.textContent   = `${pct}%`;
    els.label.textContent = `${answered} من ${total} سؤال مُجاب`;
    els.counter.textContent = `السؤال ${answered + 1} من ${total}`;
  }

  function showToast(type, msg) {
    // hide both first
    els.toastOk.classList.add('hidden');
    els.toastErr.classList.add('hidden');

    if (type === 'ok') {
      els.toastOk.classList.remove('hidden');
      els.toastOk.classList.add('toast-anim');
      setTimeout(() => els.toastOk.classList.add('hidden'), 1500);
    } else {
      els.toastMsg.textContent = msg || 'خطأ غير متوقع';
      els.toastErr.classList.remove('hidden');
      els.toastErr.classList.add('toast-anim');
    }
  }

  function setBusy(busy) {
    els.nextBtn.disabled  = busy || !selectedOptionId;
    els.btnLabel.classList.toggle('hidden', busy);
    els.btnIcon.classList.toggle('hidden', busy);
    els.btnSpin.classList.toggle('hidden', !busy);
  }

  function renderOptions(options) {
    selectedOptionId        = null;
    els.options.innerHTML   = '';
    els.nextBtn.disabled    = true;

    for (const opt of options) {
      const row = document.createElement('label');
      row.className = 'option-row flex items-center gap-4 p-4 border-2 border-base-300 rounded-xl cursor-pointer hover:border-primary/40 hover:bg-base-200 transition';

      const radio = document.createElement('input');
      radio.type      = 'radio';
      radio.name      = 'answer';
      radio.className = 'radio radio-primary shrink-0';
      radio.value     = String(opt.id);

      radio.addEventListener('change', () => {
        // deselect all
        document.querySelectorAll('.option-row').forEach(r => r.classList.remove('selected'));
        row.classList.add('selected');
        selectedOptionId      = opt.id;
        els.nextBtn.disabled  = false;
      });

      const span = document.createElement('span');
      span.className   = 'text-sm leading-snug';
      span.textContent = opt.text;

      row.append(radio, span);
      els.options.appendChild(row);
    }
  }

  /* ── API ── */

  async function apiFetch(url, opts = {}) {
    const res = await fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      ...opts,
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  }

  async function startAssessment() {
    const data = await apiFetch(APP_BASE + '/api/assessment/start', { method: 'POST' });
    assessmentId = data.assessment_id;
    setProgress(data.answered_count, data.total_questions);
    return data;
  }

  async function fetchNextQuestion() {
    return apiFetch(`${APP_BASE}/api/assessment/question/next?assessment_id=${assessmentId}`);
  }

  async function saveAnswer() {
    return apiFetch(APP_BASE + '/api/assessment/save', {
      method: 'POST',
      body: JSON.stringify({
        assessment_id: assessmentId,
        question_id:   currentQuestionId,
        option_id:     selectedOptionId,
      }),
    });
  }

  function showQuestion(q, total, answered) {
    currentQuestionId         = q.id;
    els.qText.textContent     = q.text;
    els.category.textContent  = q.category_name ?? '';
    renderOptions(q.options);
    setProgress(answered, total);
  }

  /* ── init ── */

  async function init() {
    try {
      await startAssessment();
      const data = await fetchNextQuestion();

      if (!data.question) {
        window.location.href = APP_BASE + '/results';
        return;
      }
      showQuestion(data.question, data.total_questions, data.answered_count);
    } catch (e) {
      els.qText.textContent = 'فشل تحميل الاختبار. تأكد من الاتصال وأعد المحاولة.';
      els.options.innerHTML = '';
      console.error(e);
    }
  }

  /* ── next button ── */

  els.nextBtn.addEventListener('click', async () => {
    setBusy(true);
    try {
      const saved = await saveAnswer();
      showToast('ok');
      setProgress(saved.answered_count, saved.total_questions);

      const data = await fetchNextQuestion();

      if (!data.question) {
        // finalize
        await apiFetch(APP_BASE + '/api/assessment/finalize', {
          method: 'POST',
          body: JSON.stringify({ assessment_id: assessmentId }),
        });
        window.location.href = APP_BASE + '/results';
        return;
      }

      showQuestion(data.question, data.total_questions, data.answered_count);
    } catch (e) {
      showToast('err', e?.message ?? 'خطأ غير متوقع');
    } finally {
      setBusy(false);
    }
  });

  init();
}());
</script>

<?php include __DIR__ . '/../layouts/foot.php'; ?>
