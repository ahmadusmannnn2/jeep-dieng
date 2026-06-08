<?php

namespace App\Mail;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EtiketLunasMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pesanan $pesanan) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎫 E-Tiket Perjalanan Anda - Jeep Dieng #BKG-' . str_pad($this->pesanan->id, 5, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.etiket-lunas',
        );
    }
}
