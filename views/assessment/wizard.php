<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="corporate">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>اختبار مهني - بصيرة AI</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">

  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main class="max-w-3xl mx-auto px-4 py-10">
    <div class="card bg-base-100 shadow-xl border-t-4 border-primary">
      <div class="card-body">

        <div class="flex items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold">اختبار الميول المهنية</h1>
            <p class="text-sm opacity-70">أجب عن الأسئلة وسيتم حفظ إجاباتك تلقائياً.</p>
          </div>
          <div class="badge badge-primary" id="progress-badge">0%</div>
        </div>

        <progress class="progress progress-primary w-full mt-4" value="0" max="100" id="progress"></progress>
        <p class="text-xs opacity-60 text-left mt-1" id="progress-label">جارٍ التحميل...</p>

        <div class="mt-8">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-xl font-semibold" id="question-title">جارٍ تحميل السؤال...</h2>
            <div class="badge badge-ghost" id="category-chip"></div>
          </div>

          <div class="grid gap-3" id="options"><!-- options injected --></div>

          <div class="flex gap-3 mt-8">
            <button class="btn btn-primary w-full" id="btn-next" disabled>التالي</button>
          </div>

          <div class="alert alert-success mt-6 hidden" id="save-success">
            <span>تم حفظ إجابتك.</span>
          </div>
          <div class="alert alert-error mt-6 hidden" id="save-error">
            <span id="save-error-text">خطأ</span>
          </div>
        </div>

      </div>
    </div>
  </main>

<script>
(function () {
  'use strict';

  let assessmentId   = null;
  let currentQuestionId = null;
  let selectedOptionId  = null;

  const els = {
    progress:      document.getElementById('progress'),
    badge:         document.getElementById('progress-badge'),
    label:         document.getElementById('progress-label'),
    title:         document.getElementById('question-title'),
    options:       document.getElementById('options'),
    category:      document.getElementById('category-chip'),
    nextBtn:       document.getElementById('btn-next'),
    saveSuccess:   document.getElementById('save-success'),
    saveError:     document.getElementById('save-error'),
    saveErrorText: document.getElementById('save-error-text'),
  };

  function setProgress(answered, total) {
    const pct = total > 0 ? Math.round((answered / total) * 100) : 0;
    els.progress.value    = pct;
    els.badge.innerText   = `${pct}%`;
    els.label.innerText   = `${answered} من ${total} سؤال`;
  }

  function renderOptions(options) {
    selectedOptionId      = null;
    els.options.innerHTML = '';
    els.nextBtn.disabled  = true;

    for (const opt of options) {
      const label = document.createElement('label');
      label.className = 'flex items-center p-4 border-2 border-base-300 rounded-xl cursor-pointer hover:bg-base-200 transition';

      const input = document.createElement('input');
      input.type      = 'radio';
      input.name      = 'option';
      input.className = 'radio radio-primary ml-3';
      input.value     = String(opt.id);
      input.addEventListener('change', () => {
        selectedOptionId    = opt.id;
        els.nextBtn.disabled = false;
      });

      const span = document.createElement('span');
      span.className  = 'text-base';
      span.innerText  = opt.text;

      label.append(input, span);
      els.options.appendChild(label);
    }
  }

  async function apiFetch(url, options = {}) {
    const res = await fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      ...options,
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  }

  async function startAssessment() {
    const data = await apiFetch('index.php/api/assessment/start', { method: 'POST' });
    assessmentId = data.assessment_id;
    setProgress(data.answered_count, data.total_questions);
    return data;
  }

  async function fetchNextQuestion() {
    return apiFetch(`index.php/api/assessment/question/next?assessment_id=${assessmentId}`);
  }

  async function saveAnswer() {
    if (!selectedOptionId) throw new Error('اختر خياراً أولاً');
    const data = await apiFetch('index.php/api/assessment/save', {
      method: 'POST',
      body: JSON.stringify({
        assessment_id: assessmentId,
        question_id:   currentQuestionId,
        option_id:     selectedOptionId,
      }),
    });
    setProgress(data.answered_count, data.total_questions);
  }

  function showQuestion(q, total, answered) {
    currentQuestionId = q.id;
    els.title.innerText    = q.text;
    els.category.innerText = q.category_name ?? '';
    renderOptions(q.options);
    setProgress(answered, total);
  }

  async function init() {
    els.title.innerText    = 'جارٍ التحميل...';
    els.nextBtn.disabled   = true;

    const startData = await startAssessment();
    const data      = await fetchNextQuestion();

    if (!data.question) {
      els.title.innerText   = 'أكملت جميع الأسئلة. انتقل إلى النتائج.';
      els.options.innerHTML = '';
      els.nextBtn.disabled  = true;
      window.location.href  = 'index.php/results';
      return;
    }

    showQuestion(data.question, data.total_questions, data.answered_count);
  }

  els.nextBtn.addEventListener('click', async () => {
    els.saveSuccess.classList.add('hidden');
    els.saveError.classList.add('hidden');
    els.nextBtn.disabled = true;

    try {
      await saveAnswer();
      els.saveSuccess.classList.remove('hidden');

      const data = await fetchNextQuestion();

      if (!data.question) {
        // All answered — finalize then redirect
        await apiFetch('index.php/api/assessment/finalize', {
          method: 'POST',
          body: JSON.stringify({ assessment_id: assessmentId }),
        });
        window.location.href = 'index.php/results';
        return;
      }

      showQuestion(data.question, data.total_questions, data.answered_count);
    } catch (e) {
      els.saveError.classList.remove('hidden');
      els.saveErrorText.innerText = e?.message ?? 'خطأ غير متوقع';
      els.nextBtn.disabled = false;
    }
  });

  init().catch(err => {
    els.title.innerText = 'فشل تحميل الاختبار. تأكد من الاتصال وأعد المحاولة.';
    console.error(err);
  });
}());
</script>
</body>
</html>
