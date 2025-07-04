<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dan jumlah item per halaman
        $nama = $request->input('nama'); // disesuaikan dengan kolom 'nama'
        $paginate = $request->input('itemsPerPage', 5); // default 5

        // Query awal karyawan
        $query = Karyawan::query();

        if (!empty($nama)) {
            $query->where(function ($q) use ($nama) {
                $q->where('nama', 'LIKE', '%' . $nama . '%');

                // Hanya cari berdasarkan ID jika inputnya angka
                if (is_numeric($nama)) {
                    $q->orWhere('id', $nama); // exact match untuk ID
                }
            });
        }



        // Eksekusi query dengan paginasi
        $karyawan = $query->paginate($paginate)->withQueryString();

        // Return ke view
        return view('admin.karyawan.index', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:100',
            'kontak' => 'required|numeric|digits_between:10,15',
            'umur' => 'required|integer|min:18|max:65',
            'alamat' => 'required|string|max:255',
        ]);

        Karyawan::create($data);

        Alert::success('Sukses', 'Data berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $karyawan = Karyawan::find($id);
        // Validasi apakah data ditemukan
        if (!$karyawan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama'   => 'required|string|max:255',
            'posisi' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:20',
            'umur'   => 'required|integer|min:18|max:65',
            'alamat' => 'required|string|max:255',
        ]);

        $item = Karyawan::findOrFail($id);
        $item->update($data);

        Alert::success('Sukses', 'Data berhasil diperbarui');
        return redirect()->route('karyawan.index');
    }

    public function destroy($id)
    {

        $karyawan = Karyawan::where('id', $id)->first();
        $karyawan->delete();

        Alert::success('Success', 'Data berhasil di Hapus');
        return redirect()->route('karyawan.index');
    }
}
