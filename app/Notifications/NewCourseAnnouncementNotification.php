<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCourseAnnouncementNotification extends Notification
{
    use Queueable;

    public $announcement;

    /**
     * Create a new message instance.
     */
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Announcement: ' . $this->announcement->title)
                    ->greeting('Hello Scholar ' . $notifiable->name . '!')
                    ->line('Your instructor for the course "' . $this->announcement->course->title . '" has posted a new announcement.')
                    ->line('**' . $this->announcement->title . '**')
                    ->line($this->announcement->content)
                    ->action('View Course', route('learner.course.viewer', ['course' => $this->announcement->course_id]))
                    ->line('Stay curious, stay learning!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'announcement',
            'course_title' => $this->announcement->course->title,
            'title' => $this->announcement->title,
            'instructor' => $this->announcement->instructor->name,
            'course_id' => $this->announcement->course_id,
            'announcement_id' => $this->announcement->id,
            'message' => 'New announcement posted in ' . $this->announcement->course->title,
            'description' => $this->announcement->content,
            'link' => route('learner.course.announcement.show', ['course' => $this->announcement->course_id, 'announcement' => $this->announcement->id]),
            'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'
        ];
    }
}
