<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignmentGradedNotification extends Notification
{
    use Queueable;

    public $submission;

    /**
     * Create a new message instance.
     */
    public function __construct($submission)
    {
        $this->submission = $submission;
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
        $course = $this->submission->assignment->lesson->module->course;
        return (new MailMessage)
                    ->subject('Your Assignment has been Graded!')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Your performance in the assignment "' . $this->submission->assignment->lesson->title . '" for ' . $course->title . ' has been graded by the instructor.')
                    ->line('Score Awarded: ' . $this->submission->marks_awarded . ' / ' . $this->submission->assignment->total_marks)
                    ->action('View Feedback', url('/learner/dashboard'))
                    ->line('Thank you for being part of EduBridge!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'assignment_graded',
            'assignment_title' => $this->submission->assignment->lesson->title,
            'course_title' => $this->submission->assignment->lesson->module->course->title,
            'score' => $this->submission->marks_awarded,
            'total' => $this->submission->assignment->total_marks,
            'course_id' => $this->submission->assignment->lesson->module->course->id,
            'message' => 'Your assignment for ' . $this->submission->assignment->lesson->module->course->title . ' has been graded.'
        ];
    }
}
