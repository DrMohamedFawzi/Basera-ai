<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="corporate">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>اختبار مهني - بصيرة AI</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen">
  <main class="max-w-3xl mx-auto px-4 py-10">

    <div class="card bg-base-100 shadow-xl border-t-4 border-primary">
      <div class="card-body">
        <div class="flex items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold">اختبار الميول المهنية</h1>
            <p class="text-sm opacity-70">أجب عن الأسئلة وسيتم حفظ إجاباتك تلقائياً ثم توليد النتائج.</p>
          </div>
          <div class="badge badge-primary" id="progress-badge">0%</div>
        </div>

        <progress class="progress progress-primary w-full mt-4" value="0" max="100" id="progress"></progress>

        <div class="mt-8">
          <div class="flex items-center justify-between mb-3">
            <h2 class="text-xl font-semibold" id="question-title">جارٍ تحميل السؤال...</h2>
            <div class="chip chip-primary">
              <span class="opacity-80">فئة</span>
              <span class="font-bold" id="category-chip">1</span>
            </div>
          </div>

          <div class="grid gap-3" id="options">
            <!-- options injected -->
          </div>

          <div class="flex gap-3 mt-8">
            <button class="btn btn-ghost w-full" id="btn-prev" disabled>السابق</button>
            <button class="btn btn-primary w-full" id="btn-next">التالي</button>
          </div>

          <div class="alert alert-success mt-6 hidden" id="save-success">
            <span>تم حفظ إجابتك.</span>
          </div>
          <div class="alert alert-error mt-6 hidden" id="save-error">
            <span id="save-error-text">خطأ</span>
          </div>

          <div class="mt-6">
            <div class="divider"></div>
            <p class="text-xs opacity-70 text-center">
              ملاحظة: هذه نسخة تجريبية. استخدم query string مثل:
              <span class="font-mono">?assessment_id=1&user_id=1</span>
            </p>
          </div>
        </div>
      </div>
    </div>

  </main>

<script>
  const params = new URLSearchParams(window.location.search);
  const assessmentId = Number(params.get('assessment_id') || 1);
  const userId = Number(params.get('user_id') || 1);

  // للتجربة: الفئة الأولى
  const categoryId = 1;
  document.getElementById('category-chip').innerText = String(categoryId);

  let selectedOptionId = null;
  window.currentQuestionId = null;

  const els = {
    progress: document.getElementById('progress'),
    badge: document.getElementById('progress-badge'),
    title: document.getElementById('question-title'),
    options: document.getElementById('options'),
    nextBtn: document.getElementById('btn-next'),
    prevBtn: document.getElementById('btn-prev'),
    saveSuccess: document.getElementById('save-success'),
    saveError: document.getElementById('save-error'),
    saveErrorText: document.getElementById('save-error-text'),
  };

  function setProgress(p) {
    const v = Math.max(0, Math.min(100, Number(p) || 0));
    els.progress.value = v;
    els.badge.innerText = `${v}%`;
  }

  function renderOptions(question) {
    selectedOptionId = null;
    els.options.innerHTML = '';

    for (const opt of question.options) {
      const label = document.createElement('label');
      label.className = 'flex items-center p-4 border-2 border-base-300 rounded-xl cursor-pointer hover:bg-base-200 transition';

      const input = document.createElement('input');
      input.type = 'radio';
      input.name = 'option';
      input.className = 'radio radio-primary ml-3';
      input.value = String(opt.id);
      input.addEventListener('change', () => {
        selectedOptionId = opt.id;
      });

      const span = document.createElement('span');
      span.className = 'text-base';
      span.innerText = opt.text;

      label.appendChild(input);
      label.appendChild(span);
      els.options.appendChild(label);
    }
  }

  async function fetchNextQuestion() {
    const res = await fetch(`index.php/api/assessment/question/next?category_id=${categoryId}`);
    return res.json();
  }

  async function saveAnswer() {
    if (!selectedOptionId) {
      throw new Error('اختر خياراً أولاً');
    }

    const payload = {
      assessment_id: assessmentId,
      question_id: window.currentQuestionId,
      option_id: selectedOptionId
    };

    const res = await fetch('index.php/api/assessment/save', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    const data = await res.json().catch(() => null);
    if (!data || data.status !== 'saved') {
      throw new Error('فشل حفظ الإجابة');
    }

    els.saveSuccess.classList.remove('hidden');
  }

  async function start() {
    els.saveSuccess.classList.add('hidden');
    els.saveError.classList.add('hidden');
    setProgress(0);

    const data = await fetchNextQuestion();
    if (!data.question) {
      els.title.innerText = 'لا توجد أسئلة لهذه الفئة.';
      return;
    }

    window.currentQuestionId = data.question.id;
    els.title.innerText = data.question.text;
    renderOptions(data.question);
  }

  els.nextBtn.addEventListener('click', async () => {
    try {
      els.saveError.classList.add('hidden');
      els.saveSuccess.classList.add('hidden');

      await saveAnswer();

      // تحديث progress (تجريبي)
      const current = Number(els.progress.value || 0);
      setProgress(Math.min(100, current + 10));

      const data = await fetchNextQuestion();

      if (!data.question) {
        // إنهاء الرحلة
        await fetch('index.php/api/assessment/finalize', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ assessment_id: assessmentId, user_id: userId })
        });

        window.location.href = `index.php/results?user_id=${encodeURIComponent(userId)}`;
        return;
      }

      window.currentQuestionId = data.question.id;
      els.title.innerText = data.question.text;
      renderOptions(data.question);
    } catch (e) {
      els.saveError.classList.remove('hidden');
      els.saveErrorText.innerText = e?.message || 'خطأ';
    }
  });

  // initial
  start();
</script>
</body>
</html>

