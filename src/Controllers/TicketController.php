<?php
namespace Src\Controllers;

use Src\Utils\Storage;
use Twig\Environment;

class TicketController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        session_start();
    }

    /**
     * Display all tickets
     */
    public function index(): void
    {
        // Protect route
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $tickets = Storage::getTickets();

        echo $this->twig->render('pages/tickets.html.twig', [
            'user' => $user,
            'tickets' => $tickets,
            'show_form_modal' => false,
            'editing_ticket' => null,
            'show_delete_confirm' => false,
        ]);
    }

    /**
     * Show create ticket form
     */
    public function create(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $tickets = Storage::getTickets();

        echo $this->twig->render('pages/tickets.html.twig', [
            'user' => $user,
            'tickets' => $tickets,
            'show_form_modal' => true,
            'editing_ticket' => null,
            'show_delete_confirm' => false,
        ]);
    }

    /**
     * Store new ticket
     */
    public function store(array $data): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Validate required fields
        $title = trim($data['title'] ?? '');
        $status = $data['status'] ?? 'open';

        if (!$title || !in_array($status, ['open', 'in_progress', 'closed'])) {
            $_SESSION['ticket_error'] = 'Title is required and status must be valid.';
            header('Location: /tickets');
            exit;
        }

        $ticket = [
            'id' => uniqid(),
            'title' => $title,
            'description' => $data['description'] ?? '',
            'priority' => $data['priority'] ?? 'medium',
            'status' => $status,
            'createdAt' => date('c'),
            'updatedAt' => date('c'),
        ];

        Storage::saveTicket($ticket);
        $_SESSION['ticket_success'] = 'Ticket created successfully.';
        header('Location: /tickets');
        exit;
    }

    /**
     * Show edit ticket form
     */
    public function edit(string $id): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $ticket = Storage::getTicketById($id);
        $tickets = Storage::getTickets();

        if (!$ticket) {
            $_SESSION['ticket_error'] = 'Ticket not found.';
            header('Location: /tickets');
            exit;
        }

        echo $this->twig->render('pages/tickets.html.twig', [
            'user' => $user,
            'tickets' => $tickets,
            'show_form_modal' => true,
            'editing_ticket' => $ticket,
            'show_delete_confirm' => false,
        ]);
    }

    /**
     * Update ticket
     */
    public function update(string $id, array $data): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $ticket = Storage::getTicketById($id);
        if (!$ticket) {
            $_SESSION['ticket_error'] = 'Ticket not found.';
            header('Location: /tickets');
            exit;
        }

        $updates = [
            'title' => trim($data['title'] ?? $ticket['title']),
            'description' => $data['description'] ?? $ticket['description'],
            'priority' => $data['priority'] ?? $ticket['priority'],
            'status' => $data['status'] ?? $ticket['status'],
            'updatedAt' => date('c'),
        ];

        // Validate required fields
        if (!$updates['title'] || !in_array($updates['status'], ['open', 'in_progress', 'closed'])) {
            $_SESSION['ticket_error'] = 'Title is required and status must be valid.';
            header("Location: /tickets/edit/{$id}");
            exit;
        }

        Storage::updateTicket($id, $updates);
        $_SESSION['ticket_success'] = 'Ticket updated successfully.';
        header('Location: /tickets');
        exit;
    }

    /**
     * Delete ticket
     */
    public function delete(string $id): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $ticket = Storage::getTicketById($id);
        if (!$ticket) {
            $_SESSION['ticket_error'] = 'Ticket not found.';
            header('Location: /tickets');
            exit;
        }

        Storage::deleteTicket($id);
        $_SESSION['ticket_success'] = 'Ticket deleted successfully.';
        header('Location: /tickets');
        exit;
    }

    /**
     * Show ticket details
     */
    public function show(string $id): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $user = $_SESSION['user'];
        $ticket = Storage::getTicketById($id);

        if (!$ticket) {
            $_SESSION['ticket_error'] = 'Ticket not found.';
            header('Location: /tickets');
            exit;
        }

        echo $this->twig->render('pages/ticket_details.html.twig', [
            'user' => $user,
            'ticket' => $ticket,
        ]);
    }
}
