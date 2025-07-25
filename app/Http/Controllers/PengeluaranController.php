<?php

namespace App\Http\Controllers;

use id;
use App\Models\ActivityLog;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dan jumlah item per halaman
        $sumber = $request->input('sumber'); // sesuai dengan kolom 'sumber'
        $paginate = $request->input('itemsPerPage', 5); // default 5

        // Query awal pendapatan
        $query = Pengeluaran::query();

        // Filter pencarian berdasarkan 'sumber' atau 'id' jika tersedia
        if (!empty($sumber)) {
            $query->where(function ($q) use ($sumber) {
                $q->where('sumber', 'LIKE', '%' . $sumber . '%')
                    ->orWhere('id', 'LIKE', '%' . $sumber . '%');
            });
        }
        // Eksekusi query dengan paginasi
        $pengeluarans = $query->paginate($paginate)->withQueryString();

        // Kirim data ke view
        return view('admin.pengeluaran.index', compact('pengeluarans'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $data = $request->validate([
            'tanggal'           => 'required|date',
            'sumber'            => 'required|string|max:255',
            'jumlah'            => 'required|string',
            'bukti_pengeluaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Konversi jumlah dari format rupiah ke angka
        $data['jumlah'] = (int) preg_replace('/[^\d]/', '', $data['jumlah']);

        // 3. Upload bukti (jika ada)
        if ($request->hasFile('bukti_pengeluaran')) {
            $data['bukti_pengeluaran'] = $request
                ->file('bukti_pengeluaran')
                ->store('images/pengeluaran', 'public');
        }

        // 4. Simpan ke database
        $pengeluaran = Pengeluaran::create($data);

        // 5. Jika user adalah karyawan (role_id == 2), simpan ke activity_logs
        if (Auth::user()->role_id == 2) {
            $userName = Auth::user()->name;
            $tanggalLog = now()->translatedFormat('d F Y');

            $deskripsi = "$userName menambahkan data pengeluaran.\n";
            $deskripsi .= "- ID Pengeluaran: {$pengeluaran->id}\n";
            $deskripsi .= "- Tanggal: {$pengeluaran->tanggal}\n";
            $deskripsi .= "- Sumber: {$pengeluaran->sumber}\n";
            $deskripsi .= "- Jumlah: Rp" . number_format($pengeluaran->jumlah, 0, ',', '.') . "\n";
            $deskripsi .= "- Tanggal Aksi: $tanggalLog";

            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'Tambah Pengeluaran',
                'description' => $deskripsi,
            ]);
        }

        // 6. Tampilkan alert
        Alert::success('Sukses', 'Data pengeluaran berhasil ditambahkan');

        return back();
    }

    public function edit($id)
    {
        $pengeluarans = Pengeluaran::find($id);
        // Validasi apakah data ditemukan
        if (!$pengeluarans) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return view('admin.pengeluaran.edit', compact('pengeluarans'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'tanggal'           => 'required|date',
            'sumber'            => 'required|string|max:255',
            'jumlah'            => 'required|string',
            'bukti_pengeluaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data lama
        $pengeluaran = Pengeluaran::findOrFail($id);
        $old = $pengeluaran->getOriginal();

        // Format jumlah ke angka (hilangkan Rp, titik, spasi)
        $validated['jumlah'] = floatval(str_replace(['Rp', '.', ' '], '', $validated['jumlah']));

        // Proses upload file jika ada
        if ($request->hasFile('bukti_pengeluaran')) {
            if ($pengeluaran->bukti_pengeluaran && Storage::disk('public')->exists($pengeluaran->bukti_pengeluaran)) {
                Storage::disk('public')->delete($pengeluaran->bukti_pengeluaran);
            }

            $path = $request->file('bukti_pengeluaran')->store('images', 'public');
            $pengeluaran->bukti_pengeluaran = $path;
        }

        // Update field lainnya
        $pengeluaran->tanggal = $validated['tanggal'];
        $pengeluaran->sumber  = $validated['sumber'];
        $pengeluaran->jumlah  = $validated['jumlah'];

        // Simpan perubahan
        $pengeluaran->save();

        // Catat log jika role admin/operator
        if (Auth::user()->role_id == 2) {
            $changedFields = [];

            if ($old['tanggal'] != $pengeluaran->tanggal) {
                $changedFields[] = 'Tanggal: ' . $pengeluaran->tanggal;
            }

            if ($old['sumber'] != $pengeluaran->sumber) {
                $changedFields[] = 'Sumber: ' . $pengeluaran->sumber;
            }

            if ($old['jumlah'] != $pengeluaran->jumlah) {
                $changedFields[] = 'Jumlah: Rp' . number_format($pengeluaran->jumlah, 0, ',', '.');
            }

            if ($request->hasFile('bukti_pengeluaran')) {
                $changedFields[] = 'Bukti Pengeluaran: Diperbarui';
            }

            if (!empty($changedFields)) {
                $tanggal = now()->translatedFormat('d F Y'); // Contoh: 21 Juli 2025

                $description = Auth::user()->name . " melakukan pengeditan data pengeluaran.\n";
                $description .= "Perubahan yang dilakukan:\n";
                foreach ($changedFields as $field) {
                    $description .= "- " . $field . "\n";
                }
                $description .= "ID Pengeluaran: {$pengeluaran->id}\n";
                $description .= "Tanggal Aksi: {$tanggal}";

                ActivityLog::create([
                    'user_id'     => Auth::id(),
                    'action'      => 'Edit Pengeluaran',
                    'description' => $description,
                ]);
            }
        }

        // Feedback
        Alert::success('Sukses', 'Data berhasil diperbarui');
        return redirect()->route('pengeluaran.index');
    }

    public function destroy($id)
    {
        $pengeluaran = Pengeluaran::where('id', $id)->first();

        if (!$pengeluaran) {
            Alert::error('Gagal', 'Data tidak ditemukan.');
            return redirect()->route('pengeluaran.index');
        }

        // Simpan informasi sebelum dihapus untuk log
        $deskripsiPengeluaran = "ID: {$pengeluaran->id}, Keterangan: {$pengeluaran->keterangan}, Nominal: Rp" . number_format($pengeluaran->jumlah, 0, ',', '.');

        // Hapus data
        $pengeluaran->delete();

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action' => 'Hapus Pengeluaran',
            'description' => "Menghapus data pengeluaran: $deskripsiPengeluaran",
        ]);

        Alert::success('Success', 'Data berhasil dihapus.');
        return redirect()->route('pengeluaran.index');
    }
}
