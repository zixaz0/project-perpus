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
                <a href="{{ route('admin.stok_harga.tambah_stok_form', $stok_harga->id) }}" class="text-blue-600 hover:underline">Tambah
                    Stok</a>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Stok Buku</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form action="{{ route('admin.stok_harga.tambah_stok', $stok_harga->id) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Info Buku -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buku</label>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border">
                        <img src="{{ asset('storage/' . $stok_harga->buku->cover_buku) }}" 
                             alt="cover" 
                             class="w-16 h-20 object-cover rounded shadow">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $stok_harga->buku->judul_buku }}</p>
                            <p class="text-sm text-gray-600">{{ $stok_harga->buku->penulis }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stok Saat Ini -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Saat Ini</label>
                    <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50">
                        <span class="text-2xl font-bold text-indigo-600">{{ $stok_harga->stok }}</span>
                        <span class="text-gray-600 ml-2">unit</span>
                    </div>
                </div>

                <!-- Input Jumlah Tambahan Stok -->
                <div>
                    <label for="jumlah_tambah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok yang Ditambahkan</label>
                    <input type="number" id="jumlah_tambah" name="jumlah_tambah" value="{{ old('jumlah_tambah') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Masukkan jumlah stok yang akan ditambahkan..." min="1" required>
                    <p class="text-xs text-gray-500 mt-1">Stok akan ditambahkan ke jumlah yang sudah ada</p>
                </div>

                <!-- Preview Total Stok -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Total Stok Setelah Penambahan:</p>
                    <p class="text-2xl font-bold text-green-600">
                        <span id="preview-total">{{ $stok_harga->stok }}</span>
                        <span class="text-base text-gray-600 ml-2">unit</span>
                    </p>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('admin.stok_harga.index') }}"
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="cursor-pointer px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition font-medium">
                        Tambah Stok
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update preview total stok saat input berubah
        document.getElementById('jumlah_tambah').addEventListener('input', function() {
            const stokSekarang = {{ $stok_harga->stok }};
            const jumlahTambah = parseInt(this.value) || 0;
            const total = stokSekarang + jumlahTambah;
            document.getElementById('preview-total').textContent = total;
        });
    </script>
@endsection