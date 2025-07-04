@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <h4 class="fw-bold d-flex align-items-center my-4">
                    <i class="menu-icon tf-icons bx bx-id-card" style="font-size: 1.5rem;"></i>
                    <span class="text-muted fw-light me-1"></span> Data Pendapatan
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
                                <h5>Table Pendapatan</h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Form Search -->
                                    <form method="GET" class="d-flex align-items-center my-3" style="max-width: 350px;">
                                        <div class="input-group shadow-sm" style="height: 38px; width: 100%;">
                                            <input type="text" name="sumber" value="{{ request('sumber') }}"
                                                class="form-control border-end-0 py-2 px-3" style="font-size: 0.9rem;"
                                                placeholder="Cari sumber..." aria-label="Search">
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
                                            <div class="modal-header border-bottom pb-2">
                                                <h5 class="modal-title" id="addProductModalLabel">Tambah Pendapatan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <form action="pendapatan-add" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row ">
                                                        <!-- Kolom Kiri -->
                                                        <div class="col-lg-6 ">
                                                            <div class="mb-3">
                                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                                <input type="date" name="tanggal" class="form-control"
                                                                    id="tanggal" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="sumber" class="form-label">Sumber
                                                                    Pendapatan</label>
                                                                <input type="text" name="sumber" class="form-control"
                                                                    id="sumber" required>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                                                                <input type="text" name="jumlah" class="form-control"
                                                                    id="jumlah" required oninput="formatRupiah(this)"
                                                                    placeholder="Rp.">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Bukti Transaksi</label>
                                                                <input type="file" name="bukti_pendapatan"
                                                                    class="form-control" id="imageGallerypendapatan"
                                                                    accept="image/*"
                                                                    onchange="previewImage(this, 'previewGallerypendapatan')">


                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="row justify-content-center">
                                                                <!-- Tempat tampilnya gambar pratinjau -->
                                                                <img id="previewGallerypendapatan" class="img-fluid mt-3"
                                                                    style="max-width: 200px; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 6px;"
                                                                    alt="Pratinjau Gambar">
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

                                            <th>Tanggal</th>
                                            <th>Sumber</th>
                                            <th>Jumlah</th>
                                            <th style="width: 8px;text-align:center">Bukti</th>
                                            <th style="width: 5px">ID</th>
                                            <th style="width: 8px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pendapatans as $index => $item)
                                            <tr>
                                                <td>{{ $pendapatans->firstItem() + $index }}</td>

                                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                                </td>

                                                <td>{{ $item->sumber }}</td>
                                                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>

                                                <td style="text-align: center">
                                                    @if ($item->bukti_pendapatan)
                                                        <img src="{{ asset('storage/' . $item->bukti_pendapatan) }}"
                                                            alt="Bukti" class="img-thumbnail"
                                                            style="max-width: 100px; max-height: 100px; cursor: pointer;"
                                                            onclick="showImageModal('{{ asset('storage/' . $item->bukti_pendapatan) }}')">
                                                    @else
                                                        <span class="text-muted">Tidak ada bukti</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    <a href="pendapatan-edit/{{ $item->id }}"
                                                        class="btn btn-icon btn-outline-primary" title="Edit Data">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>

                                                    <a href="javascript:void(0)"
                                                        onclick="confirmDeletePendapatan({{ $item->id }}, @js($item->sumber))"
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
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $pendapatans->appends(request()->input())->links('pagination::bootstrap-4') }}

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
        function confirmDeletePendapatan(id, sumber) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: `"${sumber}" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/pendapatan-destroy/${id}`;
                }
            });
        }
    </script>
    {{-- MODAL GAMBAR --}}
    <!-- Modal Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <!-- Header Modal: berisi tombol close (X) -->
                <div class="modal-header border-bottom pb-2">
                    <h5 class="modal-title" id="imageModalLabel">Preview Bukti Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body Modal: berisi gambar -->
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid rounded" alt="Preview"
                        style="max-height: 500px;">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImageModal(imageUrl) {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageUrl;
            const modal = new bootstrap.Modal(document.getElementById('imageModal'));
            modal.show();
        }
    </script>
    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
                preview.style.display = 'none';
            }
        }
    </script>

    @include('sweetalert::alert')
@endsection
