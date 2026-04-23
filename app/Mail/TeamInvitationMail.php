<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $invitationToken,
        public ?User $invitedBy = null
    ) {}

    public function envelope(): Envelope
    {
        $inviterName = $this->invitedBy?->name ?? 'Le Nium Team';

        return new Envelope(
            subject: "You're invited to join {$inviterName}'s team",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
