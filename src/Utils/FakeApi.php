<?php
namespace Src\Utils;

use Src\Utils\Storage;
use Src\Utils\Constants;
use Src\Utils\Validations;

class FakeApi
{
    private static function simulateDelay(int $ms = 600): void
    {
        usleep($ms * 1000);
    }

    // ----------------------------
    // USERS
    // ----------------------------
    private static function loadUsers(): array
    {
        return Storage::load(Constants::STORAGE_KEYS['USERS']) ?? [];
    }

    private static function saveUsers(array $users): void
    {
        Storage::save(Constants::STORAGE_KEYS['USERS'], $users);
    }

    public static function signup(array $data): array
    {
        self::simulateDelay();

        $errors = Validations::validateSignup($data);
        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }

        $name = trim($data['name']);
        $email = trim($data['email']);
        $password = trim($data['password']);

        $users = self::loadUsers();
        foreach ($users as $u) {
            if (strtolower($u['email']) === strtolower($email)) {
                throw new \Exception("Email is already registered.");
            }
        }

        $newUser = [
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'createdAt' => date('c'),
        ];

        $users[] = $newUser;
        self::saveUsers($users);

        return [
            'id' => $newUser['id'],
            'name' => $newUser['name'],
            'email' => $newUser['email'],
        ];
    }

    public static function login(array $data): array
    {
        self::simulateDelay();

        $errors = Validations::validateLogin($data);
        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }

        $users = self::loadUsers();
        foreach ($users as $u) {
            if (strtolower($u['email']) === strtolower($data['email']) && $u['password'] === $data['password']) {
                return [
                    'id' => $u['id'],
                    'name' => $u['name'],
                    'email' => $u['email'],
                ];
            }
        }

        throw new \Exception("Invalid email or password.");
    }

    // ----------------------------
    // TICKETS
    // ----------------------------
    private static function loadTickets(): array
    {
        return Storage::load(Constants::STORAGE_KEYS['TICKETS']) ?? [];
    }

    private static function saveTickets(array $tickets): void
    {
        Storage::save(Constants::STORAGE_KEYS['TICKETS'], $tickets);
    }

    public static function getTickets(): array
    {
        self::simulateDelay();
        return self::loadTickets();
    }

    public static function createTicket(array $data): array
    {
        self::simulateDelay();

        $errors = Validations::validateTicket($data);
        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }

        $status = $data['status'] ?? Constants::TICKET_STATUSES['OPEN'];
        if (!in_array($status, Constants::TICKET_STATUSES)) {
            throw new \Exception("Invalid ticket status.");
        }

        $newTicket = [
            'id' => uniqid(),
            'title' => trim($data['title']),
            'description' => trim($data['description'] ?? ''),
            'priority' => $data['priority'] ?? 'medium',
            'status' => $status,
            'createdAt' => date('c'),
            'updatedAt' => date('c'),
        ];

        $tickets = self::loadTickets();
        $tickets[] = $newTicket;
        self::saveTickets($tickets);

        return $newTicket;
    }

    public static function updateTicket(string $id, array $updates): array
    {
        self::simulateDelay();
        $tickets = self::loadTickets();
        $index = array_search($id, array_column($tickets, 'id'));

        if ($index === false) {
            throw new \Exception("Ticket not found.");
        }

        if (isset($updates['status']) && !in_array($updates['status'], Constants::TICKET_STATUSES)) {
            throw new \Exception("Invalid ticket status.");
        }

        $tickets[$index] = array_merge($tickets[$index], $updates, [
            'updatedAt' => date('c'),
        ]);

        self::saveTickets($tickets);
        return $tickets[$index];
    }

    public static function deleteTicket(string $id): bool
    {
        self::simulateDelay();
        $tickets = self::loadTickets();
        $filtered = array_filter($tickets, fn($t) => $t['id'] !== $id);

        if (count($filtered) === count($tickets)) {
            throw new \Exception("Ticket not found.");
        }

        self::saveTickets(array_values($filtered));
        return true;
    }
}
