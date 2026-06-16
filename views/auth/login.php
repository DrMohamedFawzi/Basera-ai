<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="corporate">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>تسجيل الدخول - بصيرة AI</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center">
  <div class="card bg-base-100 shadow-xl w-full max-w-md">
    <div class="card-body">
      <h1 class="text-2xl font-bold text-center mb-2">بصيرة AI</h1>
      <p class="text-center opacity-70 text-sm mb-6">سجّل دخولك لبدء رحلتك المهنية</p>

      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error mb-4">
          <span><?= htmlspecialchars((string)$_GET['error']) ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" action="index.php/login" class="space-y-4">
        <?= \App\Core\Csrf::field() ?>

        <div class="form-control">
          <label class="label"><span class="label-text">البريد الإلكتروني</span></label>
          <input type="email" name="email" class="input input-bordered w-full"
                 placeholder="example@email.com" required autocomplete="email" />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text">كلمة المرور</span></label>
          <input type="password" name="password" class="input input-bordered w-full"
                 placeholder="••••••••" required autocomplete="current-password" />
        </div>

        <button type="submit" class="btn btn-primary w-full mt-2">دخول</button>
      </form>

      <div class="divider">أو</div>
      <p class="text-center text-sm">
        ليس لديك حساب؟
        <a href="index.php/register" class="link link-primary">إنشاء حساب جديد</a>
      </p>
    </div>
  </div>
</body>
</html>
