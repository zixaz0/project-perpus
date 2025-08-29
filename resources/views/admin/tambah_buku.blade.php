@extends('layouts.app')

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `{!! implode('<br>', $errors->all()) !!}`, // tampil semua error
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif

@section('content')
    <div class="max-w-3xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Buku</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Input Group -->
                <div>
                    <label for="kode_buku" class="block text-sm font-medium text-gray-700 mb-2">Kode Buku</label>
                    <input type="text" id="kode_buku" name="kode_buku"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan kode buku..." required>
                </div>

                <div>
                    <label for="judul_buku" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                    <input type="text" id="judul_buku" name="judul_buku"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan judul buku..." required>
                </div>

                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan penerbit..." required>
                </div>

                <div>
                    <label for="pengarang" class="block text-sm font-medium text-gray-700 mb-2">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan pengarang..." required>
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="kategori_id" name="kategori_id"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                    <input type="date" id="tahun_terbit" name="tahun_terbit"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                <div>
                    <label for="cover_buku" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                    <input type="file" id="cover_buku" name="cover_buku" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('admin.buku.index') }}"
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
