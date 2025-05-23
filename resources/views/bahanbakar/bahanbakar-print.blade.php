<!DOCTYPE html>
<html>
@php
    use App\Helpers\FormatHelper;
@endphp

<head>
    <title>Rekap BBM {{ $kendaraan->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
            /* border-collapse: collapse; */
            margin-top: 20px;
        }

        th,
        td {
            padding: 6px;
            /* border: 1px solid #000; */
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body onload="window.print()">

    <!-- <body> -->
    <h3 class="text-center">BBM Mobil {{ $kendaraan->no_polisi }}<br>Bulan
        {{ \Carbon\Carbon::parse($start)->translatedFormat('F Y') }}
    </h3>

    <table>
        <thead>
            <!-- <tr>
                <th>Tanggal</th>
                <th>Liter</th>
                <th>Harga / Liter</th>
                <th>Total</th>
            </tr> -->
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ FormatHelper::formatTanggal($item->tanggal_pengisian)  }}:</td>
                    <td>{{ $item->jumlah_liter }} liter </td>
                    <td>X</td>
                    <td>Rp {{ number_format($item->harga_bbm, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="1">Jumlah</th>
                <td style="border-top: 1px solid black;">{{ $totalLiter }} liter</td>
                <td></td>
                <td></td>
                <td style="border-top: 1px solid black;">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <div style="float: right; text-align: center;">
        Pengemudi,<br><br><br><br>
        <u>{{ $kendaraan->user->name }}</u>
    </div>
</body>

</html>