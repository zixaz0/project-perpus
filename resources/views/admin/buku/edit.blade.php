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
                <a href="{{ route('admin.buku.edit', ['buku' => $buku->id]) }}" class="text-blue-600 hover:underline">Edit
                    Buku</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">{{ $buku->judul_buku }}</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Edit Buku</h1>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form action="{{ route('buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Info Buku Saat Ini -->
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-4">
                        @if ($buku->cover_buku)
                            <img src="{{ asset('storage/' . $buku->cover_buku) }}" alt="Cover"
                                class="w-16 h-20 object-cover rounded shadow">
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800 text-lg">{{ $buku->judul_buku }}</p>
                            <p class="text-sm text-gray-600">{{ $buku->pengarang }} • {{ $buku->penerbit }}</p>
                            <p class="text-xs text-gray-500 mt-1">Kode: {{ $buku->kode_buku }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kode Buku (readonly) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Buku</label>
                    <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50">
                        <span class="text-lg font-bold text-indigo-600">{{ $buku->kode_buku }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Kode buku tidak dapat diubah</p>
                </div>

                <!-- Kategori (readonly) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori - Jenis</label>
                    <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50">
                        <span class="text-gray-800 font-medium">
                            {{ $buku->kategori->kategori ?? '-' }} - {{ $buku->kategori->jenis ?? '-' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Kategori tidak dapat diubah</p>

                    <!-- Hidden input untuk kategori_id -->
                    <input type="hidden" name="kategori_id" value="{{ $buku->kategori_id }}">
                </div>

                <!-- Judul Buku -->
                <div>
                    <label for="judul_buku" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Buku <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="judul_buku" name="judul_buku"
                        value="{{ old('judul_buku', $buku->judul_buku) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Pengarang -->
                <div>
                    <label for="pengarang" class="block text-sm font-medium text-gray-700 mb-2">
                        Pengarang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="pengarang" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Penerbit -->
                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">
                        Penerbit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Tahun Terbit -->
                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun Terbit <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tahun_terbit" name="tahun_terbit"
                        value="{{ old('tahun_terbit', \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y-m-d')) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Cover Buku -->
                <div>
                    <label for="cover_buku" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku Baru</label>
                    <input type="file" id="cover_buku" name="cover_buku" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah cover (Max 2MB)</p>
                </div>

                <!-- Preview Cover Saat Ini -->
                @if ($buku->cover_buku)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cover Saat Ini</label>
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border">
                            <img src="{{ asset('storage/' . $buku->cover_buku) }}" alt="Cover Buku" id="current-cover"
                                class="w-24 h-32 object-cover rounded-lg shadow">
                            <div>
                                <p class="text-sm text-gray-600">Cover buku yang sedang digunakan</p>
                                <p class="text-xs text-gray-500 mt-1">Upload file baru untuk menggantinya</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Preview Cover Baru -->
                <div id="new-cover-preview" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview Cover Baru</label>
                    <div class="flex items-center gap-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <img id="preview-new-image" src="" alt="Preview"
                            class="w-24 h-32 object-cover rounded shadow">
                        <div>
                            <p class="text-sm text-gray-600"><i class="fas fa-sparkles text-yellow-500"></i> Cover baru siap
                                di-upload</p>
                            <button type="button" onclick="resetNewCover()"
                                class="text-xs text-red-500 hover:text-red-700 mt-1">
                                <i class="fas fa-times-circle"></i> Batalkan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Perubahan -->
                <div id="change-summary" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-edit text-yellow-600"></i> Perubahan yang Akan Disimpan:
                    </p>
                    <div class="space-y-2 text-sm" id="changes-list">
                        <!-- Will be populated by JS -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('admin.buku.index') }}"
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg shadow-md transition font-medium cursor-pointer">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>

            <script>
                const coverInput = document.getElementById('cover_buku');
                const newCoverPreview = document.getElementById('new-cover-preview');
                const previewNewImage = document.getElementById('preview-new-image');
                const changeSummary = document.getElementById('change-summary');
                const changesList = document.getElementById('changes-list');

                // Original values
                const originalData = {
                    judul: "{{ $buku->judul_buku }}",
                    pengarang: "{{ $buku->pengarang }}",
                    penerbit: "{{ $buku->penerbit }}",
                    tahun: "{{ \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y-m-d') }}"
                };

                // Preview new cover
                coverInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewNewImage.src = e.target.result;
                            newCoverPreview.classList.remove('hidden');
                            updateChangeSummary();
                        }
                        reader.readAsDataURL(file);
                    }
                });

                function resetNewCover() {
                    coverInput.value = '';
                    newCoverPreview.classList.add('hidden');
                    updateChangeSummary();
                }

                // Track changes
                function updateChangeSummary() {
                    const changes = [];

                    const judulBaru = document.getElementById('judul_buku').value;
                    const pengarangBaru = document.getElementById('pengarang').value;
                    const penerbitBaru = document.getElementById('penerbit').value;
                    const tahunBaru = document.getElementById('tahun_terbit').value;
                    const coverBaru = coverInput.files.length > 0;

                    if (judulBaru !== originalData.judul) {
                        changes.push(
                            `<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Judul:</b> ${originalData.judul} <i class="fas fa-arrow-right text-xs"></i> ${judulBaru}</span></div>`
                        );
                    }
                    if (pengarangBaru !== originalData.pengarang) {
                        changes.push(
                            `<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Pengarang:</b> ${originalData.pengarang} <i class="fas fa-arrow-right text-xs"></i> ${pengarangBaru}</span></div>`
                        );
                    }
                    if (penerbitBaru !== originalData.penerbit) {
                        changes.push(
                            `<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Penerbit:</b> ${originalData.penerbit} <i class="fas fa-arrow-right text-xs"></i> ${penerbitBaru}</span></div>`
                        );
                    }
                    if (tahunBaru !== originalData.tahun) {
                        changes.push(
                            `<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Tahun Terbit:</b> ${originalData.tahun} <i class="fas fa-arrow-right text-xs"></i> ${tahunBaru}</span></div>`
                        );
                    }
                    if (coverBaru) {
                        changes.push(
                            `<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Cover Buku:</b> Cover baru akan di-upload</span></div>`
                        );
                    }

                    if (changes.length > 0) {
                        changesList.innerHTML = changes.join('');
                        changeSummary.classList.remove('hidden');
                    } else {
                        changeSummary.classList.add('hidden');
                    }
                }

                // Listen to input changes
                document.getElementById('judul_buku').addEventListener('input', updateChangeSummary);
                document.getElementById('pengarang').addEventListener('input', updateChangeSummary);
                document.getElementById('penerbit').addEventListener('input', updateChangeSummary);
                document.getElementById('tahun_terbit').addEventListener('change', updateChangeSummary);
            </script>
        </div>
    </div>
@endsection
