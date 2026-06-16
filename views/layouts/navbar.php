<?php
declare(strict_types=1);

use App\Core\Session;
use App\Core\Csrf;

$userName = Session::get('user_name', '');
$initials = $userName !== '' ? mb_strtoupper(mb_substr($userName, 0, 1, 'UTF-8'), 'UTF-8') : '?';

// Active link detection
$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$base    = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($base !== '/' && str_starts_with($rawPath, $base)) {
    $rawPath = substr($rawPath, strlen($base));
}
if (str_starts_with($rawPath, '/index.php')) {
    $rawPath = substr($rawPath, 10);
}
$currentPath = $rawPath === '' ? '/' : $rawPath;

$isActive = fn(string $path): bool => $currentPath === $path
    || ($path !== '/' && str_starts_with($currentPath, $path));
?>

<header class="sticky top-0 z-50 bg-base-100 border-b border-base-300 shadow-sm">
  <div class="navbar max-w-6xl mx-auto px-4 lg:px-6">

    <!-- Brand -->
    <div class="navbar-start">
      <a href="index.php/" class="flex items-center gap-2 group">
        <div class="w-8 h-8 brand-gradient rounded-lg flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
          <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
          </svg>
        </div>
        <span class="font-bold text-xl text-base-content group-hover:text-primary transition-colors">بصيرة AI</span>
      </a>
    </div>

    <!-- Desktop nav links (center) -->
    <div class="navbar-center hidden lg:flex">
      <nav class="flex items-center gap-6">
        <a href="index.php/"
           class="text-sm nav-link py-1 <?= $isActive('/') && !$isActive('/results') ? 'nav-link-active text-primary' : 'text-base-content/70 hover:text-primary transition-colors' ?>">
          الاختبار
        </a>
        <a href="index.php/results"
           class="text-sm nav-link py-1 <?= $isActive('/results') ? 'nav-link-active text-primary' : 'text-base-content/70 hover:text-primary transition-colors' ?>">
          النتائج
        </a>
      </nav>
    </div>

    <!-- User menu (end) -->
    <div class="navbar-end gap-2">

      <!-- Avatar dropdown (desktop) -->
      <?php if ($userName !== ''): ?>
      <div class="dropdown dropdown-end hidden lg:block">
        <div tabindex="0" role="button"
             class="btn btn-ghost btn-circle avatar placeholder">
          <div class="brand-gradient text-white rounded-full w-9 flex items-center justify-center">
            <span class="text-sm font-bold"><?= htmlspecialchars($initials) ?></span>
          </div>
        </div>
        <ul tabindex="0"
            class="dropdown-content menu bg-base-100 rounded-box shadow-lg z-50 w-52 p-2 mt-1 border border-base-200">
          <li class="px-3 py-2">
            <span class="text-xs text-base-content/50 font-medium block">مرحباً،</span>
            <span class="text-sm font-semibold truncate"><?= htmlspecialchars($userName) ?></span>
          </li>
          <div class="divider my-1"></div>
          <li>
            <a href="index.php/" class="<?= $isActive('/') && !$isActive('/results') ? 'active' : '' ?>">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              الاختبار
            </a>
          </li>
          <li>
            <a href="index.php/results" class="<?= $isActive('/results') ? 'active' : '' ?>">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
              النتائج
            </a>
          </li>
          <div class="divider my-1"></div>
          <li>
            <form method="POST" action="index.php/logout">
              <?= Csrf::field() ?>
              <button type="submit" class="text-error w-full flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                تسجيل الخروج
              </button>
            </form>
          </li>
        </ul>
      </div>
      <?php endif; ?>

      <!-- Mobile hamburger -->
      <div class="dropdown dropdown-end lg:hidden">
        <button tabindex="0" class="btn btn-ghost btn-circle">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <ul tabindex="0"
            class="dropdown-content menu bg-base-100 rounded-box shadow-lg z-50 w-52 p-2 mt-1 border border-base-200">
          <?php if ($userName !== ''): ?>
          <li class="px-3 py-2">
            <span class="text-xs text-base-content/50">مرحباً، <?= htmlspecialchars($userName) ?></span>
          </li>
          <div class="divider my-1"></div>
          <?php endif; ?>
          <li><a href="index.php/">الاختبار</a></li>
          <li><a href="index.php/results">النتائج</a></li>
          <div class="divider my-1"></div>
          <li>
            <form method="POST" action="index.php/logout">
              <?= Csrf::field() ?>
              <button type="submit" class="text-error w-full text-start">تسجيل الخروج</button>
            </form>
          </li>
        </ul>
      </div>

    </div>
  </div>
</header>
