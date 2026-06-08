<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket Perjalanan Anda</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f4f6; color: #1f2937; }
        .wrapper { max-width: 600px; margin: 30px auto; }
        .header { background: linear-gradient(135deg, #111827, #1f2937); padding: 40px 32px; border-radius: 20px 20px 0 0; text-align: center; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: #059669; border-radius: 50%; opacity: 0.1; }
        .header h1 { color: #fff; font-size: 24px; font-weight: 800; }
        .header p { color: #9ca3af; margin-top: 6px; font-size: 14px; }
        .body { background: #fff; padding: 32px; }
        .ticket { background: #111827; border-radius: 20px; padding: 28px; margin: 20px 0; position: relative; overflow: hidden; }
        .ticket::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #059669, #34d399, #0d9488); }
        .ticket-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #374151; }
        .ticket-brand { color: #34d399; font-size: 18px; font-weight: 900; letter-spacing: 1px; }
        .ticket-type { background: #059669; color: #fff; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .ticket-kode { text-align: center; padding: 16px 0; border-bottom: 1px dashed #374151; margin-bottom: 16px; }
        .ticket-kode .label { font-size: 11px; color: #6b7280; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 6px; }
        .ticket-kode .kode { font-size: 30px; font-weight: 900; color: #34d399; letter-spacing: 3px; }
        .ticket-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .ticket-item .label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .ticket-item .value { font-size: 13px; font-weight: 700; color: #e5e7eb; }
        .ticket-item .value.accent { color: #34d399; font-size: 15px; }
        .ticket-divider { border: none; border-top: 2px dashed #374151; margin: 16px 0; position: relative; }
        .ticket-divider::before { content: '✂'; position: absolute; left: 50%; transform: translateX(-50%) translateY(-50%); background: #111827; color: #6b7280; padding: 0 8px; font-size: 12px; top: 0; }
        .card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px; padding: 20px; margin: 16px 0; }
        .card-title { font-size: 12px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 12px; color: #6b7280; }
        .detail-value { font-size: 13px; color: #111827; font-weight: 600; text-align: right; max-width: 55%; }
        .rules { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .rules-title { font-size: 12px; font-weight: 700; color: #92400e; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .rules ul { list-style: none; }
        .rules ul li { font-size: 12px; color: #78350f; padding: 3px 0; padding-left: 16px; position: relative; line-height: 1.5; }
        .rules ul li::before { content: '•'; position: absolute; left: 0; color: #d97706; font-weight: 900; }
        .footer { background: #1f2937; padding: 28px 32px; border-radius: 0 0 20px 20px; text-align: center; }
        .footer p { color: #9ca3af; font-size: 12px; line-height: 1.8; }
        .footer .brand { color: #fff; font-weight: 800; font-size: 16px; margin-bottom: 8px; }
        .footer a { color: #34d399; text-decoration: none; }
        .greeting { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        .text { color: #4b5563; line-height: 1.7; font-size: 14px; margin-bottom: 16px; }
        .congrats { text-align: center; padding: 20px; background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-radius: 16px; margin-bottom: 20px; border: 1px solid #86efac; }
        .congrats .emoji { font-size: 40px; margin-bottom: 8px; }
        .congrats h3 { font-size: 18px; font-weight: 800; color: #14532d; }
        .congrats p { font-size: 13px; color: #15803d; margin-top: 4px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div style="font-size: 48px; margin-bottom: 12px;">🎫</div>
        <h1>E-Tiket Perjalanan Anda</h1>
        <p>Tunjukkan tiket ini kepada driver saat keberangkatan</p>
    </div>

    <div class="body">
        <p class="greeting">Halo, {{ $pesanan->user->name ?? 'Wisatawan' }}! 🎊</p>
        <p class="text">Selamat! Pembayaran Anda telah lunas. Berikut adalah E-Tiket resmi perjalanan wisata Jeep Dieng Anda. Simpan atau cetak tiket ini dan tunjukkan kepada driver pada hari keberangkatan.</p>

        <div class="congrats">
            <div class="emoji">🏔️</div>
            <h3>Selamat Menikmati Petualangan!</h3>
            <p>Bersiaplah untuk pengalaman tak terlupakan di Dataran Tinggi Dieng</p>
        </div>

        {{-- E-TIKET UTAMA --}}
        <div class="ticket">
            <div class="ticket-header">
                <div class="ticket-brand">🚙 JEEP DIENG</div>
                <div class="ticket-type">E-Tiket Resmi</div>
            </div>

            <div class="ticket-kode">
                <div class="label">Kode Booking</div>
                <div class="kode">#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>

            <div class="ticket-grid">
                <div class="ticket-item">
                    <div class="label">Nama Pemesan</div>
                    <div class="value">{{ $pesanan->user->name ?? '-' }}</div>
                </div>
                <div class="ticket-item">
                    <div class="label">Jumlah Orang</div>
                    <div class="value accent">{{ $pesanan->jumlah_pengunjung }} Orang</div>
                </div>
                <div class="ticket-item">
                    <div class="label">Paket</div>
                    <div class="value">{{ $pesanan->paketWisata->nama_paket ?? '-' }}</div>
                </div>
                <div class="ticket-item">
                    <div class="label">Penyedia</div>
                    <div class="value">{{ $pesanan->komunitas->nama_komunitas ?? 'Jeep Dieng' }}</div>
                </div>
            </div>

            <hr class="ticket-divider">

            <div class="ticket-grid">
                <div class="ticket-item">
                    <div class="label">Tanggal</div>
                    <div class="value accent">{{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('d F Y') }}</div>
                </div>
                <div class="ticket-item">
                    <div class="label">Titik Jemput</div>
                    <div class="value">{{ $pesanan->titik_jemput }}</div>
                </div>
                @if($pesanan->jeep)
                <div class="ticket-item">
                    <div class="label">No. Kendaraan</div>
                    <div class="value accent">{{ $pesanan->jeep->nomor_polisi ?? '-' }}</div>
                </div>
                @endif
                @if($pesanan->supir)
                <div class="ticket-item">
                    <div class="label">Driver</div>
                    <div class="value">{{ $pesanan->supir->nama_supir ?? '-' }}</div>
                </div>
                @endif
            </div>

            <hr class="ticket-divider">

            <div class="ticket-item" style="text-align: center; margin-top: 8px;">
                <div class="label" style="text-align: center;">Total Tagihan</div>
                <div class="value accent" style="text-align: center; font-size: 22px;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="rules">
            <div class="rules-title">📋 Tata Tertib & Ketentuan</div>
            <ul>
                <li>Harap tiba di titik jemput <strong>15 menit sebelum</strong> waktu yang ditentukan.</li>
                <li>Tunjukkan E-Tiket ini (digital/cetak) kepada driver sebelum berangkat.</li>
                <li>Tiket masuk ke lokasi wisata <strong>belum termasuk</strong> dalam harga paket.</li>
                <li>Keselamatan adalah prioritas — ikuti instruksi driver selama perjalanan.</li>
                <li>Untuk cuaca ekstrem, perjalanan dapat dijadwal ulang demi keselamatan.</li>
                <li>Hubungi kami segera jika ada perubahan rencana minimal H-1.</li>
            </ul>
        </div>

        @if($pesanan->supir && $pesanan->supir->no_hp)
        <div class="card">
            <div class="card-title">📞 Kontak Driver</div>
            <div class="detail-row">
                <span class="detail-label">Nama Driver</span>
                <span class="detail-value">{{ $pesanan->supir->nama_supir }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">No. HP / WhatsApp</span>
                <span class="detail-value" style="color: #059669;">{{ $pesanan->supir->no_hp }}</span>
            </div>
        </div>
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
