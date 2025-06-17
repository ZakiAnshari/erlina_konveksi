@extends('layouts.admin')
@section('title', 'Laporan Pendapatan')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Heading -->
            <h4 class="fw-bold d-flex align-items-center my-4">
                <i class="menu-icon tf-icons bx bx-trending-down"></i>
                <span>Data Pengeluaran</span>
            </h4>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('laporan.pengeluaran') }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row gx-3 gy-2">
                            <!-- Hari -->
                            <div class="col-md-3">
                                <label class="form-label" for="hari">Tanggal</label>
                                <select id="hari" name="hari" class="form-select">
                                    <option value="">-- Pilih Tanggal --</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        @php $tgl = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $tgl }}" {{ request('hari') == $tgl ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Bulan -->
                            <div class="col-md-3">
                                <label class="form-label" for="bulan">Bulan</label>
                                <select id="bulan" name="bulan" class="form-select">
                                    <option value="">-- Pilih Bulan --</option>
                                    @foreach ([
                                        '01' => 'Januari',
                                        '02' => 'Februari',
                                        '03' => 'Maret',
                                        '04' => 'April',
                                        '05' => 'Mei',
                                        '06' => 'Juni',
                                        '07' => 'Juli',
                                        '08' => 'Agustus',
                                        '09' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember',
                                    ] as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ request('bulan') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Tahun -->
                            <div class="col-md-3">
                                <label class="form-label" for="tahun">Tahun</label>
                                <select id="tahun" name="tahun" class="form-select">
                                    <option value="">-- Pilih Tahun --</option>
                                    @for ($year = 2024; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}"
                                            {{ request('tahun') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <!-- Tombol Cari -->
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tabel Hasil Pencarian -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5>Table Pengeluaran</h5>
                    @if ($pengeluarans->isEmpty())
                        <div class="alert alert-warning mb-0">Tidak ada data pendapatan ditemukan.</div>
                    @else
                        <div class="table-responsive">
                            <div class="d-flex gap-2 justify-content-end mb-4">
                                <!-- Tombol Cetak -->
                                <a href="{{ route('laporan.pengeluaran-cetak', request()->query()) }}"
                                    class="btn btn-outline-success d-flex align-items-center" target="_blank">
                                    <i class="bi bi-printer me-2"></i>
                                    <span>Cetak</span>
                                </a>
                            </div>
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
                                    @foreach ($pengeluarans as $item)
                                        @php $total += $item->jumlah; @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                            <td>{{ $item->sumber }}</td>
                                            <td class="text-end">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-end text-bold">Rp {{ number_format($total, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    @include('sweetalert::alert')
@endsection
