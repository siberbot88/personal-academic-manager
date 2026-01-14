<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BahayaAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[PAM] Status Bahaya: ' . $this->task->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bahaya_alert',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
