<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="corporate">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>إنشاء حساب - بصيرة AI</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-base-200 min-h-screen flex items-center justify-center">
  <div class="card bg-base-100 shadow-xl w-full max-w-md">
    <div class="card-body">
      <h1 class="text-2xl font-bold text-center mb-2">بصيرة AI</h1>
      <p class="text-center opacity-70 text-sm mb-6">أنشئ حسابك وابدأ رحلتك المهنية</p>

      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error mb-4">
          <span><?= htmlspecialchars((string)$_GET['error']) ?></span>
        </div>
      <?php endif; ?>

      <form method="POST" action="index.php/register" class="space-y-4">
        <?= \App\Core\Csrf::field() ?>

        <div class="form-control">
          <label class="label"><span class="label-text">الاسم الكامل</span></label>
          <input type="text" name="name" class="input input-bordered w-full"
                 placeholder="محمد أحمد" required autocomplete="name" />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text">البريد الإلكتروني</span></label>
          <input type="email" name="email" class="input input-bordered w-full"
                 placeholder="example@email.com" required autocomplete="email" />
        </div>

        <div class="form-control">
          <label class="label"><span class="label-text">كلمة المرور</span></label>
          <input type="password" name="password" class="input input-bordered w-full"
                 placeholder="8 أحرف على الأقل" required minlength="8" autocomplete="new-password" />
        </div>

        <button type="submit" class="btn btn-primary w-full mt-2">إنشاء الحساب</button>
      </form>

      <div class="divider">أو</div>
      <p class="text-center text-sm">
        لديك حساب؟
        <a href="index.php/login" class="link link-primary">تسجيل الدخول</a>
      </p>
    </div>
  </div>
</body>
</html>
