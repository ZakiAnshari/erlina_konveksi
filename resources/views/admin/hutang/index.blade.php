@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <h4 class="fw-bold d-flex align-items-center my-4">
                    <i class="menu-icon tf-icons bx bx-id-card" style="font-size: 1.5rem;"></i>
                    <span class="text-muted fw-light me-1"></span> Data Hutang
                </h4>
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <h5>Table Hutang</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Form Search -->
                                    <form method="GET" class="d-flex align-items-center my-3" style="max-width: 350px;">
                                        <div class="input-group shadow-sm" style="height: 38px; width: 100%;">
                                            <input type="text" name="nama_pihak" value="{{ request('nama_pihak') }}"
                                                class="form-control border-end-0 py-2 px-3" style="font-size: 0.9rem;"
                                                placeholder="Cari nama pihak..." aria-label="Search">
                                            <button class="btn btn-outline-primary px-3" type="submit"
                                                style="font-size: 0.9rem;">
                                                <i class="bx bx-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <!-- Judul -->
                                    <!-- Tombol Aksi -->
                                    <div class="d-flex gap-2">
                                        <!-- Tombol Tambah -->
                                        <button type="button"
                                            class="btn btn-outline-success account-image-reset  d-flex align-items-center"
                                            data-bs-toggle="modal" data-bs-target="#addProductModal">
                                            <i class="bx bx-plus me-2 d-block"></i>
                                            <span>Tambah</span>
                                        </button>

                                    </div>
                                </div>

                                <!-- Modal tambah Data -->
                                <div class="modal fade" id="addProductModal" tabindex="-1"
                                    aria-labelledby="addProductModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <!-- Judul -->
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addProductModalLabel">Tambah Hutang</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="hutang-add" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <!-- Kolom Kiri -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                                <input type="date" name="tanggal" class="form-control"
                                                                    id="tanggal" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="nama_pihak" class="form-label">Nama
                                                                    Pihak</label>
                                                                <input type="text" name="nama_pihak" class="form-control"
                                                                    id="nama_pihak" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="tipe" class="form-label">Tipe</label>
                                                                <select name="tipe" id="tipe" class="form-control"
                                                                    required>
                                                                    <option value="" disabled selected>-- Pilih Tipe
                                                                        --</option>
                                                                    <option value="Hutang">Hutang</option>
                                                                    <option value="Piutang">Piutang</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Kolom Kanan -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                                                                <input type="text" name="jumlah" class="form-control"
                                                                    id="jumlah" oninput="formatRupiah(this)"
                                                                    placeholder="Rp." required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="jatuh_tempo" class="form-label">Jatuh
                                                                    Tempo</label>
                                                                <input type="date" name="jatuh_tempo"
                                                                    class="form-control" id="jatuh_tempo" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="status" class="form-label">Status</label>
                                                                <select name="status" id="status" class="form-control"
                                                                    required>
                                                                    <option value="" disabled selected>-- Pilih
                                                                        Status --</option>
                                                                    <option value="Belum Lunas">Belum Lunas</option>
                                                                    <option value="Lunas">Lunas</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- Tombol -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Table Data -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%">No</th>
                                            <th>Nama Pihak</th>
                                            <th>Tipe</th>
                                            <th>Jumlah (Rp)</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Status</th>
                                            <th>Tanggal Dicatat</th>
                                            <th style="width: 80px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($hutangs as $index => $item)
                                            <tr>
                                                <td>{{ $hutangs->firstItem() + $index }}</td>
                                                <td>{{ $item->nama_pihak }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ strtolower($item->tipe) == 'hutang' ? 'danger' : 'warning' }}">
                                                        {{ ucfirst($item->tipe) }}
                                                    </span>
                                                </td>

                                                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d M Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $item->status == 'lunas' ? 'success' : 'danger' }}">
                                                        {{ $item->status }}
                                                    </span>
                                                </td>

                                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                                <td>
                                                    <a href="hutang-edit/{{ $item->id }}"
                                                        class="btn btn-icon btn-outline-primary" title="Edit Data">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>

                                                    <a href="javascript:void(0)"
                                                        onclick="confirmDeleteHutang({{ $item->id }}, @js($item->nama_pihak))"
                                                        style="display:inline;">
                                                        <button class="btn btn-icon btn-outline-danger">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Data hutang/piutang kosong.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $hutangs->appends(request()->input())->links('pagination::bootstrap-4') }}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDeleteHutang(id, nama) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: `"${nama}" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/hutang-destroy/${id}`;
                }
            });
        }
    </script>

    <script>
        const today = new Date().toISOString().split('T')[0];

        document.getElementById('tanggal').setAttribute('min', today);
        document.getElementById('jatuh_tempo').setAttribute('min', today);
    </script>
    @include('sweetalert::alert')
@endsection
