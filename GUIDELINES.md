# Basira AI — Code Guidelines

We value **organised, readable, and purposeful code**. These guidelines exist so that anyone opening a file knows exactly where to look and why things are written the way they are.

---

## 1. General philosophy

- **One thing per file.** A file does one job. If a view grows past ~150 lines, split it into partials.
- **No magic.** Every piece of code should be understandable without running it in your head for more than a few seconds.
- **No dead code.** If it is not used, delete it. Do not comment things out "just in case".
- **No premature abstraction.** Three similar lines are better than a helper that only exists to save two lines.

---

## 2. File structure

```
Basira-ai/
├── app/
│   ├── Controllers/       # HTTP layer — validates input, calls orchestrator, redirects
│   ├── Core/              # Framework primitives (Router, Session, Csrf, Env, Database)
│   ├── Orchestration/     # Coordinates services for a single use-case
│   ├── Repositories/      # All DB reads/writes; one class per aggregate
│   └── Services/          # Domain logic — pure, stateless where possible
├── database/
│   ├── schema.sql         # Full schema in FK order (run once)
│   └── seed.sql           # Seed data for dev/staging
├── public/
│   ├── assets/
│   │   ├── css/           # Page-specific CSS (e.g. landing.css)
│   │   └── js/            # Page-specific JS  (e.g. landing.js)
│   ├── index.php          # Single entry point — router only, no business logic
│   └── results.php        # Results page bootstrapper
├── tests/
│   └── Unit/              # PHPUnit tests — mirror app/ structure
└── views/
    ├── home/              # Landing page partials
    │   ├── landing.php    # Orchestrator — includes partials only
    │   ├── _navbar.php    # Prefix _ means "partial, not a standalone page"
    │   ├── _hero.php
    │   └── ...
    ├── auth/              # login.php, register.php
    ├── assessment/        # wizard.php
    ├── results/           # dna.php, matches.php, roadmap.php
    └── layouts/           # head.php, foot.php, navbar.php (app navbar)
```

### Naming conventions

| Type                        | Convention                              | Example                        |
|-----------------------------|-----------------------------------------|--------------------------------|
| PHP classes                 | `PascalCase`                            | `CareerMatchingEngine`         |
| PHP methods / functions     | `camelCase`                             | `generateMatches()`            |
| PHP variables               | `$camelCase`                            | `$assessmentId`                |
| View partials               | `_snake_case.php` (leading underscore)  | `_hero.php`, `_stats.php`      |
| Standalone views / pages    | `snake_case.php`                        | `landing.php`, `wizard.php`    |
| CSS classes                 | `kebab-case`                            | `.card-hover`, `.gradient-text`|
| JS functions / variables    | `camelCase`                             | `animateCounter()`             |
| Database tables             | `snake_case`, plural                    | `career_matches`, `user_assessments` |
| Database columns            | `snake_case`                            | `answered_count`, `created_at` |

---

## 3. PHP

### Basics
- Every file: `declare(strict_types=1);`
- Every class: explicit namespace (`namespace App\Controllers;`)
- No `global` variables except in view partials where a `$data` array is passed in.
- Dependency injection — pass objects through constructors, not `new X()` inside methods.

### Controllers
- Controllers only: validate input → call orchestrator → redirect or include a view.
- No SQL, no business logic in controllers.

### Views
- Views are PHP templates — HTML with minimal PHP (`foreach`, `if`, `htmlspecialchars`).
- All user-facing strings go through `htmlspecialchars()` before output.
- No SQL in views. Data is prepared in the controller/orchestrator and passed as variables.
- Use `$variable ??= 'default'` at the top of a partial to declare expected variables.

### Comments
Write a comment only when the **why** is non-obvious. Never describe what the code does — the code does that.

```php
// Good — explains a constraint that is not obvious from the code
// career_twins uses UPSERT; avoid INSERT to prevent duplicate-key errors on retry.
$stmt = $db->prepare('INSERT INTO career_twins ... ON DUPLICATE KEY UPDATE ...');

// Bad — restates the code
// Get the user by ID
$user = $repository->getUserById($userId);
```

---

## 4. CSS

- **Page-specific styles** go in `public/assets/css/<page>.css`, not in `<style>` tags.
- **Component-level utilities** (`.brand-gradient`, `.bar-anim`) live in `views/layouts/head.php` because every page shares them.
- Use DaisyUI tokens (`text-primary`, `bg-base-200`) where possible; raw `oklch()` only for hero dark-section overrides that cannot use theme tokens.
- No `!important`. If you need it, the specificity is wrong.
- Keep selectors flat. Avoid nesting more than two levels.

### Class order in HTML (for consistency)
1. Layout (`flex`, `grid`, `hidden`, `w-*`, `h-*`)
2. Spacing (`p-*`, `m-*`, `gap-*`)
3. Typography (`text-*`, `font-*`, `leading-*`)
4. Colours / borders (`bg-*`, `border-*`, `text-*`)
5. Effects / animation (`shadow-*`, `transition`, `animate-*`)
6. Responsive modifiers (`sm:`, `lg:`) — keep them together with the class they modify

---

## 5. JavaScript

- All JS is vanilla — no frameworks, no build step.
- Page-specific JS lives in `public/assets/js/<page>.js`.
- Wrap everything in an IIFE: `(function () { 'use strict'; ... }());`
- Use `const` by default; `let` when reassignment is needed; never `var`.
- No inline `onclick=""` or `onsubmit=""` in HTML. Attach listeners in JS.
- Add `defer` on all `<script>` tags.

---

## 6. HTML / Views

- Language: Arabic RTL — always `<html lang="ar" dir="rtl">` (set in `head.php`).
- Accessibility: every interactive element has a visible label or `aria-label`. Decorative SVGs get `aria-hidden="true"`.
- No emojis in UI — use SVG icons from Heroicons (outline, `stroke-width="1.8"`).
- No inline `style=""` except for **dynamic values** that must be computed in PHP (e.g. a percentage width or an oklch colour from a data array). Static styles always go in CSS.

---

## 7. Database

- All queries go through `CareerRepository` (or its interface). No raw PDO in controllers.
- Always use prepared statements with named placeholders (`:name`, not `?`).
- Schema changes → add a migration comment block at the top of `schema.sql`.
- Never `SELECT *` — name every column you need.

---

## 8. Security checklist

Before every commit that touches user input or DB:

- [ ] All output through `htmlspecialchars()`
- [ ] All writes use PDO prepared statements
- [ ] State-changing routes verify CSRF token (`Csrf::verify()`)
- [ ] No secrets in committed files (use `.env`, which is in `.gitignore`)

---

## 9. Git

- Commit messages: `type: short imperative description` (e.g. `feat:`, `fix:`, `refactor:`, `docs:`)
- One logical change per commit — do not bundle unrelated fixes.
- Never commit `.env`, `vendor/`, or `*.result.cache` files.
- Pull before starting new work; keep `main` green.
