<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => false, // disable cache for dev
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
