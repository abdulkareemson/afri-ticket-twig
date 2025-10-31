<?php

namespace Src\Controllers;

use Src\Utils\Storage;
use Twig\Environment;

class AuthController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;

        // ✅ Only start session if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Show login page
     */
    public function login(): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: /dashboard');
            exit;
        }

        echo $this->twig->render('pages/login.html.twig', [
            'error' => $_SESSION['error'] ?? null,
        ]);

        unset($_SESSION['error']);
    }

    /**
     * Handle login form
     */
    public function loginSubmit(array $data): void
    {
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$email || !$password) {
            $_SESSION['error'] = 'Email and password are required.';
            header('Location: /login');
            exit;
        }

        $users = Storage::get('users');
        $foundUser = null;

        foreach ($users as $u) {
            if ($u['email'] === $email && password_verify($password, $u['password'])) {
                $foundUser = $u;
                break;
            }
        }

        if ($foundUser) {
            $_SESSION['user'] = $foundUser;
            header('Location: /dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid email or password.';
            header('Location: /login');
            exit;
        }
    }

    /**
     * Show signup page
     */
    public function signup(): void
    {
        if (isset($_SESSION['user'])) {
            header('Location: /dashboard');
            exit;
        }

        echo $this->twig->render('pages/signup.html.twig', [
            'error' => $_SESSION['error'] ?? null,
        ]);

        unset($_SESSION['error']);
    }

    /**
     * Handle signup form
     */
    public function signupSubmit(array $data): void
    {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$name || !$email || !$password) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: /signup');
            exit;
        }

        $users = Storage::get('users');

        foreach ($users as $u) {
            if ($u['email'] === $email) {
                $_SESSION['error'] = 'Email already exists.';
                header('Location: /signup');
                exit;
            }
        }

        $newUser = [
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'createdAt' => date('c'),
        ];

        $users[] = $newUser;
        Storage::set('users', $users);

        $_SESSION['user'] = $newUser;
        header('Location: /dashboard');
        exit;
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
