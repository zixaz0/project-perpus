@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Edit Pegawai</h1>

    <div class="bg-white p-8 rounded-2xl shadow-lg border">
        <form id="form-edit-pegawai" action="{{ route('owner.pegawai.update', $pegawai->id) }}" 
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name', $pegawai->name) }}"
                    class="w-full border px-3 py-2 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $pegawai->email) }}"
                    class="w-full border px-3 py-2 rounded-lg" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password (Opsional)</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti password.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" class="w-full border px-3 py-2 rounded-lg" required>
                    <option value="admin" {{ $pegawai->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kasir" {{ $pegawai->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                </select>
            </div>

            <!-- 🔹 Foto Profil -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                
                <div class="flex items-center space-x-4">
                    @if($pegawai->foto)
                        <img src="{{ asset('storage/' . $pegawai->foto) }}" 
                             alt="Foto {{ $pegawai->name }}" 
                             class="w-20 h-20 rounded-lg object-cover border">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawai->name) }}&background=random" 
                             alt="Avatar {{ $pegawai->name }}" 
                             class="w-20 h-20 rounded-lg object-cover border">
                    @endif
                    <input type="file" name="foto" class="border px-3 py-2 rounded-lg">
                </div>
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti foto.</p>
            </div>

            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('owner.pegawai.index') }}" class="px-5 py-2 bg-gray-200 rounded-lg">Batal</a>
                <button type="button" onclick="confirmEdit()"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg cursor-pointer">Update</button>
            </div>
        </form>
    </div>
</div>

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
                document.getElementById("form-edit-pegawai").submit();
            } else if (result.isDenied) {
                Swal.fire({
                    title: "Perubahan tidak disimpan",
                    icon: "info",
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('owner.pegawai.index') }}";
                });
            }
        });
    }
</script>
@endsection