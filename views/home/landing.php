<?php
/**
 * Landing page — entry point for unauthenticated visitors.
 *
 * Includes:
 *   CSS  → public/assets/css/landing.css  (landing-specific styles)
 *   JS   → public/assets/js/landing.js    (scroll effects, counters, reveal)
 *
 * Sections (each in its own partial prefixed with _):
 *   _navbar.php   fixed top navbar with login / register
 *   _hero.php     dark hero with floating card mockup
 *   _stats.php    animated stat counters
 *   _why.php      four "problem" cards
 *   _how.php      five numbered steps
 *   _careers.php  six career-category cards
 *   _feature.php  DNA feature highlight + mockup card
 *   _cta.php      dark call-to-action band
 *   _footer.php   site footer
 */
declare(strict_types=1);

$pageTitle = 'اكتشف مستقبلك المهني';
$bodyClass = 'bg-base-100';
$extraHead = '<link rel="stylesheet" href="assets/css/landing.css">';

include __DIR__ . '/../../views/layouts/head.php';
?>

<?php include __DIR__ . '/_navbar.php';  ?>
<?php include __DIR__ . '/_hero.php';    ?>
<?php include __DIR__ . '/_stats.php';   ?>
<?php include __DIR__ . '/_why.php';     ?>
<?php include __DIR__ . '/_how.php';     ?>
<?php include __DIR__ . '/_careers.php'; ?>
<?php include __DIR__ . '/_feature.php'; ?>
<?php include __DIR__ . '/_cta.php';     ?>
<?php include __DIR__ . '/_footer.php';  ?>

<script src="assets/js/landing.js" defer></script>
<?php include __DIR__ . '/../../views/layouts/foot.php'; ?>
