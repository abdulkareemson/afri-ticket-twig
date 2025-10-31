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
        session_start();
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

        // Fetch tickets from the fake API (simulates DB)
        $tickets = FakeApi::getTickets();

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
