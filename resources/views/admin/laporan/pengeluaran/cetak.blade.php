<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-size: 13px;
            color: #000;
        }

        .header-logo {
            width: 80px;
            height: auto;
        }

        .kop-text {
            line-height: 1.3;
        }

        .kop-border {
            border-top: 3px solid #000;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
        }

        .signature-block {
            margin-top: 60px;
        }

        @media print {
            .no-print {
                display: none;
            }

            .table th,
            .table td {
                font-size: 12px !important;
                border: 1px solid #000 !important;
            }

            .header-logo {
                width: 70px;
            }
        }
    </style>
</head>

<body class="bg-white">

    <div class="container py-3">
        <!-- Kop Surat -->
        <div class="row align-items-center">
            <div class="col-2 text-start">
                <svg class="header-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="48" fill="#696cff" />
                    <text x="50%" y="56%" text-anchor="middle" fill="#ffffff" font-size="60"
                        font-family="Arial, sans-serif" font-weight="bold" dominant-baseline="middle">
                        E
                    </text>
                </svg>
            </div>
            <div class="col-8 text-center kop-text">
                <h5 class="mb-0 fw-bold text-uppercase">ERLINA KONVEKSI</h5>
                <h6 class="mb-0 text-uppercase">Laporan Keuangan Usaha</h6>
                <p class="mb-0">
                    Kubu Tapi, Jorong Tigo Jorong Batu Taba,
                    Kec. Ampek Angkek, Kab. Agam, Sumatera Barat
                </p>
            </div>


        </div>

        <!-- Garis Pembatas -->
        <div class="kop-border"></div>

        <!-- Judul Laporan -->
        <div class="text-center mb-3">
            <h5 class="fw-bold mb-0">LAPORAN PENGELUARAN</h5>
            <p class="mb-0">Periode : {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>

        <!-- Tabel Pengeluaran -->
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10px">No</th>
                        <th>Tanggal</th>
                        <th>Sumber</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @forelse ($pengeluarans as $item)
                        @php $total += $item->jumlah; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                            <td>{{ $item->sumber }}</td>
                            <td class="text-end">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="3" class="text-end">Total</td>
                        <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Tanda Tangan -->
        <div class="row signature-block">
            <div class="col-6"></div>
            <div class="col-6 text-end">
                <p class="mb-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <p class="mb-5">Pemilik Usaha</p>
                <p class="fw-bold text-uppercase mb-1">Cici Herman</p>
                <p class="mb-0">NIP: 19650415 199003 1 004</p>
            </div>
        </div>

        <!-- Script Print -->
        <script type="text/javascript">
            window.print();
        </script>
    </div>

</body>

</html>
