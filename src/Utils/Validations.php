<?php
namespace Src\Utils;

class Validations
{
    /* -------------------------------------
     * 🔐 AUTHENTICATION VALIDATIONS
     * -----------------------------------*/

    public static function validateEmail(string $email): bool
    {
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePassword(string $password): bool
    {
        // Minimum 6 characters, at least 1 number
        return preg_match('/^(?=.*\d).{6,}$/', trim($password)) === 1;
    }

    public static function validateLogin(array $values): array
    {
        $errors = [];

        if (empty($values['email']) || !self::validateEmail($values['email'])) {
            $errors['email'] = "Enter a valid email address";
        }

        if (empty($values['password']) || !self::validatePassword($values['password'])) {
            $errors['password'] = "Password must be at least 6 characters and contain a number";
        }

        return $errors;
    }

    public static function validateSignup(array $values): array
    {
        $errors = [];

        if (empty($values['name']) || strlen(trim($values['name'])) < 2) {
            $errors['name'] = "Name must be at least 2 characters";
        }

        // Merge login validation
        $errors = array_merge($errors, self::validateLogin($values));

        return $errors;
    }

    /* -------------------------------------
     * 🎫 TICKET VALIDATIONS
     * -----------------------------------*/

    public static function validateTicket(array $ticket): array
    {
        $errors = [];

        if (empty($ticket['title']) || strlen(trim($ticket['title'])) < 3) {
            $errors['title'] = "Title must be at least 3 characters";
        }

        if (empty($ticket['description']) || strlen(trim($ticket['description'])) < 10) {
            $errors['description'] = "Description must be at least 10 characters";
        }

        if (empty($ticket['status']) || !in_array($ticket['status'], Constants::TICKET_STATUSES)) {
            $errors['status'] = "Status must be one of: open, in_progress, closed";
        }

        if (empty($ticket['priority']) || !in_array($ticket['priority'], Constants::TICKET_PRIORITIES)) {
            $errors['priority'] = "Priority must be one of: low, medium, high";
        }

        return $errors;
    }
}
