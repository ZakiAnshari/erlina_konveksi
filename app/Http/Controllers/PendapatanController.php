<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use App\Models\ActivityLog;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Simpan ke database
        $pendapatan = Pendapatan::create($data);

        // Catat riwayat jika user adalah karyawan (role_id == 2), atau sesuaikan jika semua role dicatat
        if (Auth::user()->role_id == 2) {
            $tanggalLog = now()->translatedFormat('d F Y'); // e.g., 25 Juli 2025

            $description = Auth::user()->name . " menambahkan data pendapatan.\n";
            $description .= "- ID Pendapatan: {$pendapatan->id}\n";
            $description .= "- Tanggal: {$pendapatan->tanggal}\n";
            $description .= "- Sumber: {$pendapatan->sumber}\n";
            $description .= "- Jumlah: Rp" . number_format($pendapatan->jumlah, 0, ',', '.') . "\n";
            $description .= "Tanggal Aksi: {$tanggalLog}";

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'Tambah Pendapatan',
                'description' => $description,
            ]);
        }

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
        $old = $pendapatan->getOriginal();

        // Format jumlah ke angka
        $validated['jumlah'] = floatval(str_replace(['Rp', '.', ' '], '', $validated['jumlah']));

        // Proses upload file jika ada
        if ($request->hasFile('bukti_pendapatan')) {
            if ($pendapatan->bukti_pendapatan && Storage::disk('public')->exists($pendapatan->bukti_pendapatan)) {
                Storage::disk('public')->delete($pendapatan->bukti_pendapatan);
            }

            $path = $request->file('bukti_pendapatan')->store('images', 'public');
            $pendapatan->bukti_pendapatan = $path;
        }

        // Update data
        $pendapatan->tanggal = $validated['tanggal'];
        $pendapatan->sumber  = $validated['sumber'];
        $pendapatan->jumlah  = $validated['jumlah'];
        $pendapatan->save();

        // Catat log jika role adalah karyawan/operator (role_id == 2)
        if (Auth::user()->role_id == 2) {
            $changedFields = [];

            if ($old['tanggal'] != $pendapatan->tanggal) {
                $changedFields[] = 'Tanggal: ' . $pendapatan->tanggal;
            }

            if ($old['sumber'] != $pendapatan->sumber) {
                $changedFields[] = 'Sumber: ' . $pendapatan->sumber;
            }

            if ($old['jumlah'] != $pendapatan->jumlah) {
                $changedFields[] = 'Jumlah: Rp' . number_format($pendapatan->jumlah, 0, ',', '.');
            }

            if ($request->hasFile('bukti_pendapatan')) {
                $changedFields[] = 'Bukti Pendapatan: Diperbarui';
            }

            if (!empty($changedFields)) {
                $tanggal = now()->translatedFormat('d F Y');

                $description = Auth::user()->name . " melakukan pengeditan data pendapatan.\n";
                $description .= "Perubahan yang dilakukan:\n";
                foreach ($changedFields as $field) {
                    $description .= "- " . $field . "\n";
                }
                $description .= "ID Pendapatan: {$pendapatan->id}\n";
                $description .= "Tanggal Aksi: {$tanggal}";

                ActivityLog::create([
                    'user_id'     => Auth::id(),
                    'action'      => 'Edit Pendapatan',
                    'description' => $description,
                ]);
            }
        }

        Alert::success('Sukses', 'Data berhasil diperbarui');
        return redirect()->route('pendapatan.index');
    }


    public function destroy($id)
    {
        $pendapatan = Pendapatan::findOrFail($id);

        // Simpan data lama sebelum dihapus
        $deletedInfo = [
            'ID Pendapatan' => $pendapatan->id,
            'Tanggal'       => $pendapatan->tanggal,
            'Sumber'        => $pendapatan->sumber,
            'Jumlah'        => 'Rp' . number_format($pendapatan->jumlah, 0, ',', '.'),
        ];

        // Hapus bukti jika ada
        if ($pendapatan->bukti_pendapatan && Storage::disk('public')->exists($pendapatan->bukti_pendapatan)) {
            Storage::disk('public')->delete($pendapatan->bukti_pendapatan);
        }

        // Hapus data dari database
        $pendapatan->delete();

        // Catat log jika user adalah karyawan
        if (Auth::user()->role_id == 2) {
            $tanggal = now()->translatedFormat('d F Y'); // Contoh: 25 Juli 2025

            $description = Auth::user()->name . " menghapus data pendapatan.\n";
            foreach ($deletedInfo as $key => $value) {
                $description .= "- {$key}: {$value}\n";
            }
            $description .= "Tanggal Aksi: {$tanggal}";

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'Hapus Pendapatan',
                'description' => $description,
            ]);
        }

        Alert::success('Success', 'Data berhasil dihapus');
        return redirect()->route('pendapatan.index');
    }
}
