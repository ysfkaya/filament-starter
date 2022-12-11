<?php

namespace App\Notifications;

use App\Filament\Resources\Management\FormResource;
use App\Models\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Ysfkaya\FilamentNotification\Actions\ButtonAction;
use Ysfkaya\FilamentNotification\Notifications\NotificationLevel;

class ContactFormNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Form $form)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
            ->subject('New Contact Form - '. config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new contact form has been submitted.')
            ->line('Subject: ' . $this->form->subject) // @phpstan-ignore-line
            ->line('Name: ' . $this->form->name) // @phpstan-ignore-line
            ->line('E-mail: ' . $this->form->email) // @phpstan-ignore-line
            ->line('Phone: ' . $this->form->phone) // @phpstan-ignore-line
            ->line('Message: ' . $this->form->message) // @phpstan-ignore-line
            ->action('Show Form', FormResource::getUrl(params: ['tableFilters' => ['id' => ['isActive' => $this->form->id]]]));
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
            'level' => NotificationLevel::INFO,
            'title' => 'New Contact Form',
            'message' => 'A new contact form has been submitted.',
            'form_id' => $this->form->id,
        ];
    }

    public static function notificationFeedActions()
    {
        return [
            ButtonAction::make('view_contact_form')
                ->label('View')
                ->action(function ($record) {
                    $record->delete();

                    $url = FormResource::getUrl(params: ['tableFilters' => ['id' => ['isActive' => data_get($record, 'data.form_id')]]]);

                    return redirect($url);
                }),
        ];
    }
}
