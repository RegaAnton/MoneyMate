<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #0d6efd;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row th {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Pengeluaran MoneyMate</h2>
        <p><b>{{ $periodeText }}</b></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Kategori</th>
                <th width="35%">Catatan</th>
                <th width="20%" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $index => $exp)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $exp->date->format('d/m/Y') }}</td>
                    <td>{{ $exp->category->name }}</td>
                    <td>{{ $exp->note ?? '-' }}</td>
                    <td class="text-right">{{ number_format($exp->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="4" class="text-right">TOTAL PENGELUARAN</th>
                <th class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
