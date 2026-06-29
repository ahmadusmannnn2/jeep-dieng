<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Berhasil</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f6; color: #1f2937; }
        .wrapper { max-width: 600px; margin: 30px auto; }
        .header { background: linear-gradient(135deg, #059669, #0d9488); padding: 40px 32px; border-radius: 20px 20px 0 0; text-align: center; }
        .header img { width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 16px; padding: 10px; margin-bottom: 16px; }
        .header h1 { color: #fff; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { color: rgba(255,255,255,0.8); margin-top: 6px; font-size: 14px; }
        .badge { display: inline-block; background: rgba(255,255,255,0.15); color: #fff; padding: 6px 16px; border-radius: 100px; font-size: 13px; font-weight: 700; margin-top: 12px; border: 1px solid rgba(255,255,255,0.3); }
        .body { background: #fff; padding: 32px; }
        .greeting { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .text { color: #4b5563; line-height: 1.7; font-size: 14px; margin-bottom: 16px; }
        .card { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 16px; padding: 24px; margin: 24px 0; }
        .card-title { font-size: 13px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #d1fae5; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 13px; color: #6b7280; font-weight: 500; }
        .detail-value { font-size: 14px; color: #111827; font-weight: 700; text-align: right; max-width: 60%; }
        .kode { font-size: 28px; font-weight: 900; color: #059669; text-align: center; background: #f0fdf4; border: 2px dashed #86efac; border-radius: 12px; padding: 16px; margin: 20px 0; letter-spacing: 2px; }
        .harga { font-size: 24px; font-weight: 900; color: #059669; }
        .steps { margin: 24px 0; }
        .step { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px; }
        .step-num { width: 28px; height: 28px; background: #059669; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; flex-shrink: 0; }
        .step-text { font-size: 13px; color: #374151; line-height: 1.5; }
        .step-text strong { color: #111827; }
        .btn { display: block; background: linear-gradient(135deg, #059669, #0d9488); color: #fff !important; text-decoration: none; text-align: center; padding: 16px 32px; border-radius: 12px; font-weight: 800; font-size: 15px; margin: 24px 0; }
        .warning { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin: 20px 0; }
        .warning p { font-size: 13px; color: #92400e; line-height: 1.6; }
        .footer { background: #1f2937; padding: 28px 32px; border-radius: 0 0 20px 20px; text-align: center; }
        .footer p { color: #9ca3af; font-size: 12px; line-height: 1.8; }
        .footer a { color: #34d399; text-decoration: none; }
        .footer .brand { color: #fff; font-weight: 800; font-size: 16px; margin-bottom: 8px; }
        .divider { height: 1px; background: #e5e7eb; margin: 24px 0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div style="font-size: 40px; margin-bottom: 12px;">🚙</div>
        <h1>Pemesanan Berhasil!</h1>
        <p>Terima kasih telah mempercayakan perjalanan Anda kepada kami</p>
        <div class="badge">Konfirmasi Pemesanan</div>
    </div>

    <div class="body">
        <p class="greeting">Halo, {{ $pesanan->user->name ?? 'Wisatawan' }}! 👋</p>
        <p class="text">Pemesanan paket wisata Jeep Dieng Anda telah berhasil kami terima. Berikut adalah ringkasan detail pemesanan Anda:</p>

        <div class="kode">#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</div>

        <div class="card">
            <div class="card-title">📋 Detail Pemesanan</div>
            <div class="detail-row">
                <span class="detail-label">Paket Wisata</span>
                <span class="detail-value">{{ $pesanan->paketWisata->nama_paket ?? '-' }}</span>
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
                <span class="detail-label">Tanggal Keberangkatan</span>
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
                <span class="detail-label">Total Tagihan</span>
                <span class="detail-value"><span class="harga">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span></span>
            </div>
            
            @if(isset($pesanan->armadas) && $pesanan->armadas->count() > 0)
            <div class="detail-row" style="flex-direction: column; align-items: flex-start; padding-top: 16px;">
                <span class="detail-label" style="margin-bottom: 10px;">Armada Ditugaskan</span>
                <div style="width: 100%;">
                    @foreach($pesanan->armadas as $index => $armada)
                    <div style="background: rgba(255,255,255,0.7); border: 1px dashed #6ee7b7; padding: 10px 12px; border-radius: 8px; margin-bottom: 8px; text-align: left;">
                        <span style="font-size: 12px; font-weight: 800; color: #059669; display: block; margin-bottom: 4px;">JEEP {{ $index + 1 }}</span>
                        <span style="font-size: 13px; color: #111827;">🚘 {{ $armada->jeep->nama ?? $armada->jeep->merk ?? 'TBA' }} &nbsp;|&nbsp; 👨‍✈️ Supir: {{ $armada->supir->nama ?? 'TBA' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="divider"></div>

        <p style="font-size: 15px; font-weight: 700; color: #111827; margin-bottom: 12px;">Langkah Selanjutnya:</p>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Unggah Bukti Pembayaran</strong> — Login ke akun Anda, buka halaman "Riwayat Pesanan", lalu klik tombol "Bayar Sekarang" untuk mengunggah bukti transfer.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Tunggu Konfirmasi</strong> — Tim admin kami akan memverifikasi pembayaran Anda dalam waktu 1x24 jam. Anda akan mendapatkan notifikasi email setelah dikonfirmasi.</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Cetak E-Tiket</strong> — Setelah pembayaran dikonfirmasi, Anda bisa mencetak atau menyimpan E-Tiket digital Anda sebagai tanda masuk.</div>
            </div>
        </div>

        <a href="{{ config('app.url') }}/dashboard" class="btn">Bayar Sekarang →</a>

        <div class="warning">
            <p>⚠️ <strong>Penting:</strong> Pesanan ini berstatus <strong>Menunggu Pembayaran</strong>. Harap selesaikan pembayaran dalam 24 jam untuk menghindari pembatalan otomatis. Jika ada pertanyaan, hubungi kami melalui WhatsApp.</p>
        </div>
    </div>

    <div class="footer">
        <div class="brand">🚙 JEEP DIENG</div>
        <p>Wisata Alam Dieng — Pengalaman Tak Terlupakan</p>
        <p style="margin-top: 8px;">Email ini dikirim otomatis oleh sistem. Jangan membalas email ini.</p>
        <p style="margin-top: 8px;">Butuh bantuan? Hubungi kami di <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>
        <p style="margin-top: 12px; color: #6b7280; font-size: 11px;">© {{ date('Y') }} Jeep Dieng. Semua hak dilindungi.</p>
    </div>
</div>
</body>
</html>