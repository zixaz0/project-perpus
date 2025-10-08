@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.logs.index') }}" class="text-blue-600 hover:underline">Log Aktivitas</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">Detail Log Aktivitas</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Tombol Kembali -->
    <a href="{{ route('admin.logs.index') }}" 
       class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium mb-6 transition-colors">
        <i class="fa fa-arrow-left mr-2"></i> Kembali ke Log Aktivitas
    </a>

    <!-- Card Utama -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="bg-indigo-600 text-white px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg sm:text-xl font-semibold tracking-wide">
                <i class="fa fa-info-circle mr-2"></i> Detail Log Aktivitas
            </h2>
            <span class="text-sm bg-indigo-500 px-3 py-1 rounded-lg shadow-sm">
                ID: {{ $log->id }}
            </span>
        </div>

        <!-- Isi Detail -->
        <div class="p-6 space-y-6">
            <!-- Informasi Utama -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm sm:text-base text-gray-700">
                <div class="flex items-center gap-2">
                    <i class="fa fa-user text-indigo-500 w-5 text-center"></i>
                    <span><strong>User:</strong> {{ $log->user->name ?? 'Sistem' }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa fa-bolt text-yellow-500 w-5 text-center"></i>
                    <span><strong>Aksi:</strong> {{ strtoupper($log->activity) }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa fa-database text-green-600 w-5 text-center"></i>
                    <span><strong>Model:</strong> {{ $log->subject_type ?? '-' }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa fa-clock text-gray-600 w-5 text-center"></i>
                    <span><strong>Waktu:</strong> {{ $log->created_at->format('d M Y, H:i:s') }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa fa-link text-blue-600 w-5 text-center"></i>
                    <span><strong>URL:</strong> {{ $log->url ?? '-' }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa fa-globe text-gray-500 w-5 text-center"></i>
                    <span><strong>IP Address:</strong> {{ $log->ip_address ?? '-' }}</span>
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fa fa-align-left text-indigo-500"></i> Deskripsi Aktivitas
                </h3>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-gray-700 leading-relaxed shadow-sm">
                    {{ $log->description ?? '-' }}
                </div>
            </div>

            <!-- Old & New Values -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <i class="fa fa-history text-red-500"></i> Data Lama
                    </h3>
                    <pre class="bg-red-50 text-red-700 border border-red-200 rounded-xl p-3 text-sm overflow-x-auto">
{{ json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?? '-' }}
                    </pre>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <i class="fa fa-sync text-green-600"></i> Data Baru
                    </h3>
                    <pre class="bg-green-50 text-green-700 border border-green-200 rounded-xl p-3 text-sm overflow-x-auto">
{{ json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?? '-' }}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection