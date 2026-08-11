<?php

namespace App\Mail;

use App\Models\ContactSource;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactSubmission extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly array $submission,
        public readonly ContactSource $source,
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->source->email_subject ?: 'New contact form submission from '.$this->submission['website_origin'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-submission',
            with: ['submission' => $this->submission, 'source' => $this->source],
        );
    }
}
