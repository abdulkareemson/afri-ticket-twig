<?php
require_once __DIR__ . '/Storage.php';

class FakeApi {

  /**
   * Register a new user.
   */
  public static function signup(string $name, string $email, string $password): array {
    $users = Storage::get('users');

    // Check if user already exists
    foreach ($users as $user) {
      if (strtolower($user['email']) === strtolower($email)) {
        return ['success' => false, 'error' => 'Email already exists.'];
      }
    }

    $newUser = [
      'id' => uniqid('user_'),
      'name' => trim($name),
      'email' => strtolower(trim($email)),
      'password' => password_hash($password, PASSWORD_DEFAULT),
      'created_at' => date('Y-m-d H:i:s')
    ];

    Storage::add('users', $newUser);
    return ['success' => true, 'user' => $newUser];
  }

  /**
   * Log in an existing user.
   */
  public static function login(string $email, string $password): array {
    $user = Storage::find('users', ['email' => strtolower(trim($email))]);

    if (!$user) {
      return ['success' => false, 'error' => 'User not found.'];
    }

    if (!password_verify($password, $user['password'])) {
      return ['success' => false, 'error' => 'Invalid password.'];
    }

    // Simulate session/localStorage handling for browser-based persistence
    return ['success' => true, 'user' => $user];
  }

  /**
   * Get all tickets.
   */
  public static function getTickets(): array {
    return Storage::get('tickets');
  }

  /**
   * Create a new ticket.
   */
  public static function createTicket(string $userId, string $title, string $eventDate, string $location): array {
    $tickets = Storage::get('tickets');

    $newTicket = [
      'id' => uniqid('ticket_'),
      'user_id' => $userId,
      'title' => trim($title),
      'event_date' => $eventDate,
      'location' => trim($location),
      'status' => 'active',
      'created_at' => date('Y-m-d H:i:s')
    ];

    Storage::add('tickets', $newTicket);
    return ['success' => true, 'ticket' => $newTicket];
  }

  /**
   * Get tickets for a specific user.
   */
  public static function getUserTickets(string $userId): array {
    $tickets = Storage::get('tickets');
    return array_values(array_filter($tickets, fn($t) => $t['user_id'] === $userId));
  }

  /**
   * Cancel or update ticket status.
   */
  public static function cancelTicket(string $ticketId): array {
    $updated = Storage::update('tickets', ['id' => $ticketId], ['status' => 'cancelled']);
    return $updated
      ? ['success' => true, 'message' => 'Ticket cancelled successfully.']
      : ['success' => false, 'error' => 'Ticket not found.'];
  }
}
