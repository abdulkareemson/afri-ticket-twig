<?php
namespace Src\Controllers;

use Src\Utils\Storage;
use Twig\Environment;

class DashboardController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        session_start();
    }

    /**
     * Render the dashboard page with ticket statistics.
     */
    public function index(): void
    {
        // Protect route: only logged-in users
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];

        // Load tickets from storage
        $tickets = Storage::getTickets();

        // Calculate stats
        $totalTickets = count($tickets);
        $openTickets = count(array_filter($tickets, fn($t) => $t['status'] === 'open'));
        $inProgressTickets = count(array_filter($tickets, fn($t) => $t['status'] === 'in_progress'));
        $resolvedTickets = count(array_filter($tickets, fn($t) => $t['status'] === 'closed'));

        // Render Twig template
        echo $this->twig->render('pages/dashboard.html.twig', [
            'user' => $user,
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'inProgressTickets' => $inProgressTickets,
            'resolvedTickets' => $resolvedTickets,
        ]);
    }
}
