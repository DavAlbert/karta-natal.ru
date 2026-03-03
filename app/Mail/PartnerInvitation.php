<?php

namespace App\Mail;

use App\Models\PartnerCompatibility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public PartnerCompatibility $compatibility;
    public string $verifyUrl;
    public string $initiatorName;
    public string $locale;

    public function __construct(PartnerCompatibility $compatibility, string $locale = 'en')
    {
        $this->compatibility = $compatibility;
        $this->locale = $locale;
        $this->verifyUrl = route('compatibility.verify', $compatibility->verification_token);
        $this->initiatorName = $compatibility->user->name ?? trans('emails.partner_invite_default_name', [], $this->locale);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('emails.partner_invite_subject', ['name' => $this->initiatorName], $this->locale),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-invitation',
            with: ['locale' => $this->locale],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
