<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                font-size: 12px;
                color: #000;
            }

            table th,
            table td {
                font-size: 12px !important;
                padding: 8px !important;
            }
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold">LAPORAN PENDAPATAN</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Sumber</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @forelse ($pendapatans as $item)
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
                    <tr class="table-light">
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end text-bold">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
            
        </div>

        <!-- Bagian Tanda Tangan -->
        <div class="row mt-5">
            <div class="col-6"></div>
            <div class="col-6 text-end">
                <p class="mb-1">..., <span id="tanggal-sekarang"></span></p>

                <p class="mb-5">Pemilik usaha</p>
                <p class="fw-bold text-uppercase mb-1">Cici Herman</p>
                <p class="mb-0">NIP: 19650415 199003 1 004</p>
            </div>
        </div>

        <script type="text/javascript">
            window.print();
        </script>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalElement = document.getElementById('tanggal-sekarang');
            const bulanIndonesia = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const today = new Date();
            const tanggal = today.getDate().toString().padStart(2, '0');
            const bulan = bulanIndonesia[today.getMonth()];
            const tahun = today.getFullYear();

            tanggalElement.textContent = `${tanggal} ${bulan} ${tahun}`;
        });
    </script>
</body>

</html>
