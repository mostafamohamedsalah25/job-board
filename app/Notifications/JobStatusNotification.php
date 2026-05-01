<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobPosting;

class JobStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $job;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobPosting $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusMessage = $this->job->status === 'approved' ? 'has been Approved!' : 'was Rejected.';
        $color = $this->job->status === 'approved' ? 'success' : 'error';

        return (new MailMessage)
            ->subject("Job Posting {$this->job->status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your job posting '{$this->job->title}' {$statusMessage}")
            ->action('View Your Jobs', url('/employer/jobs'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->job->id,
            'title' => $this->job->title,
            'status' => $this->job->status,
            'message' => "Your job posting '{$this->job->title}' is now {$this->job->status}.",
            'link' => '/employer/jobs'
        ];
    }
}
