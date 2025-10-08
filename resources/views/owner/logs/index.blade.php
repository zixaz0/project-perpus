@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('owner.dashboard') }}" class="text-indigo-600 hover:underline font-medium">Dashboard</a>
            </li>
            <li class="text-gray-400">/</li>
            <li>
                <a href="" class="text-indigo-600 hover:underline font-medium">Log Aktivitas</a>
            </li>
           
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                Log Aktivitas Semua Pengguna
            </h1>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-1">
            <!-- Filter User -->
            <form action="{{ route('owner.logs.index') }}" method="GET" class="mb-5 flex items-center gap-2">
                <select name="user_id" class="cursor-pointer px-3 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="">Semua Pengguna</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm shadow-sm">
                    <i class="fa fa-filter mr-1"></i> Filter
                </button>
            </form>

            <!-- Search -->
            <form action="{{ route('owner.logs.index') }}" method="GET" class="flex items-center gap-2 mb-5">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aksi / model / user..."
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm w-64">
                <button type="submit"
                    class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm shadow-sm">
                    <i class="fa fa-search"></i> Cari
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-indigo-600 text-white text-xs uppercase tracking-wider">
                            <th class="px-4 py-3 font-semibold">No</th>
                            <th class="px-4 py-3 font-semibold">User</th>
                            <th class="px-4 py-3 font-semibold">Role</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                            <th class="px-4 py-3 font-semibold">Model</th>
                            <th class="px-4 py-3 font-semibold">Deskripsi</th>
                            <th class="px-4 py-3 font-semibold">Waktu</th>
                            <th class="px-4 py-3 font-semibold text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $index => $log)
                            <tr class="border-b hover:bg-indigo-50 transition duration-150 ease-in-out">
                                <td class="px-4 py-3 text-gray-600">{{ $logs->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $log->user->name ?? 'Sistem' }}</td>
                                <td class="px-4 py-3 text-gray-700 capitalize">{{ $log->user->role ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $act = strtolower($log->activity);
                                        $color = str_contains($act, 'buat')
                                            ? 'bg-green-100 text-green-700'
                                            : (str_contains($act, 'ubah')
                                                ? 'bg-yellow-100 text-yellow-700'
                                                : (str_contains($act, 'hapus')
                                                    ? 'bg-red-100 text-red-700'
                                                    : 'bg-gray-100 text-gray-700'));
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-md {{ $color }}">
                                        {{ strtoupper($log->activity) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ class_basename($log->subject_type) ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $log->description }}">
                                    {{ $log->description ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('owner.logs.show', $log->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition shadow-sm"
                                        title="Lihat Detail">
                                        <i class="fa fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-6 text-gray-500">
                                    <i class="fa fa-info-circle"></i> Tidak ada aktivitas tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t bg-gray-50 rounded-b-lg">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
