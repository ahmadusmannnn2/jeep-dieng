<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Penyewaan_Jeep_Dieng_{{ $tanggal_mulai }}_s_d_{{ $tanggal_selesai }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; color: #333; font-size: 12px; margin: 30px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header { margin-bottom: 30px; border-bottom: 3px double #333; padding-bottom: 10px; }
        .header h2 { margin: 0 0 5px 0; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 0; color: #666; font-size: 13px; }
        .meta-info { margin-bottom: 20px; font-size: 13px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #f2f2f2; font-weight: bold; border: 1px solid #ddd; padding: 10px; text-align: left; text-transform: uppercase; font-size: 11px; }
        td { border: 1px solid #ddd; padding: 10px; }
        tr:nth-child(even) { background-color: #fafafa; }
        .footer-total { font-size: 14px; background-color: #eaecf0 !important; font-weight: bold; }
        .signature-container { margin-top: 50px; float: right; text-align: center; width: 200px; font-size: 13px; }
        .signature-space { height: 70px; }
        
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="header text-center">
        <h2>Sistem Manajemen Penyewaan Jeep Dataran Tinggi Dieng</h2>
        <p>Laporan Rekapitulasi Pendapatan Finansial & Operasional Armada</p>
    </div>

    <div class="meta-info">
        <table style="width: auto; border: none; margin: 0;">
            <tr style="background: none;"><td style="border: none; padding: 2px 10px 2px 0;">Penyelenggara</td><td style="border: none; padding: 2px 10px;">: <strong>{{ $nama_komunitas }}</strong></td></tr>
            <tr style="background: none;"><td style="border: border: none; padding: 2px 10px 2px 0;">Periode Laporan</td><td style="border: none; padding: 2px 10px;">: {{ \Carbon\Carbon::parse($tanggal_mulai)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_selesai)->translatedFormat('d F Y') }}</td></tr>
            <tr style="background: none;"><td style="border: border: none; padding: 2px 10px 2px 0;">Tanggal Cetak</td><td style="border: none; padding: 2px 10px;">: {{ now()->translatedFormat('l, d F Y H:i') }} WIB</td></tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Waktu Transaksi</th>
                <th style="width: 15%;">Tgl Trip</th>
                <th style="width: 15%;">Nama Wisatawan</th>
                <th style="width: 15%;">Armada & Supir</th>
                <th style="width: 20%;">Paket Perjalanan</th>
                <th style="width: 15%; text-align: right;">Tarif Flat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($laporan as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>
                    <span class="font-bold">#BKG-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span><br>
                    <small style="color:#666;">{{ $item->created_at->format('d M Y H:i') }}</small>
                </td>
                <td class="font-bold" style="color: #10b981;">{{ \Carbon\Carbon::parse($item->tanggal_jadwal)->format('d/m/Y') }}</td>
                <td>{{ $item->user->name }}</td>
                <td>
                    {{ $item->jeep->nama_jeep ?? '-' }}<br>
                    <small style="color:#666;">Driver: {{ $item->supir->nama_supir ?? '-' }}</small>
                </td>
                <td>{{ $item->paketWisata->nama_paket ?? 'Paket Terhapus' }}</td>
                <td class="text-right font-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #666;">Tidak ada catatan transaksi pada rentang waktu ini.</td>
            </tr>
            @endforelse
            
            <tr class="footer-total">
                <td colspan="6" class="text-right">TOTAL PENDAPATAN BRUTO BERSIH:</td>
                <td class="text-right">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-container">
        <p>Wonosobo, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Mengetahui,<br><strong>Ketua Komunitas Jeep</strong></p>
        <div class="signature-space"></div>
        <p style="text-decoration: underline; font-weight: bold;">( .................................... )</p>
    </div>

    <script>
        window.print();
    </script>

</body>
</html>