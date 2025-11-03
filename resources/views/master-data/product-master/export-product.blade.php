<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Produk</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .company-info { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; }
        .footer { margin-top: 20px; font-size: 11px; }
        .signature { margin-top: 40px; width: 100%; text-align: right; }
    </style>
</head>
<body>
    <div class="company-info">
        <h1>PT SUSU ALAM JAYA</h1>
        <h2>Rekap Stock Produk Gudang</h2>
        <h3>Periode: {{ $startDate ?? '-' }} - {{ $endDate ?? '-' }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Unit</th>
                <th>Tipe</th>
                <th>Qty</th>
                <th>Produsen</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->unit }}</td>
                <td>{{ $p->category }}</td>
                <td>{{ $p->stock }}</td>
                <td>{{ $p->supplier }}</td>
                <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Catatan: Data bersifat rahasia. Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="signature">
        <p>Kepala Logistik</p>
        <br><br><br>
        <p>Jonathan Aditya Saputra</p>
    </div>
</body>
</html>
