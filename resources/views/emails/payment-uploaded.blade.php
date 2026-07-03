<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran Diterima</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f6; color: #1f2937; }
        .wrapper { max-width: 600px; margin: 30px auto; }
        .header { background: linear-gradient(135deg, #2563eb, #7c3aed); padding: 40px 32px; border-radius: 20px 20px 0 0; text-align: center; }
        .header h1 { color: #fff; font-size: 26px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.8); margin-top: 6px; font-size: 14px; }
        .badge { display: inline-block; background: rgba(255,255,255,0.15); color: #fff; padding: 6px 16px; border-radius: 100px; font-size: 13px; font-weight: 700; margin-top: 12px; border: 1px solid rgba(255,255,255,0.3); }
        .body { background: #fff; padding: 32px; }
        .greeting { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .text { color: #4b5563; line-height: 1.7; font-size: 14px; margin-bottom: 16px; }
        .card { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 16px; padding: 24px; margin: 24px 0; }
        .card-title { font-size: 13px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #dbeafe; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 13px; color: #6b7280; font-weight: 500; }
        .detail-value { font-size: 14px; color: #111827; font-weight: 700; text-align: right; max-width: 60%; }
        .status-box { text-align: center; background: #fef9c3; border: 2px solid #fde047; border-radius: 16px; padding: 20px; margin: 20px 0; }
        .status-box .icon { font-size: 32px; margin-bottom: 8px; }
        .status-box p { font-size: 14px; color: #713f12; line-height: 1.6; }
        .status-box strong { display: block; font-size: 16px; color: #92400e; margin-bottom: 4px; }
        .footer { background: #1f2937; padding: 28px 32px; border-radius: 0 0 20px 20px; text-align: center; }
        .footer p { color: #9ca3af; font-size: 12px; line-height: 1.8; }
        .footer .brand { color: #fff; font-weight: 800; font-size: 16px; margin-bottom: 8px; }
        .footer a { color: #34d399; text-decoration: none; }
        .kode { font-size: 20px; font-weight: 900; color: #2563eb; text-align: center; background: #eff6ff; border: 2px dashed #93c5fd; border-radius: 12px; padding: 12px; margin: 16px 0; letter-spacing: 2px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div style="font-size: 40px; margin-bottom: 12px;">📄</div>
        <h1>Bukti Pembayaran Diterima!</h1>
        <p>Pembayaran Anda sedang dalam proses verifikasi admin</p>
        <div class="badge">Menunggu Verifikasi</div>
    </div>

    <div class="body">
        <p class="greeting">Halo, {{ $pesanan->user->name ?? 'Wisatawan' }}! 👋</p>
        <p class="text">Kami telah menerima bukti pembayaran yang Anda unggah untuk pemesanan berikut. Mohon tunggu sementara tim kami memverifikasi pembayaran Anda.</p>

        <div class="kode">#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</div>

        <div class="card">
            <div class="card-title">💳 Informasi Pembayaran</div>
            <div class="detail-row">
                <span class="detail-label">Paket Wisata</span>
                <span class="detail-value">
                    <span style="display: block;">{{ $pesanan->paketWisata->nama_paket ?? '-' }}</span>
                    @if(isset($pesanan->paketWisata->rutes) && $pesanan->paketWisata->rutes->count() > 0)
                        <span style="display: block; font-size: 11px; color: #059669; margin-top: 4px; font-weight: 500;">
                            📍 Destinasi: {{ $pesanan->paketWisata->rutes->pluck('nama_rute')->implode(', ') }}
                        </span>
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tipe Trip</span>
                <span class="detail-value">{{ $pesanan->tipe_trip ?? 'Private' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Peserta & Armada</span>
                <span class="detail-value">{{ $pesanan->jumlah_pengunjung }} Orang ({{ $pesanan->jumlah_jeep }} Jeep)</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Tour</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('l, d F Y') }}</span>
            </div>
            @if($pesanan->pembayaran)
            <div class="detail-row">
                <span class="detail-label">Jenis Pembayaran</span>
                <span class="detail-value">{{ $pesanan->pembayaran->jenis_pembayaran }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Metode</span>
                <span class="detail-value">{{ $pesanan->pembayaran->metode_pembayaran }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Jumlah Dibayar</span>
                <span class="detail-value" style="color: #2563eb; font-size: 15px;">Rp {{ number_format($pesanan->pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
            @endif

            @if(isset($pesanan->armadas) && $pesanan->armadas->count() > 0)
            <div class="detail-row" style="flex-direction: column; align-items: flex-start; padding-top: 16px;">
                <span class="detail-label" style="margin-bottom: 10px;">Armada Ditugaskan</span>
                <div style="width: 100%;">
                    @foreach($pesanan->armadas as $index => $armada)
                    <div style="background: rgba(255,255,255,0.7); border: 1px dashed #93c5fd; padding: 10px 12px; border-radius: 8px; margin-bottom: 8px; text-align: left;">
                        <span style="font-size: 12px; font-weight: 800; color: #2563eb; display: block; margin-bottom: 4px;">JEEP {{ $index + 1 }}</span>
                        <span style="font-size: 13px; color: #111827;">🚘 {{ $armada->jeep->nama ?? $armada->jeep->merk ?? 'TBA' }} &nbsp;|&nbsp; 👨‍✈️ Supir: {{ $armada->supir->nama ?? 'TBA' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="status-box">
            <div class="icon">⏳</div>
            <strong>Sedang Diverifikasi</strong>
            <p>Tim admin kami akan memverifikasi pembayaran Anda dalam waktu maksimal <strong>1x24 jam</strong>. Anda akan menerima email konfirmasi setelah proses verifikasi selesai.</p>
        </div>

        <p class="text">Jika ada kendala atau pertanyaan terkait pembayaran, jangan ragu untuk menghubungi kami melalui kontak yang tersedia.</p>
    </div>

    <div class="footer">
        <div class="brand">🚙 JEEP DIENG</div>
        <p>Wisata Alam Dieng — Pengalaman Tak Terlupakan</p>
        <p style="margin-top: 8px;">Email ini dikirim otomatis. Jangan membalas email ini.</p>
        <p style="margin-top: 8px;">Hubungi kami di <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>
        <p style="margin-top: 12px; color: #6b7280; font-size: 11px;">© {{ date('Y') }} Jeep Dieng. Semua hak dilindungi.</p>
    </div>
</div>
</body>
</html>