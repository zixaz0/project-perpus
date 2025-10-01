<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - BukuKita</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
  class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-900 via-blue-700 to-sky-400 px-4">

  <!-- Card -->
  <div
    class="w-full max-w-md bg-white/20 backdrop-blur-2xl shadow-2xl rounded-2xl p-8 border border-white/30 transition duration-500 hover:shadow-[0_0_40px_rgba(255,255,255,0.4)]">

    <!-- Logo -->
    <div class="text-center mb-10">
      <div
        class="group w-32 h-32 rounded-full p-[4px] bg-gradient-to-tr from-pink-500 via-indigo-500 to-sky-400 mx-auto shadow-xl cursor-pointer transition duration-500 hover:shadow-[0_0_40px_rgba(99,102,241,0.7)]">
        <div
          class="w-full h-full rounded-full bg-white flex items-center justify-center transition duration-500 group-hover:scale-105">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 object-contain">
        </div>
      </div>
      <h2 class="text-3xl font-bold text-white mt-6 tracking-wide">Selamat Datang</h2>
      <p class="text-sm text-white/70">Masukkan Email dan Password Anda</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
      @csrf

      <!-- Email -->
      <div class="relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-indigo-500">
          <!-- Heroicons Mail -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
        </span>
        <input type="email" name="email" id="email" required value="{{ old('email') }}"
          class="peer w-full pl-10 pr-4 pt-5 pb-2 rounded-xl bg-white/90 text-gray-800 placeholder-transparent border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
          placeholder="Email">
        <label for="email"
          class="absolute left-10 top-1 text-gray-500 text-sm transition-all duration-200
                 peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                 peer-focus:top-1 peer-focus:text-sm peer-focus:text-indigo-600">
          Email
        </label>
        @error('email')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password -->
      <div class="relative">
        <span class="absolute inset-y-0 left-3 flex items-center text-indigo-500">
          <!-- Heroicons Lock -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V9a5 5 0 10-10 0v2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
          </svg>
        </span>
        <input type="password" name="password" id="password" required
          class="peer w-full pl-10 pr-10 pt-5 pb-2 rounded-xl bg-white/90 text-gray-800 placeholder-transparent border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"
          placeholder="Password">
        <label for="password"
          class="absolute left-10 top-1 text-gray-500 text-sm transition-all duration-200
                 peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base
                 peer-focus:top-1 peer-focus:text-sm peer-focus:text-indigo-600">
          Password
        </label>
        <!-- Toggle Password -->
        <button type="button" onclick="togglePassword()"
          class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-indigo-600 transition">
          <!-- Heroicons Eye -->
          <svg id="toggleIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>
        </button>
        @error('password')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Pesan Error -->
      @if (session('error'))
        <div
          class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm shadow-sm animate-pulse">
          {{ session('error') }}
        </div>
      @endif

      <!-- Tombol -->
      <button type="submit"
        class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 via-blue-500 to-sky-400 hover:from-indigo-700 hover:via-blue-600 hover:to-sky-500 text-white font-semibold shadow-lg transition transform hover:scale-[1.04] hover:shadow-[0_0_25px_rgba(59,130,246,0.7)]">
        Masuk
      </button>
    </form>

    <!-- Footer -->
    <p class="text-center mt-8 text-xs text-white/70">© 2025 BukuKita. All rights reserved.</p>
  </div>

  <!-- Script -->
  <script>
    function togglePassword() {
      const input = document.getElementById("password");
      const icon = document.getElementById("toggleIcon");

      if (input.type === "password") {
        input.type = "text";
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.563-4.263m3.741-2.444A9.969 9.969 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.96 9.96 0 01-4.132 5.411M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
      } else {
        input.type = "password";
        icon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.458  12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
      }
    }
  </script>
</body>

</html>