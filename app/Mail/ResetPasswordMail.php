<?php

namespace App\Mail;

use App\Models\User;
use App\Models\WebsiteSetting; 
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ResetPasswordMail extends Mailable
{
    use SerializesModels;
    /**
     * Create a new message instance.
     */
    public function __construct(public String $url,public WebsiteSetting $site_settings)
    {  
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {  
        return new Envelope(
            from: new Address($this->site_settings->email,$this->site_settings->site_name),
            subject: 'Reset Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {     
        return new Content(
            view: 'emails.reset_password',
            with: [ 
                'site_settings' => $this->site_settings,
                'url' => $this->url
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
