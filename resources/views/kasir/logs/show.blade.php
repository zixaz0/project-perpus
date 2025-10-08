@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <a href="{{ route('kasir.logs.index') }}" 
       class="text-indigo-600 hover:underline mb-4 inline-flex items-center gap-2">
        <i class="fa fa-arrow-left"></i> Kembali ke Log Aktivitas
    </a>

    <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                📄 Detail Log Aktivitas
            </h2>
            <span class="text-sm text-gray-500">
                {{ $log->created_at->format('d M Y, H:i') }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
            <div>
                <strong>User:</strong>
                <p>{{ $log->user->name ?? 'Sistem' }}</p>
            </div>
            <div>
                <strong>Aksi:</strong>
                @php
                    $act = strtolower($log->activity);
                    $color = str_contains($act, 'buat') ? 'bg-green-100 text-green-700'
                        : (str_contains($act, 'ubah') ? 'bg-yellow-100 text-yellow-700'
                        : (str_contains($act, 'hapus') ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700'));
                @endphp
                <p><span class="px-2 py-1 text-xs font-semibold rounded {{ $color }}">
                    {{ ucfirst($log->activity) }}
                </span></p>
            </div>
            <div>
                <strong>Model:</strong>
                <p>{{ class_basename($log->subject_type) ?? '-' }}</p>
            </div>
            <div>
                <strong>ID Data:</strong>
                <p>{{ $log->subject_id ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <strong>Deskripsi:</strong>
            <pre class="bg-gray-100 border border-gray-200 p-4 rounded-lg text-sm text-gray-800 mt-2 whitespace-pre-wrap">
{{ $log->description ?? '-' }}
            </pre>
        </div>
    </div>
</div>
@endsection