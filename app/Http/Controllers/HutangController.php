<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dan jumlah item per halaman
        $nama_pihak = $request->input('nama_pihak'); // disesuaikan dengan kolom 'nama'
        $paginate = $request->input('itemsPerPage', 5); // default 5

        // Query awal karyawan
        $query = Hutang::query();

        // Filter pencarian berdasarkan 'nama' jika tersedia
        if (!empty($nama_pihak)) {
            $query->where('nama_pihak', 'LIKE', '%' . $nama_pihak . '%');
        }

        // Eksekusi query dengan paginasi
        $hutangs = $query->paginate($paginate)->withQueryString();

        // Return ke view
        return view('admin.hutang.index', compact('hutangs'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            'tanggal'       => 'required|date',
            'nama_pihak'    => 'required|string|max:255',
            'tipe'          => 'required|in:Hutang,Piutang',
            'jumlah'        => 'required|string', // akan diformat dulu
            'jatuh_tempo'   => 'required|date|after_or_equal:tanggal',
            'status'        => 'required|in:Lunas,Belum Lunas',
        ]);

        // Bersihkan dan ubah format jumlah ke decimal
        $data['jumlah'] = floatval(str_replace(['Rp', '.', ' '], '', $data['jumlah']));

        // Simpan data ke database
        Hutang::create($data);

        // Feedback sukses
        Alert::success('Sukses', 'Data hutang/piutang berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $hutangs = Hutang::find($id);
        // Validasi apakah data ditemukan
        if (!$hutangs) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return view('admin.hutang.edit', compact('hutangs'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal'      => 'required|date',
            'nama_pihak'   => 'required|string|max:255',
            'tipe'         => 'required|in:hutang,piutang',
            'jumlah'       => 'required|string', // sementara string, nanti diubah ke angka
            'jatuh_tempo'  => 'nullable|date|after_or_equal:tanggal',
            'status'       => 'required|in:belum_lunas,lunas',
        ]);

        // Format jumlah ke angka (hilangkan Rp, titik, spasi)
        $validated['jumlah'] = floatval(str_replace(['Rp', '.', ' '], '', $validated['jumlah']));

        // Ambil data lama
        $hutang = Hutang::findOrFail($id);

        // Update kolom satu per satu
        $hutang->tanggal      = $validated['tanggal'];
        $hutang->nama_pihak   = $validated['nama_pihak'];
        $hutang->tipe         = $validated['tipe'];
        $hutang->jumlah       = $validated['jumlah'];
        $hutang->jatuh_tempo  = $validated['jatuh_tempo'];
        $hutang->status       = $validated['status'];

        // Simpan
        $hutang->save();

        // Notifikasi sukses
        Alert::success('Sukses', 'Data berhasil diperbarui');
        return redirect()->route('hutang.index');
    }

    public function destroy($id)
    {

        $hutangs = Hutang::where('id', $id)->first();
        $hutangs->delete();

        Alert::success('Success', 'Data berhasil di Hapus');
        return redirect()->route('hutang.index');
    }
}
