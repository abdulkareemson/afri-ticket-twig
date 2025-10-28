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
        session_start();
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
            'error' => null,
        ]);
    }

    /**
     * Process login form
     */
    public function loginSubmit(array $data): void
    {
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        // Validate required fields
        if (!$email || !$password) {
            echo $this->twig->render('pages/login.html.twig', [
                'error' => 'Email and password are required.',
            ]);
            return;
        }

        $users = Storage::getUsers();
        $user = null;

        foreach ($users as $u) {
            if ($u['email'] === $email && $u['password'] === $password) {
                $user = $u;
                break;
            }
        }

        if ($user) {
            $_SESSION['user'] = $user;
            Storage::setSession(['user' => $user]);
            header('Location: /dashboard');
            exit;
        } else {
            echo $this->twig->render('pages/login.html.twig', [
                'error' => 'Invalid email or password.',
            ]);
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
            'error' => null,
        ]);
    }

    /**
     * Process signup form
     */
    public function signupSubmit(array $data): void
    {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        // Validate required fields
        if (!$name || !$email || !$password) {
            echo $this->twig->render('pages/signup.html.twig', [
                'error' => 'All fields are required.',
            ]);
            return;
        }

        // Check for duplicate email
        $users = Storage::getUsers();
        foreach ($users as $u) {
            if ($u['email'] === $email) {
                echo $this->twig->render('pages/signup.html.twig', [
                    'error' => 'Email already exists.',
                ]);
                return;
            }
        }

        $newUser = [
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'createdAt' => date('c'),
        ];

        Storage::saveUser($newUser);
        $_SESSION['user'] = $newUser;
        Storage::setSession(['user' => $newUser]);

        header('Location: /dashboard');
        exit;
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        session_destroy();
        Storage::clearSession();
        header('Location: /login');
        exit;
    }
}
