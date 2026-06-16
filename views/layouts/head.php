<?php
$pageTitle ??= 'بصيرة AI';
$bodyClass ??= 'bg-base-200 min-h-screen';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="basira">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle) ?> — بصيرة AI</title>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* ── Basira custom theme ───────────────────────────────────────── */
    [data-theme="basira"] {
      color-scheme: light;
      --color-primary:          oklch(55.78% 0.2268 264.05); /* indigo-600  */
      --color-primary-content:  oklch(100%   0      0);
      --color-secondary:        oklch(56.93% 0.1508 172.89); /* teal-600    */
      --color-secondary-content:oklch(100%   0      0);
      --color-accent:           oklch(62.97% 0.2236 292.72); /* violet-500  */
      --color-accent-content:   oklch(100%   0      0);
      --color-neutral:          oklch(32.14% 0.0166 232.66);
      --color-neutral-content:  oklch(98%    0      0);
      --color-base-100:         oklch(100%   0      0);
      --color-base-200:         oklch(96.72% 0.0027 247.84);
      --color-base-300:         oklch(92.95% 0.0067 247.89);
      --color-base-content:     oklch(26.08% 0.0157 247.84);
      --color-info:             oklch(71.17% 0.1286 207.08);
      --color-info-content:     oklch(98%    0      0);
      --color-success:          oklch(67.15% 0.1706 150.65);
      --color-success-content:  oklch(98%    0      0);
      --color-warning:          oklch(76.87% 0.1867 69.66);
      --color-warning-content:  oklch(26%    0.01   69.66);
      --color-error:            oklch(64.39% 0.2107 25.33);
      --color-error-content:    oklch(98%    0      0);
      --radius-box:   0.75rem;
      --radius-field: 0.5rem;
      --radius-badge: 99rem;
      --depth: 1;
      --noise: 0;
    }

    /* ── Utilities ─────────────────────────────────────────────────── */
    .brand-gradient { background: linear-gradient(135deg, oklch(55.78% 0.2268 264.05), oklch(56.93% 0.1508 172.89)); }

    @keyframes fadeSlideIn {
      from { opacity: 0; transform: translateY(-6px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .toast-anim { animation: fadeSlideIn .2s ease-out; }

    @keyframes growWidth { from { width: 0 } }
    .bar-anim { animation: growWidth .9s cubic-bezier(.4,0,.2,1) both; }

    .option-row { transition: all .15s ease; }
    .option-row.selected {
      border-color: oklch(55.78% 0.2268 264.05);
      background: oklch(55.78% 0.2268 264.05 / 0.07);
    }

    /* active nav link */
    .nav-link-active {
      color: oklch(55.78% 0.2268 264.05);
      font-weight: 600;
      position: relative;
    }
    .nav-link-active::after {
      content: '';
      position: absolute;
      bottom: -2px; left: 0; right: 0;
      height: 2px;
      background: oklch(55.78% 0.2268 264.05);
      border-radius: 99px;
    }
  </style>
</head>
<body class="<?= htmlspecialchars($bodyClass) ?>">
