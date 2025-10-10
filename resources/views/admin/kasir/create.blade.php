@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.kasir.index') }}" class="text-blue-600 hover:underline">Management Kasir</a>
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
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
            });
        });
    </script>
@endif

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Tambah Kasir Baru</h1>

    <div class="bg-white p-8 rounded-2xl shadow-lg border">
        <form id="form-tambah-kasir" action="{{ route('kasir.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kasir <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Masukkan nama lengkap kasir...">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="contoh@email.com">
                <p class="text-xs text-gray-500 mt-1">Email akan digunakan untuk login</p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Minimal 8 karakter">
                    <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <i id="icon-password" class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Ketik ulang password">
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <i id="icon-password_confirmation" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Password Match Indicator -->
            <div id="password-match" class="hidden">
                <div class="p-3 rounded-lg text-sm">
                    <span id="match-icon"></span>
                    <span id="match-text"></span>
                </div>
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                <input type="file" id="foto" name="foto" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG (Max 2MB, Opsional)</p>
            </div>

            <!-- Preview Foto -->
            <div id="foto-preview" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preview Foto</label>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border">
                    <img id="preview-foto" src="" alt="Preview" class="w-20 h-20 object-cover rounded-full shadow">
                    <div>
                        <p class="text-sm text-gray-600">Foto siap di-upload</p>
                        <button type="button" onclick="resetFoto()" class="text-xs text-red-500 hover:text-red-700 mt-1">
                            <i class="fas fa-times-circle"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Preview -->
            <div id="summary-preview" class="hidden bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-user-tie text-indigo-600"></i> Ringkasan Kasir:
                </p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-semibold text-indigo-600" id="summary-name">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold text-indigo-600" id="summary-email">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Password:</span>
                        <span class="font-semibold text-indigo-600" id="summary-password">-</span>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end space-x-4 pt-4 border-t">
                <a href="{{ route('admin.kasir.index') }}" 
                   class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                    Batal
                </a>
                <button type="button" onclick="checkPassword()" 
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition font-medium cursor-pointer">
                    <i class="fas fa-save"></i> Simpan Kasir
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const fotoInput = document.getElementById('foto');
    const fotoPreview = document.getElementById('foto-preview');
    const previewFoto = document.getElementById('preview-foto');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const matchIndicator = document.getElementById('password-match');
    const matchIcon = document.getElementById('match-icon');
    const matchText = document.getElementById('match-text');
    const summaryPreview = document.getElementById('summary-preview');

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById('icon-' + fieldId);
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Preview foto
    fotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewFoto.src = e.target.result;
                fotoPreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    function resetFoto() {
        fotoInput.value = '';
        fotoPreview.classList.add('hidden');
    }

    // Check password match
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (confirm.length > 0) {
            matchIndicator.classList.remove('hidden');
            
            if (password === confirm) {
                matchIndicator.querySelector('div').className = 'p-3 rounded-lg text-sm bg-green-50 border border-green-200';
                matchIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                matchText.textContent = ' Password cocok!';
                matchText.className = 'text-green-700';
            } else {
                matchIndicator.querySelector('div').className = 'p-3 rounded-lg text-sm bg-red-50 border border-red-200';
                matchIcon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
                matchText.textContent = ' Password tidak cocok!';
                matchText.className = 'text-red-700';
            }
        } else {
            matchIndicator.classList.add('hidden');
        }

        updateSummary();
    }

    confirmInput.addEventListener('input', checkPasswordMatch);
    passwordInput.addEventListener('input', checkPasswordMatch);

    // Update summary
    function updateSummary() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = passwordInput.value;

        document.getElementById('summary-name').textContent = name || '-';
        document.getElementById('summary-email').textContent = email || '-';
        document.getElementById('summary-password').textContent = password ? '•'.repeat(password.length) + ' karakter' : '-';

        if (name || email || password) {
            summaryPreview.classList.remove('hidden');
        } else {
            summaryPreview.classList.add('hidden');
        }
    }

    document.getElementById('name').addEventListener('input', updateSummary);
    document.getElementById('email').addEventListener('input', updateSummary);
    passwordInput.addEventListener('input', updateSummary);

    function checkPassword() {
        let password = passwordInput.value;
        let confirm = confirmInput.value;

        if (password !== confirm) {
            Swal.fire({
                icon: 'error',
                title: 'Password Tidak Sama',
                text: 'Pastikan password dan konfirmasi password cocok.',
                confirmButtonColor: '#d33'
            });
        } else if (password.length < 8) {
            Swal.fire({
                icon: 'error',
                title: 'Password Terlalu Pendek',
                text: 'Password minimal 8 karakter.',
                confirmButtonColor: '#d33'
            });
        } else {
            document.getElementById("form-tambah-kasir").submit();
        }
    }
</script>
@endsection