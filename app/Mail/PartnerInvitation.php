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

    public function __construct(PartnerCompatibility $compatibility)
    {
        $this->compatibility = $compatibility;
        $this->verifyUrl = route('compatibility.verify', $compatibility->verification_token);
        $this->initiatorName = $compatibility->user->name ?? 'Ваш партнёр';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->initiatorName} приглашает вас проверить совместимость — Karta-Natal.ru",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-invitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
