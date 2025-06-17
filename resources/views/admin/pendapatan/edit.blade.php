@extends('layouts.admin')
@section('title', 'Edit')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold d-flex align-items-center my-4">


            <i class="bx bx-user me-2 text-primary" style="font-size: 1.5rem;"></i>
            <span class="text-muted fw-light me-1"></span> Pendapatan
        </h4>
        <div class="card">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                <a class="mx-4 my-4" href="{{ route('pendapatan.index') }}">
                    <button class="btn btn-outline-primary border-1 rounded-1 px-3 py-1 d-flex align-items-center"
                        data-bs-toggle="tooltip" title="Kembali">
                        <i class="bi bi-arrow-left fs-5 mx-1"></i>
                        <span class="fw-normal">Kembali</span>
                    </button>
                </a>
            </div>


            <div class="card-body">
                <div class="text-nowrap">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ url('pendapatan-edit/' . $pendapatans->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row ">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" id="tanggal"
                                        value="{{ $pendapatans->tanggal }}">
                                </div>

                                <div class="mb-3">
                                    <label for="sumber" class="form-label">Sumber Pendapatan</label>

                                    <input type="text" name="sumber" class="form-control" id="sumber"
                                        value="{{ $pendapatans->sumber }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="text" name="jumlah" class="form-control" id="jumlah"
                                        placeholder="Rp." value="{{ number_format($pendapatans->jumlah, 0, ',', '.') }}"
                                        oninput="formatRupiah(this)">
                                </div>

                                <div class="mb-3">
                                    <label for="bukti_pendapatan" class="form-label">Bukti Transaksi</label>
                                    <input type="file" name="bukti_pendapatan" class="form-control"
                                        id="imageGallerypendapatan" accept="image/*"
                                        onchange="previewImage(this, 'previewGallerypendapatan')">


                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row justify-content-center">
                                    <!-- Gambar Lama (jika ada) -->
                                    @if ($pendapatans->bukti_pendapatan)
                                        <img id="previewGallerypendapatan"
                                            src="{{ asset('storage/' . $pendapatans->bukti_pendapatan) }}"
                                            class="img-fluid mt-3"
                                            style="max-width: 200px; border: 1px solid #ccc; padding: 5px; border-radius: 6px;"
                                            alt="Pratinjau Gambar">
                                    @else
                                        <img id="previewGallerypendapatan" class="img-fluid mt-3"
                                            style="max-width: 200px; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 6px;"
                                            alt="Pratinjau Gambar">
                                    @endif
                                </div>
                            </div>
                            <div class="text-end btn-page mb-0 mt-4">
                                <a href="{{ route('pendapatan.index') }}" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>


    </div>


    @include('sweetalert::alert')
@endsection
