<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OwnerPegawaiController extends Controller
{
    // 🔹 List Pegawai
    public function index(Request $request)
    {
        $title = 'Manajemen Pegawai';

        $query = User::whereIn('role', ['admin', 'kasir']); // Pegawai selain owner

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        // Admin duluan
        $query->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END");

        $pegawai = $query->latest()->paginate(10);

        return view('owner.pegawai.index', compact('pegawai', 'title'));
    }

    // 🔹 Form Tambah Pegawai
    public function create()
    {
        return view('owner.pegawai.create');
    }

    // 🔹 Simpan Pegawai
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|same:password|min:6',
            'role'     => 'required|in:admin,kasir',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ],
        [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'password.required' => 'Password harus diisi.',
            'password_confirmation.required' => 'Konfirmasi Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password_confirmation.min' => 'Konfirmasi Password minimal 6 karakter.',
            'password.same' => 'Password dan Konfirmasi Password harus sama.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pegawai', 'public');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'foto'     => $fotoPath,
        ]);

        return redirect()->route('owner.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    // 🔹 Edit Pegawai
    public function edit($id)
    {
        $pegawai = User::whereIn('role', ['admin', 'kasir'])->findOrFail($id);
        return view('owner.pegawai.edit', compact('pegawai'));
    }

    // 🔹 Update Pegawai
    public function update(Request $request, $id)
    {
        $pegawai = User::whereIn('role', ['admin', 'kasir'])->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pegawai->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,kasir',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pegawai->name  = $request->name;
        $pegawai->email = $request->email;
        $pegawai->role  = $request->role;

        if ($request->filled('password')) {
            $pegawai->password = Hash::make($request->password);
        }

        // Handle Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama kalau ada
            if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $pegawai->foto = $request->file('foto')->store('pegawai', 'public');
        }

        $pegawai->save();

        return redirect()->route('owner.pegawai.index')->with('success', 'Pegawai berhasil diupdate!');
    }

    // 🔹 Hapus Pegawai
    public function destroy($id)
    {
        $pegawai = User::whereIn('role', ['admin', 'kasir'])->findOrFail($id);

        // Hapus foto kalau ada
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('owner.pegawai.index')->with('success', 'Pegawai berhasil dihapus!');
    }
}