<?php
require __DIR__ . '/../src/bootstrap.php';

use Src\Controllers\LandingController;
use Src\Controllers\AuthController;
use Src\Controllers\DashboardController;
use Src\Controllers\TicketController;

// Parse URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route map
$routes = [
    '/' => [LandingController::class, 'index'],
    '/login' => [AuthController::class, 'login'],
    '/login/submit' => [AuthController::class, 'loginSubmit'],
    '/signup' => [AuthController::class, 'signup'],
    '/signup/submit' => [AuthController::class, 'signupSubmit'],
    '/logout' => [AuthController::class, 'logout'],
    '/dashboard' => [DashboardController::class, 'index'],
    '/tickets' => [TicketController::class, 'index'],
    '/tickets/create' => [TicketController::class, 'create'],
    '/tickets/store' => [TicketController::class, 'store'],
    '/tickets/edit' => [TicketController::class, 'edit'],
    '/tickets/update' => [TicketController::class, 'update'],
    '/tickets/delete' => [TicketController::class, 'delete'],
    '/tickets/show' => [TicketController::class, 'show'],
];

// Auth guards
$protectedRoutes = [
    '/dashboard',
    '/tickets',
    '/tickets/create',
    '/tickets/store',
    '/tickets/edit',
    '/tickets/update',
    '/tickets/delete',
    '/tickets/show',
];

$guestRoutes = [
    '/login',
    '/login/submit',
    '/signup',
    '/signup/submit',
];

if (isset($routes[$uri])) {
    [$controllerClass, $method] = $routes[$uri];
    $controller = new $controllerClass($twig);

    // Protect routes
    if (in_array($uri, $protectedRoutes) && !isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }

    if (in_array($uri, $guestRoutes) && isset($_SESSION['user'])) {
        header('Location: /dashboard');
        exit;
    }

    $params = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
    $controller->$method($params);
} else {
    header("HTTP/1.0 404 Not Found");
    echo $twig->render('pages/404.html.twig');
}
