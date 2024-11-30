<?php
// app/Notifications/TicketAssignedNotification.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification
{
    private $ticketTitle;

    public function __construct($ticketTitle)
    {
        $this->ticketTitle = $ticketTitle;
    }

    public function via($notifiable)
    {
        return ['database']; // Only send the notification via database
    }


    public function toArray($notifiable)
    {
        return [
            'message' => "You have been assigned a new task: {$this->ticketTitle}",
            'ticket_title' => $this->ticketTitle,
        ];
    }
}
