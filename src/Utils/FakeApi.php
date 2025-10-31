<?php
namespace Src\Utils;

class FakeApi
{
    /**
     * Register a new user.
     */
    public static function register(string $email, string $password, string $fullName): array
    {
        $users = Storage::getUsers();

        // Check if email already exists
        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                return [
                    'success' => false,
                    'message' => 'Email already registered. Please login instead.'
                ];
            }
        }

        // Create new user
        $newUser = [
            'id' => uniqid('user_', true),
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'fullName' => $fullName,
            'createdAt' => date('Y-m-d H:i:s')
        ];

        $users[] = $newUser;
        Storage::saveUsers($users);

        return [
            'success' => true,
            'message' => 'Registration successful.',
            'user' => $newUser
        ];
    }

    /**
     * Authenticate a user.
     */
    public static function login(string $email, string $password): array
    {
        $users = Storage::getUsers();

        foreach ($users as $user) {
            if (strtolower($user['email']) === strtolower($email)) {
                if (password_verify($password, $user['password'])) {
                    return [
                        'success' => true,
                        'message' => 'Login successful.',
                        'user' => $user
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Incorrect password.'
                    ];
                }
            }
        }

        return [
            'success' => false,
            'message' => 'No account found for this email.'
        ];
    }

    /**
     * Retrieve all tickets for a given user.
     */
    public static function getTickets(string $userId): array
    {
        $tickets = Storage::getTickets();

        // Ensure we return only user-related tickets
        return array_values(array_filter($tickets, function ($t) use ($userId) {
            return isset($t['userId']) && $t['userId'] === $userId;
        }));
    }

    /**
     * Create a new support ticket.
     */
    public static function createTicket(string $userId, string $subject, string $description): array
    {
        $tickets = Storage::getTickets();

        $newTicket = [
            'id' => uniqid('ticket_', true),
            'userId' => $userId,
            'subject' => $subject,
            'description' => $description,
            'status' => 'open',
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];

        $tickets[] = $newTicket;
        Storage::saveTickets($tickets);

        return [
            'success' => true,
            'message' => 'Ticket created successfully.',
            'ticket' => $newTicket
        ];
    }
}
