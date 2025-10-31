<?php
namespace Src\Controllers;

use Src\Utils\FakeApi;
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
     * Handle login submission
     */
    public function loginSubmit(array $data): void
    {
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$email || !$password) {
            echo $this->twig->render('pages/login.html.twig', [
                'error' => 'Email and password are required.',
            ]);
            return;
        }

        $response = FakeApi::login($email, $password);

        if ($response['success']) {
            $_SESSION['user'] = $response['user'];
            header('Location: /dashboard');
            exit;
        } else {
            echo $this->twig->render('pages/login.html.twig', [
                'error' => $response['error'] ?? 'Login failed.',
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
     * Handle signup submission
     */
    public function signupSubmit(array $data): void
    {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$name || !$email || !$password) {
            echo $this->twig->render('pages/signup.html.twig', [
                'error' => 'All fields are required.',
            ]);
            return;
        }

        $response = FakeApi::signup($name, $email, $password);

        if ($response['success']) {
            $_SESSION['user'] = $response['user'];
            header('Location: /dashboard');
            exit;
        } else {
            echo $this->twig->render('pages/signup.html.twig', [
                'error' => $response['error'] ?? 'Signup failed.',
            ]);
        }
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
