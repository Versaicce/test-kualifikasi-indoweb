<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Tahunan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="card">
        <div class="card-header">
            <h4>Laporan Penjualan Tahunan</h4>
        </div>
        <div class="card-body">
            <form method="GET" class="d-flex gap-2 mb-3">
                <input type="number" name="tahun" class="form-control w-25" placeholder="Pilih Tahun"
                    value="{{ $tahun }}">
                <button class="btn btn-primary">Tampilkan</button>
                <a href="/download-database" class="btn btn-success">Download Database</a>
            </form>

            @if ($tahun)
                <table class="table table-bordered table-sm text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2" class="text-center align-middle" style="width:220px; font-size:16px;">Menu</th>
                            <th colspan="12" class="text-center">
                                Periode Pada {{ $tahun }}
                            </th>
                            <th rowspan="2" class="text-center align-middle" style="width:120px;">Total</th>
                        </tr>
                        <tr>
                            @foreach (range(1, 12) as $b)
                                <th>{{ DateTime::createFromFormat('!m', $b)->format('M') }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $kategori => $menus)
                            <tr class="table-secondary">
                                <td colspan="14" class="text-start fw-bold">
                                    {{ ucfirst($kategori) }}
                                </td>
                            </tr>

                            @foreach ($menus as $nama => $items)
                                @php $rowTotal = 0; @endphp
                                <tr>
                                    <td class="text-start">{{ $nama }}</td>

                                    @foreach (range(1, 12) as $b)
                                        @php
                                            $val = $items->firstWhere('bulan', $b)->total ?? 0;
                                            $rowTotal += $val;
                                        @endphp
                                        <td>{{ number_format($val) }}</td>
                                    @endforeach

                                    <td class="fw-bold">{{ number_format($rowTotal) }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>

                    <tfoot class="table-dark">
                        <tr>
                            <th>Grand Total</th>
                            @foreach (range(1, 12) as $b)
                                <th>{{ number_format($grandTotal[$b] ?? 0) }}</th>
                            @endforeach
                            <th>{{ number_format(collect($grandTotal)->sum()) }}</th>
                        </tr>
                    </tfoot>
                </table>
            @endif

        </div>
    </div>
</body>

</html>
