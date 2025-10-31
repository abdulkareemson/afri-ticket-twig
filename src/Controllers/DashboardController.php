<?php
namespace Src\Controllers;

use Src\Utils\FakeApi;
use Twig\Environment;

class DashboardController
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
     * Render the dashboard with user info and ticket stats.
     */
    public function index(): void
    {
        // Ensure user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];

        // ✅ Pass user ID to get only their tickets
        $tickets = FakeApi::getTickets($user['id']);

        // Calculate ticket stats
        $totalTickets = count($tickets);
        $openTickets = count(array_filter($tickets, fn($t) => $t['status'] === 'open'));
        $inProgressTickets = count(array_filter($tickets, fn($t) => $t['status'] === 'in_progress'));
        $resolvedTickets = count(array_filter($tickets, fn($t) => $t['status'] === 'closed'));

        // Render dashboard view
        echo $this->twig->render('pages/dashboard.html.twig', [
            'user' => $user,
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'inProgressTickets' => $inProgressTickets,
            'resolvedTickets' => $resolvedTickets,
        ]);
    }
}
