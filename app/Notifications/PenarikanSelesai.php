<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PenarikanSelesai extends Notification
{
    use Queueable;

    protected $penarikan;

    public function __construct($penarikan)
    {
        $this->penarikan = $penarikan;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'penarikan_selesai',
            'penarikan_id' => $this->penarikan->id,
            'amount' => $this->penarikan->total_diterima,
            'message' => 'Pencairan dana sebesar Rp ' . number_format($this->penarikan->total_diterima, 0, ',', '.') . ' telah selesai ditransfer ke rekening Anda.',
            'url' => route('admin.penarikan-saldo.index')
        ];
    }
}
