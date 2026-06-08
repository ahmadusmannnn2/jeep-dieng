<?php

namespace App\Mail;

use App\Models\Pesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pesanan $pesanan) {}

    public function envelope(): Envelope
    {
        $kodeBooking = '#BKG-' . str_pad($this->pesanan->id, 5, '0', STR_PAD_LEFT);
        $statusLabel = $this->pesanan->status === 'DP Lunas' ? '💰 DP Lunas Dikonfirmasi' : '🎉 Pembayaran Lunas Dikonfirmasi';
        return new Envelope(
            subject: $statusLabel . ' - ' . $kodeBooking,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-confirmed',
        );
    }
}
