<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Terminal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TERMINAL</h1>
        <p>{{ $terminal->name }}</p>
        <p>Periode: {{ $period['start'] }} s/d {{ $period['end'] }}</p>
    </div>

    <div class="summary">
        <h2>Ringkasan</h2>
        <table>
            <tr>
                <td><strong>Total Keberangkatan:</strong></td>
                <td>{{ number_format($statistics['total_departures']) }}</td>
            </tr>
            <tr>
                <td><strong>Total Penumpang:</strong></td>
                <td>{{ number_format($statistics['total_passengers']) }}</td>
            </tr>
            <tr>
                <td><strong>Total Pendapatan:</strong></td>
                <td>Rp {{ number_format($statistics['total_revenue'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Rata-rata Okupansi:</strong></td>
                <td>{{ number_format($statistics['average_occupancy'], 2) }}%</td>
            </tr>
        </table>
    </div>

    <h2>Detail Keberangkatan</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Rute</th>
                <th>Operator</th>
                <th>Penumpang</th>
                <th>Okupansi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departures as $departure)
            <tr>
                <td>{{ $departure->departure_date }}</td>
                <td>{{ $departure->route->full_name }}</td>
                <td>{{ $departure->operator->name }}</td>
                <td>{{ $departure->passengers }}/{{ $departure->seat_capacity }}</td>
                <td>{{ number_format($departure->occupancy_rate, 1) }}%</td>
                <td>{{ $departure->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ $generated_at }}</p>
    </div>
</body>
</html>