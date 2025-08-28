<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BukuKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
    <div class="bg-white shadow-xl rounded-xl overflow-hidden w-full max-w-4xl grid md:grid-cols-2">
        
        <!-- Bagian Form -->
        <div class="p-10 flex flex-col justify-center">

            <!-- Logo untuk mobile -->
            <div class="text-center mb-6 md:hidden">
                <img src="{{ asset('images/logo.png') }}"  alt="Logo" class="mx-auto w-24 h-24 rounded-full drop-shadow-md object-cover bg-white">
                <h3 class="text-xl font-semibold text-gray-800">BukuKita</h3>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang 👋</h2>
            <p class="text-gray-500 mb-8">Masukkan Email dan Password Anda</p>

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
            
                <!-- Email -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" placeholder="Email" required
                        class="pl-10 pr-4 py-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- Password -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" placeholder="Password" required
                        class="pl-10 pr-4 py-3 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <!-- 🔴 Pesan error umum (login gagal) -->
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            
                <!-- Tombol -->
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg shadow-md transition">
                    Masuk
                </button>
            </form>
            

            <!-- Footer -->
            <p class="text-center mt-8 text-sm text-gray-400">© 2025 BukuKita. All rights reserved.</p>
        </div>

        <!-- Bagian Ilustrasi (desktop saja) -->
        <div class="hidden md:flex bg-gradient-to-br from-indigo-500 to-purple-600 items-center justify-center p-8">
            <div class="text-center text-white">

                <!-- Wrapper Circle -->
                <div class="mx-auto w-44 h-44 rounded-full bg-white flex items-center justify-center shadow-lg mb-6">
                    <img src="{{ asset('images/logo.png') }}" 
                        alt="Logo" 
                        class="w-28 h-28 object-contain">
                </div>

                <p class="text-sm opacity-80 mt-2">Sistem Manajemen Buku Modern</p>
            </div>
        </div>
    </div>
</body>

</html>
