<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
        }
        .section {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="title">Invoice Pemesanan</div>

    <div class="section">
        <strong>Nama Pemesan:</strong> {{ $pemesan->name }} <br>
        <strong>Alamat Pemesan:</strong> {{ $pemesan->alamat ?? 'Tidak tersedia' }}
    </div>

    <div class="section">
        <strong>Nama Pekerja:</strong> {{ $pesan->name }} <br>
        <strong>Alamat Pekerja:</strong> {{ $pesan->alamat ?? 'Tidak tersedia' }}
    </div>

    <div class="section">
        <strong>Waktu Order:</strong> {{ $order->waktu }} <br>
        <strong>Batas Waktu:</strong> {{ $order->batas }} <br>
        <strong>Lokasi:</strong> {{ $order->location }} <br>
        <strong>Biaya:</strong> Rp {{ number_format($order->cost, 0, ',', '.') }}
    </div>

    <div class="section">
        <em>Terima kasih telah menggunakan layanan kami!</em>
    </div>
</body>
</html>
