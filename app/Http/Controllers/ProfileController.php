<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Hapus foto lama kalau ada
        if ($user->foto && Storage::exists('public/'.$user->foto)) {
            Storage::delete('public/'.$user->foto);
        }

        // Simpan foto baru
        $path = $request->file('foto')->store('profile', 'public');
        $user->foto = $path;
        $user->save();

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
