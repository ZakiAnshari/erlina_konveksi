@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <h4 class="fw-bold d-flex align-items-center my-4">
                    <i class="menu-icon tf-icons bx bx-id-card" style="font-size: 1.5rem;"></i>
                    <span class="text-muted fw-light me-1"></span> Data Karyawan
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
                                <h5>Table Karyawan</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Form Search -->
                                    <form method="GET" class="d-flex align-items-center my-3" style="max-width: 350px;">
                                        <div class="input-group shadow-sm" style="height: 38px; width: 100%;">
                                            <input type="text" name="nama" value="{{ request('nama') }}"
                                                class="form-control border-end-0 py-2 px-3" style="font-size: 0.9rem;"
                                                placeholder="Cari nama atau ID karyawan..." aria-label="Cari nama atau ID">
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
                                                <h5 class="modal-title" id="addProductModalLabel">Tambah Karyawan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="karyawan-add" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <!-- Kolom Kiri -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="nama" class="form-label">Nama</label>
                                                                <input type="text" name="nama" class="form-control"
                                                                    id="nama" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="posisi" class="form-label">Posisi</label>
                                                                <input type="text" name="posisi" class="form-control"
                                                                    id="posisi" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="kontak" class="form-label">Kontak</label>
                                                                <input type="text" name="kontak" class="form-control"
                                                                    id="kontak" maxlength="12" pattern="\d{1,12}"
                                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);"
                                                                    required>
                                                            </div>

                                                        </div>

                                                        <!-- Kolom Kanan -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="umur" class="form-label">Umur</label>
                                                                <input type="number" name="umur" class="form-control"
                                                                    id="umur" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="alamat" class="form-label">Alamat</label>
                                                                <textarea name="alamat" class="form-control" id="alamat" rows="4" required></textarea>
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
                                            <th style="width: 5px">No</th>
                                            <th>Nama</th>
                                            <th>Posisi</th>
                                            <th style="width: 5px">Kontak</th>
                                            <th style="width: 5px">Umur</th>
                                            <th>Alamat</th>
                                            <th style="width: 5px">ID</th>
                                            <th style="width: 80px; text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($karyawan as $index => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->posisi }}</td>
                                                <td>{{ $item->kontak }}</td>
                                                <td>{{ $item->umur }} tahun</td>
                                                <td>{{ $item->alamat }}</td>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    <a href="karyawan-edit/{{ $item->id }}"
                                                        class="btn btn-icon btn-outline-primary" title="Edit Data">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>

                                                    <a href="javascript:void(0)"
                                                        onclick="confirmDeleteKaryawan({{ $item->id }}, @js($item->nama))"
                                                        style="display:inline;">
                                                        <button class="btn btn-icon btn-outline-danger">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Data Kosong</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                                <!-- Pagination -->
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $karyawan->appends(request()->input())->links('pagination::bootstrap-4') }}

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
        function confirmDeleteKaryawan(id, nama) {
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
                    window.location.href = `/karyawan-destroy/${id}`;
                }
            });
        }
    </script>
    @include('sweetalert::alert')
@endsection
