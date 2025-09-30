@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.management_kasir') }}" class="text-blue-600 hover:underline">Management Kasir</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">Tambah Kasir</a>
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
                html: `{!! implode('<br>', $errors->all()) !!}`, // tampil semua error
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Kasir</h1>

    <div class="bg-white p-8 rounded-2xl shadow-lg border">
        <form id="form-tambah-kasir" action="{{ route('kasir.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kasir</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                <input type="file" id="foto" name="foto" class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- Tombol -->
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('kasir.index') }}" class="px-5 py-2.5 bg-gray-200 rounded-lg">Batal</a>
                <button type="button" onclick="checkPassword()" 
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg cursor-pointer">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function checkPassword() {
        let password = document.getElementById("password").value;
        let confirm  = document.getElementById("password_confirmation").value;

        if (password !== confirm) {
            Swal.fire({
                icon: 'error',
                title: 'Password Tidak Sama',
                text: 'Pastikan password dan konfirmasi password cocok.',
                confirmButtonColor: '#d33'
            });
        } else {
            document.getElementById("form-tambah-kasir").submit();
        }
    }
</script>
@endsection