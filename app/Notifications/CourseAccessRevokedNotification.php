<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourseAccessRevokedNotification extends Notification
{
    use Queueable;

    public $courseTitle;

    /**
     * Create a new notification instance.
     */
    public function __construct($courseTitle)
    {
        $this->courseTitle = $courseTitle;
    }

    /**
     * Define delivery channels: 'mail' (for email) and 'database' (for dashboard alerts)
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Define the structure of the Email Notification
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Course Access Revoked: ' . $this->courseTitle)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your enrollment in the course "' . $this->courseTitle . '" has been revoked by the instructor.')
            ->line('If you believe this is an error or have questions regarding payment, please contact support or your instructor.')
            ->action('Go to Dashboard', route('learner.dashboard'))
            ->line('Thank you for learning with EduBridge.');
    }

    /**
     * Define the structure of the Database/Dashboard Notification
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'enrollment_revoked',
            'course_title' => $this->courseTitle,
            'message' => 'Your access to the course <strong>' . e($this->courseTitle) . '</strong> has been revoked.',
            'description' => 'Your instructor has removed you from the course roster. Please contact support if you need assistance.',
            'link' => route('learner.dashboard'),
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' // Warning triangle icon
        ];
    }
}