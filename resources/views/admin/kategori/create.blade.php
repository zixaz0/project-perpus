@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.kategori.index') }}" class="text-blue-600 hover:underline">Manajemen Kategori</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.kategori.create') }}" class="text-blue-600 hover:underline">Tambah Kategori</a>
            </li>
        </ol>
    </nav>
@endsection

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Kategori</h1>

    <div class="bg-white p-8 rounded-2xl shadow-lg border">
        <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" id="kategori" name="kategori"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Masukkan nama kategori..." required>
            </div>

            <div>
                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                <input type="text" id="jenis" name="jenis"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Masukkan jenis kategori..." required>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.kategori.index') }}"
                    class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="cursor-pointer px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection