<?php

namespace App\Mail;

use App\Models\User;
use App\Models\WebsiteSetting; 
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class VerifyUserMail extends Mailable
{
    use SerializesModels;
    /**
     * Create a new message instance.
     */
    public function __construct(public User $user,public WebsiteSetting $site_settings)
    {  
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {  
        return new Envelope(
            from: new Address($this->site_settings->email,$this->site_settings->site_name),
            subject: 'Email Verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {     
        return new Content(
            view: 'emails.email_verification',
            with: [ 
                'site_settings' => $this->site_settings,
                'user' => $this->user
            ]
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
