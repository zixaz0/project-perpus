@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2 text-gray-400">/</li>
            <li>
                <a href="" class="text-indigo-600 hover:underline">Log Aktivitas</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800"> 
                <i class="fas fa-clipboard-list text-indigo-600"></i>
                Log Aktivitas</h1>

            <form action="{{ route('admin.logs.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aksi / model..."
                    class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm w-56">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md flex items-center gap-2 text-sm">
                    <i class="fa fa-search"></i> Cari
                </button>
            </form>
        </div>

        <!-- Info Role -->
        @if (Auth::user()->role !== 'owner')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-5 rounded-md">
                <p class="text-yellow-700 text-sm">
                    <i class="fa fa-info-circle"></i> Anda hanya dapat melihat log aktivitas akun Anda sendiri.
                </p>
            </div>
        @endif

        <!-- Tabel Log -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-indigo-600 text-white uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                            <th class="px-4 py-3 text-left">Model</th>
                            <th class="px-4 py-3 text-left">Deskripsi</th>
                            <th class="px-4 py-3 text-left">Waktu</th>
                            <th class="px-4 py-3 text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $activity = strtolower($log->activity);
                                        $badgeColor = match (true) {
                                            str_contains($activity, 'buat') || str_contains($activity, 'tambah')
                                                => 'bg-green-100 text-green-700',
                                            str_contains($activity, 'ubah') || str_contains($activity, 'edit')
                                                => 'bg-yellow-100 text-yellow-700',
                                            str_contains($activity, 'hapus') || str_contains($activity, 'delete')
                                                => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $badgeColor }}">
                                        {{ ucfirst($log->activity) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ class_basename($log->subject_type) ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 max-w-md truncate" title="{{ $log->description }}">
                                    {{ $log->description ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.logs.show', $log->id) }}"
                                        class="group relative inline-flex items-center justify-center w-8 h-8 bg-indigo-500 text-white rounded-full hover:bg-indigo-600 transition"
                                        title="Lihat Detail">
                                        <i class="fa fa-eye text-xs"></i>
                                        <span
                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                            Lihat Detail
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500">
                                    <i class="fa fa-info-circle"></i> Tidak ada aktivitas yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t bg-gray-50">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
