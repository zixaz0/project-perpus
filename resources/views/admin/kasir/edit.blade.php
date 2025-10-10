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
                <a href="" class="text-blue-600 hover:underline">Edit Kasir</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">{{ $kasir->name }}</a>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-4">Edit Kasir</h1>

        <div class="bg-white p-8 rounded-2xl shadow-lg border">
            <form id="form-edit-kasir" action="{{ route('kasir.update', $kasir->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Info Kasir Saat Ini -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-4">
                        @if ($kasir->foto)
                            <img src="{{ asset('storage/' . $kasir->foto) }}" alt="Foto {{ $kasir->name }}"
                                class="w-16 h-16 rounded-full object-cover border-2 border-yellow-300 shadow">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kasir->name) }}&background=random"
                                alt="Avatar {{ $kasir->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-yellow-300 shadow">
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800 text-lg">{{ $kasir->name }}</p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-envelope text-yellow-600"></i> {{ $kasir->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kasir <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $kasir->name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $kasir->email) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    <p class="text-xs text-gray-500 mt-1">Email akan digunakan untuk login</p>
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru (Opsional)
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="Biarkan kosong jika tidak ingin mengubah">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                            <i id="icon-password" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti password</p>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            placeholder="Ketik ulang password baru">
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

                <!-- Foto Profil -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <input type="file" id="foto" name="foto" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti foto (Max 2MB)</p>
                </div>

                <!-- Preview Foto Saat Ini -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg border">
                        @if ($kasir->foto)
                            <img src="{{ asset('storage/' . $kasir->foto) }}" alt="Foto {{ $kasir->name }}" 
                                 id="current-foto"
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-300 shadow">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kasir->name) }}&background=random"
                                 alt="Avatar {{ $kasir->name }}" 
                                 id="current-foto"
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-300 shadow">
                        @endif
                        <div>
                            <p class="text-sm text-gray-600">Foto profil yang sedang digunakan</p>
                            <p class="text-xs text-gray-500 mt-1">Upload file baru untuk menggantinya</p>
                        </div>
                    </div>
                </div>

                <!-- Preview Foto Baru -->
                <div id="new-foto-preview" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preview Foto Baru</label>
                    <div class="flex items-center gap-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <img id="preview-new-foto" src="" alt="Preview" class="w-20 h-20 rounded-full object-cover border-2 border-yellow-400 shadow">
                        <div>
                            <p class="text-sm text-gray-600"><i class="fas fa-sparkles text-yellow-500"></i> Foto baru siap di-upload</p>
                            <button type="button" onclick="resetNewFoto()" class="text-xs text-red-500 hover:text-red-700 mt-1">
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

                <!-- Tombol -->
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="{{ route('admin.kasir.index') }}" 
                       class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                        Batal
                    </a>
                    <button type="button" onclick="handleUpdate()"
                        class="px-5 py-2.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg shadow-md transition font-medium cursor-pointer">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const fotoInput = document.getElementById('foto');
        const newFotoPreview = document.getElementById('new-foto-preview');
        const previewNewFoto = document.getElementById('preview-new-foto');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const matchIndicator = document.getElementById('password-match');
        const matchIcon = document.getElementById('match-icon');
        const matchText = document.getElementById('match-text');
        const changeSummary = document.getElementById('change-summary');
        const changesList = document.getElementById('changes-list');

        // Original values
        const originalData = {
            name: "{{ $kasir->name }}",
            email: "{{ $kasir->email }}"
        };

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

        // Preview new foto
        fotoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewNewFoto.src = e.target.result;
                    newFotoPreview.classList.remove('hidden');
                    updateChangeSummary();
                }
                reader.readAsDataURL(file);
            }
        });

        function resetNewFoto() {
            fotoInput.value = '';
            newFotoPreview.classList.add('hidden');
            updateChangeSummary();
        }

        // Check password match
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (password.length > 0 && confirm.length > 0) {
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

            updateChangeSummary();
        }

        confirmInput.addEventListener('input', checkPasswordMatch);
        passwordInput.addEventListener('input', checkPasswordMatch);

        // Track changes
        function updateChangeSummary() {
            const changes = [];
            
            const nameBaru = document.getElementById('name').value;
            const emailBaru = document.getElementById('email').value;
            const passwordBaru = passwordInput.value;
            const fotoBaru = fotoInput.files.length > 0;

            if (nameBaru !== originalData.name) {
                changes.push(`<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Nama:</b> ${originalData.name} <i class="fas fa-arrow-right text-xs"></i> ${nameBaru}</span></div>`);
            }
            if (emailBaru !== originalData.email) {
                changes.push(`<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Email:</b> ${originalData.email} <i class="fas fa-arrow-right text-xs"></i> ${emailBaru}</span></div>`);
            }
            if (passwordBaru.length > 0) {
                changes.push(`<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Password:</b> Password akan diubah</span></div>`);
            }
            if (fotoBaru) {
                changes.push(`<div class="flex items-start gap-2"><span class="text-yellow-600"><i class="fas fa-angle-right"></i></span><span><b>Foto Profil:</b> Foto baru akan di-upload</span></div>`);
            }

            if (changes.length > 0) {
                changesList.innerHTML = changes.join('');
                changeSummary.classList.remove('hidden');
            } else {
                changeSummary.classList.add('hidden');
            }
        }

        // Listen to input changes
        document.getElementById('name').addEventListener('input', updateChangeSummary);
        document.getElementById('email').addEventListener('input', updateChangeSummary);

        function handleUpdate() {
            let password = passwordInput.value;
            let confirm = confirmInput.value;

            // Kalau ada password baru tapi konfirmasinya beda
            if (password && password !== confirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Tidak Sama',
                    text: 'Pastikan password dan konfirmasi password cocok.',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Kalau password ada tapi kurang dari 8 karakter
            if (password && password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Terlalu Pendek',
                    text: 'Password minimal 8 karakter.',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // Kalau password kosong atau password cocok → lanjut confirm edit
            Swal.fire({
                title: "Apakah kamu yakin?",
                text: "Perubahan akan disimpan!",
                icon: "question",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Simpan",
                denyButtonText: `Jangan Simpan`,
                confirmButtonColor: '#d97706',
                cancelButtonColor: '#6b7280',
                denyButtonColor: '#dc2626'
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
                        window.location.href = "{{ route('admin.kasir.index') }}";
                    });
                }
            });
        }
    </script>
@endsection