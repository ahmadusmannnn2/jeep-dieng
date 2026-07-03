<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Dikonfirmasi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f6; color: #1f2937; }
        .wrapper { max-width: 600px; margin: 30px auto; }
        .header { background: linear-gradient(135deg, #059669, #16a34a); padding: 40px 32px; border-radius: 20px 20px 0 0; text-align: center; }
        .header h1 { color: #fff; font-size: 26px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.8); margin-top: 6px; font-size: 14px; }
        .badge { display: inline-block; background: rgba(255,255,255,0.2); color: #fff; padding: 6px 16px; border-radius: 100px; font-size: 13px; font-weight: 700; margin-top: 12px; border: 1px solid rgba(255,255,255,0.4); }
        .body { background: #fff; padding: 32px; }
        .greeting { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .text { color: #4b5563; line-height: 1.7; font-size: 14px; margin-bottom: 16px; }
        .card { background: #f0fdf4; border: 1px solid #86efac; border-radius: 16px; padding: 24px; margin: 24px 0; }
        .card-title { font-size: 13px; font-weight: 700; color: #16a34a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #bbf7d0; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 13px; color: #6b7280; font-weight: 500; }
        .detail-value { font-size: 14px; color: #111827; font-weight: 700; text-align: right; max-width: 60%; }
        .success-box { text-align: center; background: #f0fdf4; border: 2px solid #4ade80; border-radius: 20px; padding: 28px; margin: 20px 0; }
        .success-box .icon { font-size: 48px; margin-bottom: 12px; }
        .success-box h3 { font-size: 20px; font-weight: 800; color: #14532d; margin-bottom: 6px; }
        .success-box p { font-size: 14px; color: #15803d; line-height: 1.6; }
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .info-box p { font-size: 13px; color: #1e40af; line-height: 1.6; }
        .btn { display: block; background: linear-gradient(135deg, #059669, #16a34a); color: #fff !important; text-decoration: none; text-align: center; padding: 16px 32px; border-radius: 12px; font-weight: 800; font-size: 15px; margin: 24px 0; }
        .footer { background: #1f2937; padding: 28px 32px; border-radius: 0 0 20px 20px; text-align: center; }
        .footer p { color: #9ca3af; font-size: 12px; line-height: 1.8; }
        .footer .brand { color: #fff; font-weight: 800; font-size: 16px; margin-bottom: 8px; }
        .footer a { color: #34d399; text-decoration: none; }
        .status-dp { display: inline-block; background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 700; }
        .status-lunas { display: inline-block; background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 700; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        @if($pesanan->status === 'DP Lunas')
            <div style="font-size: 40px; margin-bottom: 12px;">💰</div>
            <h1>DP Lunas Dikonfirmasi!</h1>
            <p>Pembayaran uang muka Anda telah berhasil diverifikasi</p>
            <div class="badge">DP Lunas ✓</div>
        @else
            <div style="font-size: 40px; margin-bottom: 12px;">🎉</div>
            <h1>Pembayaran Lunas!</h1>
            <p>Selamat! Pembayaran Anda telah lunas dan dikonfirmasi</p>
            <div class="badge">LUNAS ✓</div>
        @endif
    </div>

    <div class="body">
        <p class="greeting">Halo, {{ $pesanan->user->name ?? 'Wisatawan' }}! 🎊</p>
        <p class="text">
            @if($pesanan->status === 'DP Lunas')
                Uang muka (DP) Anda untuk pemesanan Jeep Dieng telah <strong>berhasil diverifikasi</strong> oleh admin kami. Pemesanan Anda sudah dikonfirmasi!
            @else
                Selamat! Pembayaran Anda untuk pemesanan Jeep Dieng telah <strong>lunas dan dikonfirmasi</strong> oleh admin kami. Bersiaplah untuk petualangan seru di Dieng!
            @endif
        </p>

        <div class="success-box">
            <div class="icon">✅</div>
            <h3>
                @if($pesanan->status === 'DP Lunas') DP Telah Dikonfirmasi @else Pembayaran Lunas @endif
            </h3>
            <p>#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="card">
            <div class="card-title">📋 Detail Pemesanan</div>
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
                <span class="detail-label">Penyedia</span>
                <span class="detail-value">{{ $pesanan->komunitas->nama_komunitas ?? 'Jeep Dieng' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tipe Trip</span>
                <span class="detail-value">{{ $pesanan->tipe_trip ?? 'Private' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Tour</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('l, d F Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Titik Jemput</span>
                <span class="detail-value">{{ $pesanan->titik_jemput }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Peserta & Armada</span>
                <span class="detail-value">{{ $pesanan->jumlah_pengunjung }} Orang ({{ $pesanan->jumlah_jeep }} Jeep)</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    @if($pesanan->status === 'DP Lunas')
                        <span class="status-dp">DP Lunas</span>
                    @else
                        <span class="status-lunas">LUNAS</span>
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Tagihan</span>
                <span class="detail-value" style="color: #059669; font-size: 16px;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>

            @if(isset($pesanan->armadas) && $pesanan->armadas->count() > 0)
            <div class="detail-row" style="flex-direction: column; align-items: flex-start; padding-top: 16px;">
                <span class="detail-label" style="margin-bottom: 10px;">Armada Ditugaskan</span>
                <div style="width: 100%;">
                    @foreach($pesanan->armadas as $index => $armada)
                    <div style="background: rgba(255,255,255,0.7); border: 1px dashed #4ade80; padding: 10px 12px; border-radius: 8px; margin-bottom: 8px; text-align: left;">
                        <span style="font-size: 12px; font-weight: 800; color: #16a34a; display: block; margin-bottom: 4px;">JEEP {{ $index + 1 }}</span>
                        <span style="font-size: 13px; color: #111827;">🚘 {{ $armada->jeep->nama ?? $armada->jeep->merk ?? 'TBA' }} &nbsp;|&nbsp; 👨‍✈️ Supir: {{ $armada->supir->nama ?? 'TBA' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        @if($pesanan->status === 'DP Lunas')
        <div class="info-box">
            <p>ℹ️ <strong>Catatan:</strong> Anda membayar dengan skema <strong>DP (Uang Muka)</strong>. Harap siapkan sisa pelunasan sebesar <strong>Rp {{ number_format($pesanan->total_harga / 2, 0, ',', '.') }}</strong> dan lunasi sebelum tanggal keberangkatan melalui halaman riwayat pesanan.</p>
        </div>
        <a href="{{ config('app.url') }}/dashboard" class="btn">Bayar Pelunasan →</a>
        @else
        <div class="info-box">
            <p>🎫 <strong>E-Tiket Anda sudah siap!</strong> Silakan login ke akun Anda dan cetak E-Tiket digital Anda. Tunjukkan E-Tiket tersebut kepada driver pada saat keberangkatan.</p>
        </div>
        <a href="{{ config('app.url') }}/dashboard" class="btn">Lihat & Cetak E-Tiket →</a>
        @endif
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