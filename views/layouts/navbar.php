<?php

declare(strict_types=1);

use App\Core\Session;

$userName    = Session::get('user_name', '');
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
?>
<div class="navbar bg-base-100 shadow-lg px-6">
  <div class="flex-1">
    <a href="index.php/" class="text-2xl font-bold text-primary">بصيرة AI</a>
  </div>
  <div class="flex-none gap-3 items-center">
    <a href="index.php/" class="btn btn-ghost btn-sm">اختبار جديد</a>
    <a href="index.php/results" class="btn btn-ghost btn-sm">النتائج</a>
    <?php if ($userName !== ''): ?>
      <span class="text-sm opacity-70 hidden sm:inline"><?= htmlspecialchars($userName) ?></span>
    <?php endif; ?>
    <form method="POST" action="index.php/logout" class="inline">
      <?= \App\Core\Csrf::field() ?>
      <button type="submit" class="btn btn-ghost btn-sm text-error">خروج</button>
    </form>
  </div>
</div>
