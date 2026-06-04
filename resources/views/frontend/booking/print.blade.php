<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket_Jeep_Dieng_#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #000; margin: 0; padding: 20px; font-size: 14px; }
        .ticket-box { border: 2px dashed #000; max-width: 800px; margin: 0 auto; padding: 30px; position: relative; }
        .header { display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .logo-title h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .logo-title p { margin: 5px 0 0 0; font-size: 12px; font-weight: bold; }
        .booking-code { text-align: right; }
        .booking-code h2 { margin: 0; font-size: 28px; font-family: 'Courier New', Courier, monospace; }
        .booking-code p { margin: 0; font-size: 12px; text-transform: uppercase; }
        .content { display: table; width: 100%; margin-bottom: 20px; }
        .col { display: table-cell; width: 50%; padding-right: 20px; vertical-align: top; }
        .field { margin-bottom: 15px; }
        .label { font-size: 11px; font-weight: bold; text-transform: uppercase; color: #555; display: block; margin-bottom: 3px; }
        .value { font-size: 16px; font-weight: bold; margin: 0; }
        .status-box { display: inline-block; padding: 5px 15px; border: 2px solid #000; font-weight: bold; text-transform: uppercase; font-size: 14px; margin-top: 10px; }
        .footer { border-top: 1px solid #ccc; padding-top: 15px; font-size: 11px; line-height: 1.5; color: #444; }
        .barcode-placeholder { width: 100%; height: 50px; background: repeating-linear-gradient(90deg, #000, #000 2px, transparent 2px, transparent 4px, #000 4px, #000 5px, transparent 5px, transparent 8px); margin-top: 10px; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="ticket-box">
        <div class="header">
            <div class="logo-title">
                <h1>JEEP DIENG E-TICKET</h1>
                <p>BOARDING PASS WISATA ALAM</p>
            </div>
            <div class="booking-code">
                <p>KODE RESERVASI</p>
                <h2>#BKG-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}</h2>
            </div>
        </div>

        <div class="content">
            <div class="col">
                <div class="field">
                    <span class="label">Nama Pemesan (Customer)</span>
                    <p class="value">{{ $pesanan->user->name }}</p>
                </div>
                <div class="field">
                    <span class="label">Paket Perjalanan</span>
                    <p class="value">{{ $pesanan->paketWisata->nama_paket ?? '-' }}</p>
                </div>
                <div class="field">
                    <span class="label">Jadwal Keberangkatan</span>
                    <p class="value">
                        {{ \Carbon\Carbon::parse($pesanan->tanggal_jadwal)->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="field">
                    <span class="label">Lokasi Penjemputan</span>
                    <p class="value" style="font-size: 14px;">{{ $pesanan->titik_jemput }}</p>
                </div>
                <div class="field">
                    <span class="label">Catatan & Waktu:</span>
                    <p class="value" style="font-size: 12px; font-weight: normal; white-space: pre-line;">{{ $pesanan->catatan ?? '-' }}</p>
                </div>
            </div>

            <div class="col">
                <div class="field">
                    <span class="label">Jumlah Peserta</span>
                    <p class="value">{{ $pesanan->jumlah_pengunjung }} Orang (1 Armada)</p>
                </div>
                <div class="field">
                    <span class="label">Komunitas Penyelenggara</span>
                    <p class="value">{{ $pesanan->komunitas->nama_komunitas ?? 'Umum' }}</p>
                </div>
                <div class="field">
                    <span class="label">Armada Jeep & Driver</span>
                    <p class="value" style="font-size: 14px;">
                        Jeep: {{ $pesanan->jeep->nama_jeep ?? 'Menunggu Konfirmasi' }} ({{ $pesanan->jeep->nomor_polisi ?? '-' }})<br>
                        Driver: {{ $pesanan->supir->nama_supir ?? 'Menunggu Konfirmasi' }}
                    </p>
                </div>
                <div class="field">
                    <span class="label">Status Pembayaran</span>
                    <div class="status-box">{{ $pesanan->status }}</div>
                </div>
                
                <div class="barcode-placeholder"></div>
                <div style="text-align: center; font-family: monospace; letter-spacing: 5px; font-size: 10px; margin-top: 5px;">{{ 202600 + $pesanan->id }}89237492</div>
            </div>
        </div>

        <div class="footer">
            <strong>Syarat & Ketentuan:</strong><br>
            1. Harap menunjukkan E-Tiket ini (digital atau cetak) kepada petugas / driver di Basecamp titik kumpul.<br>
            2. Peserta diharapkan berkumpul sesuai dengan waktu jemput yang telah disepakati.<br>
            3. Tiket ini sah dan dikeluarkan secara resmi oleh sistem manajemen komunitas Jeep Dieng area Wonosobo/Banjarnegara.
        </div>
    </div>

    <script>
        window.print();
    </script>

</body>
</html>