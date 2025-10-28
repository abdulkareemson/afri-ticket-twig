<?php
namespace Src\Utils;

class Constants
{
    // ---- App Name ----
    public const APP_NAME = "AfriTicket";

    // ---- Ticket Statuses ----
    public const TICKET_STATUSES = [
        'OPEN' => 'open',
        'IN_PROGRESS' => 'in_progress',
        'CLOSED' => 'closed',
    ];

    // ---- Ticket Priorities ----
    public const TICKET_PRIORITIES = [
        'LOW' => 'low',
        'MEDIUM' => 'medium',
        'HIGH' => 'high',
    ];

    // ---- Status Color Mapping (Tailwind style) ----
    public const STATUS_COLORS = [
        'open' => 'bg-green-500 text-white',
        'in_progress' => 'bg-yellow-500 text-black',
        'closed' => 'bg-gray-500 text-white',
    ];

    // ---- Priority Color Mapping (Tailwind style) ----
    public const PRIORITY_COLORS = [
        'low' => 'bg-blue-500 text-white',
        'medium' => 'bg-orange-500 text-white',
        'high' => 'bg-red-600 text-white',
    ];

    // ---- Storage Keys ----
    public const STORAGE_KEYS = [
        'USERS' => 'ticketapp_users',
        'SESSION' => 'ticketapp_session',
        'TICKETS' => 'ticketapp_tickets',
    ];
}
