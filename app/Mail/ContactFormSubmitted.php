<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array{name:string,email:string,subject:string,message:string} $contactData
     */
    public function __construct(public array $contactData)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address($this->contactData['email'], $this->contactData['name']),
            ],
            subject: 'FindNest Contact: ' . $this->contactData['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form-submitted',
            with: [
                'contact' => $this->contactData,
            ],
        );
    }
}
