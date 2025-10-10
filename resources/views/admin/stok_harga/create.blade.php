@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.stok_harga.index') }}" class="text-blue-600 hover:underline">Manajemen Stok & Harga</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.stok_harga.create') }}" class="text-blue-600 hover:underline">Tambah Stok & Harga</a>
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
    <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Stok & Harga</h1>

    <div class="bg-white p-8 rounded-2xl shadow-lg border">
        <form action="{{ route('admin.stok_harga.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pilih Buku -->
            <div>
                <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Buku</label>
                <select id="buku_id" name="buku_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Pilih Buku --</option>
                    @foreach($buku as $b)
                        <option value="{{ $b->id }}" 
                                data-cover="{{ asset('storage/' . $b->cover_buku) }}"
                                data-penulis="{{ $b->penulis }}"
                                {{ old('buku_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->judul_buku }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih buku yang akan ditambahkan stok dan harga</p>
            </div>

            <!-- Preview Buku yang Dipilih -->
            <div id="buku-preview" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buku yang Dipilih</label>
                <div class="flex items-center gap-4 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <img id="preview-cover" src="" alt="cover" class="w-16 h-20 object-cover rounded shadow">
                    <div>
                        <p id="preview-judul" class="font-semibold text-gray-800"></p>
                        <p id="preview-penulis" class="text-sm text-gray-600"></p>
                    </div>
                </div>
            </div>

            <!-- Input Stok -->
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok Awal</label>
                <input type="number" id="stok" name="stok" value="{{ old('stok') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Masukkan jumlah stok awal..." min="0" required>
                <p class="text-xs text-gray-500 mt-1">Jumlah stok buku yang tersedia</p>
            </div>

            <!-- Input Harga -->
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga Buku</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-600 font-medium">Rp</span>
                    <input type="number" id="harga" name="harga" value="{{ old('harga') }}"
                        class="w-full border border-gray-300 rounded-lg pl-12 pr-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan harga buku..." min="0" required>
                </div>
                <p class="text-xs text-gray-500 mt-1">Harga jual buku per unit</p>
            </div>

            <!-- Preview Summary -->
            <div id="summary-preview" class="hidden bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700 mb-3">📋 Ringkasan Data:</p>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Stok:</span>
                        <span class="font-semibold text-indigo-600"><span id="summary-stok">0</span> unit</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga:</span>
                        <span class="font-semibold text-indigo-600">Rp <span id="summary-harga">0</span></span>
                    </div>
                    <div class="border-t border-indigo-200 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span class="text-gray-700 font-medium">Total Nilai Stok:</span>
                            <span class="font-bold text-lg text-indigo-700">Rp <span id="summary-total">0</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.stok_harga.index') }}"
                    class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="cursor-pointer px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition font-medium">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const bukuSelect = document.getElementById('buku_id');
    const bukuPreview = document.getElementById('buku-preview');
    const previewCover = document.getElementById('preview-cover');
    const previewJudul = document.getElementById('preview-judul');
    const previewPenulis = document.getElementById('preview-penulis');
    const stokInput = document.getElementById('stok');
    const hargaInput = document.getElementById('harga');
    const summaryPreview = document.getElementById('summary-preview');
    const summaryStok = document.getElementById('summary-stok');
    const summaryHarga = document.getElementById('summary-harga');
    const summaryTotal = document.getElementById('summary-total');

    // Preview buku yang dipilih
    bukuSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            const cover = selectedOption.getAttribute('data-cover');
            const penulis = selectedOption.getAttribute('data-penulis');
            const judul = selectedOption.text;

            previewCover.src = cover;
            previewJudul.textContent = judul;
            previewPenulis.textContent = penulis;
            bukuPreview.classList.remove('hidden');
        } else {
            bukuPreview.classList.add('hidden');
        }
        updateSummary();
    });

    // Update summary
    function updateSummary() {
        const stok = parseInt(stokInput.value) || 0;
        const harga = parseInt(hargaInput.value) || 0;
        const total = stok * harga;

        summaryStok.textContent = stok;
        summaryHarga.textContent = harga.toLocaleString('id-ID');
        summaryTotal.textContent = total.toLocaleString('id-ID');

        // Show/hide summary
        if (stok > 0 || harga > 0) {
            summaryPreview.classList.remove('hidden');
        } else {
            summaryPreview.classList.add('hidden');
        }
    }

    stokInput.addEventListener('input', updateSummary);
    hargaInput.addEventListener('input', updateSummary);
</script>
@endsection