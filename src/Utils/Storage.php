<?php
namespace Src\Utils;

use Src\Utils\Constants;

class Storage
{
    private static string $storagePath = __DIR__ . '/../../templates/data';

    /**
     * Ensure the storage directory exists
     */
    private static function ensureStorageDir(): void
    {
        if (!file_exists(self::$storagePath)) {
            mkdir(self::$storagePath, 0777, true);
        }
    }

    /**
     * Get full path for a given file
     */
    private static function getFilePath(string $filename): string
    {
        self::ensureStorageDir();
        return self::$storagePath . '/' . $filename;
    }

    // -----------------------------
    // Generic JSON load/save
    // -----------------------------
    public static function save(string $filename, $data): bool
    {
        try {
            file_put_contents(self::getFilePath($filename), json_encode($data, JSON_PRETTY_PRINT));
            return true;
        } catch (\Throwable $e) {
            error_log("Storage save error: " . $e->getMessage());
            return false;
        }
    }

    public static function load(string $filename)
    {
        try {
            $path = self::getFilePath($filename);
            if (!file_exists($path)) return [];
            return json_decode(file_get_contents($path), true);
        } catch (\Throwable $e) {
            error_log("Storage load error: " . $e->getMessage());
            return [];
        }
    }

    public static function remove(string $filename): bool
    {
        try {
            $path = self::getFilePath($filename);
            if (file_exists($path)) unlink($path);
            return true;
        } catch (\Throwable $e) {
            error_log("Storage remove error: " . $e->getMessage());
            return false;
        }
    }

    // -----------------------------
    // Users
    // -----------------------------
    public static function getUsers(): array
    {
        return self::load('users.json');
    }

    public static function saveUser(array $user): bool
    {
        $users = self::getUsers();
        $users[] = $user;
        return self::save('users.json', $users);
    }

    // -----------------------------
    // Tickets
    // -----------------------------
    public static function getTickets(): array
    {
        return self::load('tickets.json');
    }

    public static function saveTicket(array $ticket): bool
    {
        $tickets = self::getTickets();
        $tickets[] = $ticket;
        return self::save('tickets.json', $tickets);
    }

    public static function getTicketById(string $id): ?array
    {
        $tickets = self::getTickets();
        foreach ($tickets as $ticket) {
            if ($ticket['id'] === $id) return $ticket;
        }
        return null;
    }

    public static function updateTicket(string $id, array $data): bool
    {
        $tickets = self::getTickets();
        foreach ($tickets as &$ticket) {
            if ($ticket['id'] === $id) {
                $ticket = array_merge($ticket, $data);
                $ticket['updatedAt'] = date('c');
                return self::save('tickets.json', $tickets);
            }
        }
        return false;
    }

    public static function deleteTicket(string $id): bool
    {
        $tickets = self::getTickets();
        $tickets = array_filter($tickets, fn($t) => $t['id'] !== $id);
        return self::save('tickets.json', array_values($tickets));
    }

    // -----------------------------
    // Session
    // -----------------------------
    public static function getSession(): array
    {
        return self::load('session.json');
    }

    public static function setSession(array $sessionObj): bool
    {
        return self::save('session.json', $sessionObj);
    }

    public static function clearSession(): bool
    {
        return self::remove('session.json');
    }
}
