<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function kasirIndex(Request $request)
    {
        $title = 'Management Kasir';
        $query = User::where('role', 'kasir');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        $kasir = $query->latest()->paginate(10);

        return view('admin.kasir.index', compact('kasir', 'title'));
    }

    public function kasirCreate()
    {
        return view('admin.kasir.create');
    }

    public function kasirStore(Request $request)
    {
        $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|same:password|min:6',
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
            ]
        );

        // handle upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_users', 'public');
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'kasir',
            'foto'     => $fotoPath,
        ]);

        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil ditambahkan!');
    }

    // 🔹 Edit Kasir
    public function kasirEdit($id)
    {
        $title = "Edit Kasir";
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        return view('admin.kasir.edit', compact('kasir', 'title'));
    }

    // 🔹 Update Kasir
    public function kasirUpdate(Request $request, $id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $kasir->id,
            'password' => 'nullable|string|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ],
    [
        'name.required' => 'Nama harus diisi.',
        'email.required' => 'Email harus diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.same' => 'Password dan Konfirmasi Password harus sama.',
    ]);

        $kasir->name  = $request->name;
        $kasir->email = $request->email;

        if ($request->filled('password')) {
            $kasir->password = Hash::make($request->password);
        }

        // ✅ Update foto kalau ada upload baru
        if ($request->hasFile('foto')) {
            // hapus foto lama kalau ada
            if ($kasir->foto && Storage::disk('public')->exists($kasir->foto)) {
                Storage::disk('public')->delete($kasir->foto);
            }
            $kasir->foto = $request->file('foto')->store('foto_users', 'public');
        }

        $kasir->save();

        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil diupdate!');
    }


    public function kasirDestroy($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        $kasir->delete();

        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil dihapus!');
    }
}
