<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Database;
use App\Core\Session;

final class AuthController
{
    public function showLogin(): void
    {
        include __DIR__ . '/../../views/auth/login.php';
    }

    public function showRegister(): void
    {
        include __DIR__ . '/../../views/auth/register.php';
    }

    public function login(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $this->redirectWithError('login', 'طلب غير صالح. أعد المحاولة.');
            return;
        }

        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->redirectWithError('login', 'البريد الإلكتروني وكلمة المرور مطلوبان.');
            return;
        }

        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT id, name, password FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, (string)$user['password'])) {
            $this->redirectWithError('login', 'البريد الإلكتروني أو كلمة المرور غير صحيحة.');
            return;
        }

        Session::set('user_id',   (int)$user['id']);
        Session::set('user_name', (string)$user['name']);

        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '/');
        exit;
    }

    public function register(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $this->redirectWithError('register', 'طلب غير صالح. أعد المحاولة.');
            return;
        }

        $name     = trim((string)($_POST['name'] ?? ''));
        $email    = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            $this->redirectWithError('register', 'جميع الحقول مطلوبة.');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithError('register', 'صيغة البريد الإلكتروني غير صحيحة.');
            return;
        }

        if (strlen($password) < 8) {
            $this->redirectWithError('register', 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.');
            return;
        }

        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $this->redirectWithError('register', 'البريد الإلكتروني مسجّل مسبقًا.');
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare(
            'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)'
        );
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hash]);

        $userId = (int)$db->lastInsertId();

        Session::set('user_id',   $userId);
        Session::set('user_name', $name);

        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '/');
        exit;
    }

    public function logout(): void
    {
        Session::destroy();
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '/login');
        exit;
    }

    private function redirectWithError(string $page, string $message): void
    {
        $encoded = urlencode($message);
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . "/{$page}?error={$encoded}");
        exit;
    }
}
