<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PenarikanDiajukan extends Notification
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
            'type' => 'penarikan_diajukan',
            'penarikan_id' => $this->penarikan->id,
            'komunitas_id' => $this->penarikan->komunitas_id,
            'komunitas_name' => $this->penarikan->komunitas->nama_komunitas,
            'amount' => $this->penarikan->total_diterima,
            'message' => 'Pengajuan penarikan dana baru sebesar Rp ' . number_format($this->penarikan->total_diterima, 0, ',', '.') . ' dari ' . $this->penarikan->komunitas->nama_komunitas,
            'url' => route('admin.penarikan-saldo.index')
        ];
    }
}
