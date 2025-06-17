<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $roles = Roles::all();

        // Ambil input pencarian dan jumlah item per halaman
        $name = $request->input('name');
        $paginate = $request->input('itemsPerPage', 5); // default 5

        // Query awal pengguna
        $query = User::query();

        // Filter pencarian berdasarkan nama jika tersedia
        if (!empty($name)) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        // Eksekusi query dengan paginasi
        $users = $query->paginate($paginate)->withQueryString();

        return view('admin.user.index', compact('roles', 'user', 'users'));
    }

    public function store(Request $request)
    {
        // Validasi data dengan pesan kustom
        $validated = $request->validate([
            'name' => 'required|string|max:255|different:username|different:email',
            'username' => 'required|string|max:255|unique:users,username|different:email|different:name',
            'contact' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|max:255|unique:users,email|different:name|different:username',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
        ]);


        // Simpan data ke database
        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
            'contact' => $validated['contact'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
        ]);

        // Redirect atau beri respon sukses
        Alert::success('Success', 'Data User berhasil ditambahkan');
        return back();
    }

    public function edit($id)
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login
        $roles = $user->role; // Mengambil role pengguna
        $users = User::find($id); // Mengambil data lokasi surfing berdasarkan ID
        // Ambil semua roles
        $roles = Roles::all();
        // Validasi apakah data ditemukan
        if (!$users) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return view('admin.user.edit', compact('users', 'user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Temukan data berdasarkan ID
        $users = User::findOrFail($id);

        // Validasi data yang masuk
        $validatedData = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $id,
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'username'      => 'required|string|max:255|unique:users,username,' . $id,
            'contact'       => 'nullable|string|max:20',
            'role_id'       => 'required|exists:roles,id',
        ]);

        // Perbarui data di database
        $users->update($validatedData);

        // Redirect kembali dengan pesan sukses
        Alert::success('Success', 'Data berhasil diperbarui');
        return redirect()->route('user.index');
    }

    public function show($id)
    {
        $users = User::findOrFail($id);
        return view('admin.user.show', compact('users'));
    }

    public function destroy($id)
    {

        $users = User::where('id', $id)->first();
        $users->delete();

        Alert::success('Success', 'Data berhasil di Hapus');
        return redirect()->route('user.index');
    }
}
