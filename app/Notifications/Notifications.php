<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
class Notifications extends Notification
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($action_performer,$action_to,$action_title,$description,$read_status)
    {
        $this->action_performer = $action_performer;
        $this->action_to = $action_to;
        $this->action_title = $action_title;
        $this->description = $description;
        $this->read_status = $read_status;
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
        $url = url('/api/find/'.$this->action_performer.$this->action_to.$this->action_title.$this->description.$this->read_status);
        return (new MailMessage)
            ->line('You are receiving this email because we received a .')
            ->action('', url($url))
            ->line('');
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
