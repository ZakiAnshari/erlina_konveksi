<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class PendapatanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dan jumlah item per halaman
        $sumber = $request->input('sumber'); // sesuai dengan kolom 'sumber'
        $paginate = $request->input('itemsPerPage', 5); // default 5

        // Query awal pendapatan
        $query = Pendapatan::query();

        // Filter pencarian berdasarkan 'sumber' jika tersedia
        if (!empty($sumber)) {
            $query->where(function ($q) use ($sumber) {
                $q->where('sumber', 'LIKE', '%' . $sumber . '%')
                    ->orWhere('id', 'LIKE', '%' . $sumber . '%');
            });
        }


        // Eksekusi query dengan paginasi
        $pendapatans = $query->paginate($paginate)->withQueryString();

        // Kirim data ke view
        return view('admin.pendapatan.index', compact('pendapatans'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'sumber'  => 'required|string|max:255',
            'jumlah'  => 'required|string',
            'bukti_pendapatan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Bersihkan nilai jumlah dari format rupiah
        $data['jumlah'] = preg_replace('/[^\d]/', '', $data['jumlah']);

        // Proses upload gambar jika ada
        if ($request->hasFile('bukti_pendapatan')) {
            $data['bukti_pendapatan'] = $request->file('bukti_pendapatan')->store('images', 'public');
        }

        Pendapatan::create($data);

        Alert::success('Sukses', 'Data pendapatan berhasil ditambahkan');
        return back();
    }


    public function edit($id)
    {
        $pendapatans = Pendapatan::find($id);
        // Validasi apakah data ditemukan
        if (!$pendapatans) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return view('admin.pendapatan.edit', compact('pendapatans'));
    }


    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'tanggal'           => 'required|date',
            'sumber'            => 'required|string|max:255',
            'jumlah'            => 'required|string',
            'bukti_pendapatan'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data lama
        $pendapatan = Pendapatan::findOrFail($id);

        // Format jumlah ke angka (hilangkan Rp, titik, spasi)
        $validated['jumlah'] = floatval(str_replace(['Rp', '.', ' '], '', $validated['jumlah']));

        // Proses upload file jika ada
        if ($request->hasFile('bukti_pendapatan')) {
            // Hapus file lama jika ada
            if ($pendapatan->bukti_pendapatan && Storage::disk('public')->exists($pendapatan->bukti_pendapatan)) {
                Storage::disk('public')->delete($pendapatan->bukti_pendapatan);
            }

            // Simpan file baru
            $path = $request->file('bukti_pendapatan')->store('images', 'public');
            $pendapatan->bukti_pendapatan = $path;
        }

        // Update field lainnya
        $pendapatan->tanggal = $validated['tanggal'];
        $pendapatan->sumber  = $validated['sumber'];
        $pendapatan->jumlah  = $validated['jumlah'];

        // Simpan
        $pendapatan->save();

        // Feedback
        Alert::success('Sukses', 'Data berhasil diperbarui');
        return redirect()->route('pendapatan.index');
    }

    public function destroy($id)
    {

        $pendapatan = Pendapatan::where('id', $id)->first();
        $pendapatan->delete();

        Alert::success('Success', 'Data berhasil di Hapus');
        return redirect()->route('pendapatan.index');
    }
}
