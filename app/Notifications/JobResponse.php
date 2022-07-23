<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobResponse extends Notification implements ShouldQueue
{
    use Queueable;

    private array $data_to_send;
    private int $send_delay;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data_to_send,$send_delay = 0)
    {
        $this->data_to_send = $data_to_send;
        $this->send_delay = $send_delay;
    }

    public function withDelay($notifiable)
    {
        return [
            'mail' => now()->addMinutes($this->send_delay),
        ];
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Job Response')
            ->view('./mails/job_response',$this->data_to_send);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
