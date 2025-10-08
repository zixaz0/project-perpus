@extends('layouts.app')

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
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Pegawai</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form id="form-tambah-pegawai" action="{{ route('owner.pegawai.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pegawai</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border px-3 py-2 rounded-lg"
                        required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border px-3 py-2 rounded-lg" required>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full border px-3 py-2 rounded-lg"
                        required>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full border px-3 py-2 rounded-lg" required>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full border px-3 py-2 rounded-lg" required>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>

                <!-- Foto -->
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <input type="file" id="foto" name="foto" class="w-full border rounded-lg px-3 py-2">
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('owner.pegawai.index') }}" class="px-5 py-2 bg-gray-200 rounded-lg">Batal</a>
                    <button type="button" onclick="validatePegawaiForm()"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validatePegawaiForm() {
            let password = document.getElementById("password").value;
            let confirm = document.getElementById("password_confirmation").value;

            if (password !== confirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Sama',
                    text: 'Pastikan password dan konfirmasi password cocok.',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            Swal.fire({
                title: "Apakah kamu yakin?",
                text: "Data pegawai akan disimpan!",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Simpan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("form-tambah-pegawai").submit();
                }
            });
        }
    </script>
@endsection
