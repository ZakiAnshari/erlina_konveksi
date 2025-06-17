@extends('layouts.admin')
@section('title', 'Edit')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold d-flex align-items-center my-4">


            <i class="bx bx-user me-2 text-primary" style="font-size: 1.5rem;"></i>
            <span class="text-muted fw-light me-1"></span> Hutang
        </h4>
        <div class="card">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                <a class="mx-4 my-4" href="{{ route('hutang.index') }}">
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
                    <form action="{{ url('hutang-edit/' . $hutangs->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal Dicatat</label>
                                    <input type="date" name="tanggal" class="form-control" id="tanggal"
                                        value="{{ $hutangs->tanggal }}">
                                </div>

                                <div class="mb-3">
                                    <label for="nama_pihak" class="form-label">Nama Pihak</label>
                                    <input type="text" name="nama_pihak" class="form-control" id="nama_pihak"
                                        value="{{ $hutangs->nama_pihak }}">
                                </div>

                                <div class="mb-3">
                                    <label for="tipe" class="form-label">Tipe</label>
                                    <select name="tipe" class="form-select" required>
                                        <option value="hutang"
                                            {{ old('tipe', $hutangs->tipe) == 'hutang' ? 'selected' : '' }}>Hutang</option>
                                        <option value="piutang"
                                            {{ old('tipe', $hutangs->tipe) == 'piutang' ? 'selected' : '' }}>Piutang</option>
                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="text" name="jumlah" class="form-control" id="jumlah"
                                        placeholder="Rp." value="{{ number_format($hutangs->jumlah, 0, ',', '.') }}"
                                        oninput="formatRupiah(this)">
                                </div>

                                <div class="mb-3">
                                    <label for="jatuh_tempo" class="form-label">Jatuh Tempo</label>
                                    <input type="date" name="jatuh_tempo" class="form-control" id="jatuh_tempo"
                                        value="{{ $hutangs->jatuh_tempo }}">
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="belum_lunas"
                                            {{ old('status', $hutangs->status) == 'belum_lunas' ? 'selected' : '' }}>
                                            Belum Lunas</option>
                                        <option value="lunas"
                                            {{ old('status', $hutangs->status) == 'lunas' ? 'selected' : '' }}>Lunas
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end btn-page mb-0 mt-4">
                                <a href="{{ route('hutang.index') }}" class="btn btn-outline-secondary">Batal</a>
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
