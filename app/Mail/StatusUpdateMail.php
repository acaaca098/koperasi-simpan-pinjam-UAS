<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $judul,
        public string $pesan,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->judul);
    }

    public function content(): Content
    {
        // Buat view resources/views/emails/status-update.blade.php
        // yang menampilkan $judul dan $pesan.
        return new Content(view: 'emails.status-update');
    }
}
