@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.stok_harga.index') }}" class="text-blue-600 hover:underline">Manajemen Stok &
                    Harga</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.stok_harga.edit', $stok_harga->id) }}" class="text-blue-600 hover:underline">Edit
                    Stok & Harga</a>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Edit Stok & Harga</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form action="{{ route('admin.stok_harga.update', $stok_harga->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Pilih Buku (readonly) -->
                <div>
                    <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-2">Buku</label>

                    <!-- tampilkan buku tapi disable biar tidak bisa diubah -->
                    <select id="buku_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 bg-gray-100 cursor-not-allowed"
                        disabled>
                        @foreach ($buku as $b)
                            <option value="{{ $b->id }}" {{ $stok_harga->buku_id == $b->id ? 'selected' : '' }}>
                                {{ $b->judul_buku }}
                            </option>
                        @endforeach
                    </select>

                    <!-- hidden supaya tetap terkirim ke server -->
                    <input type="hidden" name="buku_id" value="{{ $stok_harga->buku_id }}">
                </div>


                <!-- Input Stok -->
                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                    <input type="number" id="stok" name="stok" value="{{ old('stok', $stok_harga->stok) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        min="0" required>
                </div>

                <!-- Input Harga -->
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" id="harga" name="harga" value="{{ old('harga', $stok_harga->harga) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        min="0" required>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('admin.stok_harga.index') }}"
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="cursor-pointer px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition font-medium">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
