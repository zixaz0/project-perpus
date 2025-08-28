<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        return view('admin.management_kasir', compact('kasir', 'title'));
    }

    public function kasirCreate()
    {
        return view('admin.tambah_kasir');
    }

    public function kasirStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'kasir',
        ]);

        return redirect()->route('kasir.index')->with('success', 'Kasir berhasil ditambahkan!');
    }

    // 🔹 Edit Kasir
    public function kasirEdit($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        return view('admin.edit_kasir', compact('kasir'));
    }

    // 🔹 Update Kasir
    public function kasirUpdate(Request $request, $id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $kasir->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $kasir->name  = $request->name;
        $kasir->email = $request->email;

        if ($request->filled('password')) {
            $kasir->password = Hash::make($request->password);
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