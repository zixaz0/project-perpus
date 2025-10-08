<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    // 🔹 INDEX (List Semua Log)
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        $user = Auth::user();
    
        // Role logic
        if ($user->role === 'owner') {
            // Owner bisa lihat semua log
            $logs = ActivityLog::with('user')
                ->when($request->q, function ($query, $q) {
                    $query->where('activity', 'like', "%$q%")
                          ->orWhere('subject_type', 'like', "%$q%");
                })
                ->when($request->user_id, function ($query, $userId) {
                    $query->where('user_id', $userId);
                })
                ->latest()
                ->paginate(10);
    
            // 🔹 Tambahkan daftar user untuk filter dropdown
            $users = \App\Models\User::select('id', 'name', 'role')->orderBy('name')->get();
    
            // Return ke view owner + kirim logs & users
            return view('owner.logs.index', compact('logs', 'users'));
        }
    
        // Admin & Kasir hanya lihat log milik mereka sendiri
        $logs = ActivityLog::with('user')
            ->where('user_id', $user->id)
            ->when($request->q, function ($query, $q) {
                $query->where('activity', 'like', "%$q%")
                      ->orWhere('subject_type', 'like', "%$q%");
            })
            ->latest()
            ->paginate(10);
    
        // Tentukan view sesuai role
        $view = match ($user->role) {
            'kasir' => 'kasir.logs.index',
            default => 'admin.logs.index',
        };
    
        // Return view + logs
        return view($view, compact('logs'));
    }    

    // 🔹 SHOW (Detail Log)
    public function show(ActivityLog $log)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Batasi akses: selain owner, hanya bisa lihat log miliknya
        if ($user->role !== 'owner' && $log->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat log ini.');
        }

        // Tentukan view sesuai role
        $view = match ($user->role) {
            'kasir' => 'kasir.logs.show',
            'owner' => 'owner.logs.show',
            default => 'admin.logs.show',
        };

        // Pastikan view ada
        $view = view()->exists($view) ? $view : 'admin.logs.show';

        return view($view, compact('log'));
    }
}