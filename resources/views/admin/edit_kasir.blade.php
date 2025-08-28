@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Edit Kasir</h1>

    <div class="bg-white p-8 rounded-2xl shadow-lg border">
        <form id="form-edit-kasir" action="{{ route('kasir.update', $kasir->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $kasir->name) }}"
                       class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $kasir->email) }}"
                       class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (opsional)</label>
                <input type="password" id="password" name="password"
                       class="w-full border rounded-lg px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti password.</p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                <input type="file" id="foto" name="foto" class="w-full border rounded-lg px-3 py-2">

                @if($kasir->foto)
                    <p class="mt-2 text-sm text-gray-600">Foto saat ini:</p>
                    <img src="{{ asset('storage/'.$kasir->foto) }}" alt="Foto Profil"
                         class="w-20 h-20 rounded-full mt-1 border">
                @endif
            </div>

            <!-- Tombol -->
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('kasir.index') }}" class="px-5 py-2.5 bg-gray-200 rounded-lg">Batal</a>
                <button type="button" onclick="confirmEdit()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg">Update</button>
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
                document.getElementById("form-edit-kasir").submit();
            } else if (result.isDenied) {
                Swal.fire({
                    title: "Perubahan tidak disimpan",
                    icon: "info",
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('kasir.index') }}";
                });
            }
        });
    }
</script>