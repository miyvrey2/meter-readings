<?php

namespace App\Notifications;

use App\Models\Connection;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeekConsumptionChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Connection $connectionModel
    ) { }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('There has been a drastic change in your weekly consumption for EAN code: ' . $this->connectionModel->ean_code . '.')
            ->action('Check your usage now', url('/'))
            ->line('Thank you for using our application!');
    }
}
