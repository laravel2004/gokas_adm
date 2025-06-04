<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header-table, .info-table, .detail-table, .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td, .info-table td, .detail-table th, .detail-table td, .footer-table td {
            padding: 5px;
            vertical-align: top;
        }
        .detail-table th, .detail-table td {
            border: 1px solid #ccc;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .paid {
            position: absolute;
            top: 10%;
            left: 1%;
            font-size: 150px;
            color: #cccccc;
            transform: rotate(-15deg);
            z-index: -1;
        }
        .qr {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>

<!-- PAID WATERMARK -->
<div class="paid">{{ strtoupper($invoice->status) }} </div>

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td style="width: 20%;" class="text-center">
            <img src="{{ public_path('build/images/logos/koperasi.png') }}" alt="Logo Kiri" width="150">
        </td>
        <td style="width: 60%;" class="text-center">
            <b>KOPERASI KONSUMEN<br>KARYAWAN REJOSO MANIS INDO</b><br>
            No. AHU - 0002089.AH.01.26.Tahun 2020<br>
            Jl. Raya Rejoso RT. 002 / RW. 003 Ds. Rejoso Kec. Binangun Kab. Blitar<br>
            Jawa Timur
        </td>
        <td style="width: 20%;" class="text-center">
            <img src="{{public_path('build/images/logos/koperasi.png') }}" alt="Logo Kanan" width="150">
        </td>
    </tr>
</table>

<hr>

<!-- INVOICE TITLE -->
<h2 class="text-center">INVOICE</h2>

<!-- INFO -->
<table class="info-table">
    <tr>
        <td style="width: 50%;">
            <b>KOPERASI KARYAWAN REJOSO MANIS INDO</b><br>
            Kab. Blitar, Provinsi Jawa Timur<br><br>
            Ditujukan Kepada : <b>{{ $invoice->account->employee->name }}</b>
        </td>
        <td style="width: 50%;">
            <table style="width: 100%;">
                <tr>
                    <td>No :</td><td class="text-right">INV-11-{{ $invoice->id }}</td>
                </tr>
                <tr>
                    <td>Tanggal :</td><td class="text-right">{{ $invoice->created_at->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>PO. No :</td><td class="text-right">-</td>
                </tr>
                <tr>
                    <td>Quo. No :</td><td class="text-right">-</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- DETAIL -->
<table class="detail-table">
    <thead>
    <tr>
        <th>Tanggal</th>
        <th>Nominal</th>
        <th>Bunga</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($content->unit as $unit)
        <tr>
            <td>{{ \Carbon\Carbon::parse($unit->date)->format('d F Y') }}</td>
            <td>{{ formatRupiah($unit->nominal) }}</td>
            <td>{{ formatRupiah($unit->interest) }}</td>
            <td>{{ formatRupiah($unit->amount) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- TERBILANG DAN KETERANGAN -->
<table class="footer-table">
    <tr>
        <td style="width: 70%;">
            <b>Terbilang :</b><br>
            {{ terbilang($content->total) }}
            <br><br>
            <b>Keterangan :</b><br>
            Pembayaran paylater
        </td>
        <td style="width: 30%;" class="text-center">
            <b>KOPERASI KARYAWAN<br>REJOSO MANIS INDO</b><br><br>
            <img src="{{ public_path('build/images/logos/qr.jpg') }}" alt="QR Code" class="qr"><br><br>
            Koord. Sie Usaha
        </td>
    </tr>
</table>

</body>
</html>
