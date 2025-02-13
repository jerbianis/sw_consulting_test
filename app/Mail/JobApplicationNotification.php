<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $jobPost;
    public $cvPath;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $jobPost, $cvPath)
    {
        $this->user = $user;
        $this->jobPost = $jobPost;
        $this->cvPath = $cvPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Job Application Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-application-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->cvPath)
                ->as('cv_'.$this->user->name.'_'. $this->user->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
