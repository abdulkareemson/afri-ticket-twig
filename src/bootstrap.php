<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Set default timezone and error reporting
date_default_timezone_set('Africa/Lagos');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => false, // Disable cache for development
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

return $twig;
