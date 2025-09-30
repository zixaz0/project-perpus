@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.management_buku') }}" class="text-blue-600 hover:underline">Management Buku</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a  href="{{ route('admin.buku.edit', ['buku' => $buku->id]) }}" class="text-blue-600 hover:underline">Edit Buku</a>
            <li class="mx-2">/</li>
            <li>
                <a  href="" class="text-blue-600 hover:underline">{{ $buku->judul_buku }}</a>
            </li>   
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Edit Buku</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form id="form-edit-buku" action="{{ route('admin.buku.update', $buku->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="kode_buku" class="block text-sm font-medium text-gray-700 mb-2">Kode Buku</label>
                    <input type="text" value="{{ $buku->kode_buku }}" readonly
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-500 bg-gray-100 cursor-not-allowed">
                </div>

                <div>
                    <label for="judul_buku" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                    <input type="text" id="judul_buku" name="judul_buku"
                        value="{{ old('judul_buku', $buku->judul_buku) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                        required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div>
                    <label for="pengarang" class="block text-sm font-medium text-gray-700 mb-2">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}"
                        required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value=""> Pilih Kategori - Jenis </option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ $buku->kategori_id == $kat->id ? 'selected' : '' }}>
                                {{ $kat->kategori }} - {{ $kat->jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>                

                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                    <input type="date" id="tahun_terbit" name="tahun_terbit"
                        value="{{ old('tahun_terbit', \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y-m-d')) }}"
                        required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <div>
                    <label for="cover_buku" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                    <input type="file" id="cover_buku" name="cover_buku" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700">

                    @if ($buku->cover_buku)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600">Cover saat ini:</p>
                            <img src="{{ asset('storage/' . $buku->cover_buku) }}" alt="Cover Buku"
                                class="w-32 h-44 object-cover rounded-lg border mt-2">
                        </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('admin.management_buku') }}"
                        class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg cursor-pointer">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    function confirmEdit() {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Perubahan akan disimpan!",
            icon: "question",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Simpan",
            denyButtonText: `Jangan Simpan`
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("form-edit-buku").submit();
            } else if (result.isDenied) {
                Swal.fire({
                    title: "Perubahan tidak disimpan",
                    icon: "info",
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('admin.buku.index') }}";
                });
            }
        });
    }
</script>
