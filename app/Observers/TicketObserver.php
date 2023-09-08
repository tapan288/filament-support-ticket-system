<?php

namespace App\Observers;

use App\Models\Ticket;
use Filament\Notifications\Notification;
use Filament\Notifications\Events\DatabaseNotificationsSent;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $assignedTo = $ticket->assignedTo;

        Notification::make()
            ->title('A new ticket has been assigned to you')
            ->sendToDatabase($assignedTo);

        event(new DatabaseNotificationsSent($assignedTo));
    }
}
