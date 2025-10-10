@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.buku.index') }}" class="text-blue-600 hover:underline">Management Buku</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.buku.create') }}" class="text-blue-600 hover:underline">Tambah Buku</a>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Buku Baru</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Pilih Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori" name="kategori" required
                        class="cursor-pointer w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori->groupBy('kategori') as $kat => $items)
                            <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Jenis (Muncul setelah kategori dipilih) -->
                <div id="jenis-container" class="hidden">
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis" name="kategori_id" required
                        class="cursor-pointer w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Jenis --</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih jenis untuk generate kode buku</p>
                </div>

                <!-- Kode Buku (Auto Generate) -->
                <div>
                    <label for="kode_buku" class="block text-sm font-medium text-gray-700 mb-2">Kode Buku</label>
                    <input type="text" id="kode_buku" name="kode_buku" readonly
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 bg-gray-50 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">Kode otomatis di-generate setelah memilih jenis</p>
                </div>

                <!-- Preview Kode -->
                <div id="kode-preview" class="hidden bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">📋 Kode Buku yang Akan Digunakan:</p>
                    <p class="text-2xl font-bold text-indigo-600" id="preview-kode">-</p>
                </div>

                <!-- Judul Buku -->
                <div>
                    <label for="judul_buku" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Buku <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="judul_buku" name="judul_buku" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan judul buku...">
                </div>

                <!-- Pengarang -->
                <div>
                    <label for="pengarang" class="block text-sm font-medium text-gray-700 mb-2">
                        Pengarang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="pengarang" name="pengarang" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan nama pengarang...">
                </div>

                <!-- Penerbit -->
                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">
                        Penerbit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="penerbit" name="penerbit" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Masukkan nama penerbit...">
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun Terbit <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tahun_terbit" name="tahun_terbit" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Cover Buku -->
                <div>
                    <label for="cover_buku" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                    <input type="file" id="cover_buku" name="cover_buku" accept="image/*"
                        class="cursor-pointer w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG (Max 2MB)</p>
                </div>

                <!-- Preview Cover -->
                <div id="cover-preview" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview Cover</label>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border">
                        <img id="preview-image" src="" alt="Preview" class="w-24 h-32 object-cover rounded shadow">
                        <div>
                            <p class="text-sm text-gray-600">Cover siap di-upload</p>
                            <button type="button" onclick="resetCover()" class="cursor-pointer text-xs text-red-500 hover:text-red-700 mt-1">
                                <i class="fas fa-times-circle"></i> Batalkan Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Preview -->
                <div id="summary-preview" class="hidden bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-3"><i class="fas fa-info-circle text-indigo-600"></i> Ringkasan Buku:</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kode:</span>
                            <span class="font-semibold text-indigo-600" id="summary-kode">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Judul:</span>
                            <span class="font-semibold text-indigo-600" id="summary-judul">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pengarang:</span>
                            <span class="font-semibold text-indigo-600" id="summary-pengarang">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Penerbit:</span>
                            <span class="font-semibold text-indigo-600" id="summary-penerbit">-</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('admin.buku.index') }}"
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition font-medium cursor-pointer">
                        <i class="fas fa-save"></i> Simpan Buku
                    </button>
                </div>
            </form>

            <script>
                // Data kategori dari backend
                const kategoriData = @json($kategori);

                const kategoriSelect = document.getElementById('kategori');
                const jenisContainer = document.getElementById('jenis-container');
                const jenisSelect = document.getElementById('jenis');
                const kodeBukuInput = document.getElementById('kode_buku');
                const kodePreview = document.getElementById('kode-preview');
                const previewKode = document.getElementById('preview-kode');
                const coverInput = document.getElementById('cover_buku');
                const coverPreview = document.getElementById('cover-preview');
                const previewImage = document.getElementById('preview-image');
                const summaryPreview = document.getElementById('summary-preview');

                // Ketika kategori dipilih, tampilkan dropdown jenis
                kategoriSelect.addEventListener('change', function() {
                    const selectedKategori = this.value;
                    
                    // Reset jenis dropdown
                    jenisSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
                    kodeBukuInput.value = '';
                    kodePreview.classList.add('hidden');
                    
                    if (selectedKategori) {
                        // Filter jenis berdasarkan kategori yang dipilih
                        const jenisOptions = kategoriData.filter(item => item.kategori === selectedKategori);
                        
                        jenisOptions.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.jenis;
                            jenisSelect.appendChild(option);
                        });
                        
                        jenisContainer.classList.remove('hidden');
                    } else {
                        jenisContainer.classList.add('hidden');
                    }
                    
                    updateSummary();
                });

                // Generate kode buku ketika jenis dipilih
                jenisSelect.addEventListener('change', function() {
                    let kategoriId = this.value;
                    if (kategoriId) {
                        fetch(`/admin/buku/generate-kode/${kategoriId}`)
                            .then(res => res.json())
                            .then(data => {
                                kodeBukuInput.value = data.kode_buku;
                                previewKode.textContent = data.kode_buku;
                                kodePreview.classList.remove('hidden');
                                updateSummary();
                            });
                    } else {
                        kodeBukuInput.value = '';
                        kodePreview.classList.add('hidden');
                        updateSummary();
                    }
                });

                // Preview cover
                coverInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            coverPreview.classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });

                function resetCover() {
                    coverInput.value = '';
                    coverPreview.classList.add('hidden');
                }

                // Update summary
                function updateSummary() {
                    const kode = kodeBukuInput.value;
                    const judul = document.getElementById('judul_buku').value;
                    const pengarang = document.getElementById('pengarang').value;
                    const penerbit = document.getElementById('penerbit').value;

                    document.getElementById('summary-kode').textContent = kode || '-';
                    document.getElementById('summary-judul').textContent = judul || '-';
                    document.getElementById('summary-pengarang').textContent = pengarang || '-';
                    document.getElementById('summary-penerbit').textContent = penerbit || '-';

                    if (kode || judul || pengarang || penerbit) {
                        summaryPreview.classList.remove('hidden');
                    } else {
                        summaryPreview.classList.add('hidden');
                    }
                }

                document.getElementById('judul_buku').addEventListener('input', updateSummary);
                document.getElementById('pengarang').addEventListener('input', updateSummary);
                document.getElementById('penerbit').addEventListener('input', updateSummary);
            </script>
        </div>
    </div>
@endsection