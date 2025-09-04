<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BukuKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body
    class="min-h-screen bg-gradient-to-br from-indigo-900 via-blue-700 to-sky-400 flex items-center justify-center px-4">

    <!-- Container -->
    <div class="w-full max-w-md bg-white/30 backdrop-blur-xl shadow-2xl rounded-2xl p-8 border border-white/20">

        <!-- Logo -->
        <div class="text-center mb-10">
            <div class="w-28 h-28 bg-white rounded-full flex items-center justify-center mx-auto shadow-xl">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 object-contain">
            </div>
            <h2 class="text-3xl font-bold text-white mt-4">Selamat Datang</h2>
            <p class="text-sm text-white/70">Masukkan Email dan Password Anda</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email dengan floating label + icon -->
            <div class="relative">
                <!-- Icon -->
                <span class="absolute inset-y-0 left-3 flex items-center text-indigo-400">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email" name="email" id="email" required
                    class="peer w-full pl-10 pr-4 pt-5 pb-2 rounded-xl bg-white/80 text-gray-800 placeholder-transparent border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-sm"
                    placeholder="Email" value="{{ old('email') }}">
                <label for="email"
                    class="absolute left-10 top-1 text-gray-500 text-sm transition-all
                 peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                 peer-focus:top-1 peer-focus:text-sm peer-focus:text-indigo-600">
                    Email
                </label>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password dengan floating label + icon -->
            <div class="relative">
                <!-- Icon -->
                <span class="absolute inset-y-0 left-3 flex items-center text-indigo-400">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password" name="password" id="password" required
                    class="peer w-full pl-10 pr-4 pt-5 pb-2 rounded-xl bg-white/80 text-gray-800 placeholder-transparent border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-sm"
                    placeholder="Password">
                <label for="password"
                    class="absolute left-10 top-1 text-gray-500 text-sm transition-all
                           peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                           peer-focus:top-1 peer-focus:text-sm peer-focus:text-indigo-600">
                    Password
                </label>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pesan Error -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tombol -->
            <button type="submit"
                class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white font-semibold shadow-lg transition">
                Masuk
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center mt-8 text-xs text-white/70">© 2025 BukuKita. All rights reserved.</p>
    </div>

</body>

</html>
