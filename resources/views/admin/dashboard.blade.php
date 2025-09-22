@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Halo Admin! Selamat datang di dashboard admin.</p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-blue-500 text-white rounded-lg shadow p-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold">Total Buku</h2>
                <p class="text-3xl font-bold">{{ $totalBuku }}</p>
            </div>
            <i class="fas fa-book text-5xl opacity-70"></i>
        </div>

        <div class="bg-green-600 text-white rounded-lg shadow p-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold">Total Kasir</h2>
                <p class="text-3xl font-bold">{{ $totalKasir }}</p>
            </div>
            <i class="fas fa-users text-5xl opacity-70"></i>
        </div>
    </div>

    <!-- Daftar Buku -->
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Buku</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($buku as $item)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col">
                <img src="{{ asset('storage/' . $item->cover_buku) }}" 
                     alt="cover {{ $item->judul_buku }}"
                     class="w-full h-40 object-cover rounded mb-4">

                <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $item->judul_buku }}</h3>
                <p class="text-sm text-gray-600 mb-1">
                    <i class="fas fa-tag text-blue-500"></i>
                    {{ $item->kategori->kategori ?? '-' }}
                </p>
                <p class="text-sm text-gray-600 mb-4">
                    <i class="fas fa-calendar text-green-500"></i>
                    {{ $item->tahun_terbit->format('Y') }}
                </p>

                <button onclick="showDetail({{ $item->id }})"
                    class="cursor-pointer mt-auto w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg shadow transition">
                    Detail
                </button>
            </div>
        @empty
            <p class="col-span-4 text-center text-gray-500">Belum ada buku.</p>
        @endforelse
    </div>
</div>

<!-- Modal Detail Buku -->
<script>
    function showDetail(id) {
        const buku = @json($buku);

        let item = buku.find(b => b.id === id);
        if (!item) return;

        Swal.fire({
            title: `<h2 class="text-xl font-bold text-gray-800 mb-4">${item.judul_buku}</h2>`,
            html: `
                <div class="flex flex-col md:flex-row items-start gap-6">
                    <!-- Cover -->
                    <div class="flex-shrink-0">
                        <img src="/storage/${item.cover_buku}" 
                             class="w-40 h-56 object-cover rounded-lg shadow-md border">
                    </div>

                    <!-- Divider (opsional biar rapih) -->
                    <div class="hidden md:block w-px bg-gray-200"></div>

                    <!-- Detail -->
                    <div class="text-left space-y-3 text-sm md:text-base">
                        <p><i class="fas fa-barcode text-indigo-600"></i> <b>Kode:</b> ${item.kode_buku}</p>
                        <p><i class="fas fa-building text-indigo-600"></i> <b>Penerbit:</b> ${item.penerbit}</p>
                        <p><i class="fas fa-user text-indigo-600"></i> <b>Pengarang:</b> ${item.pengarang}</p>
                        <p><i class="fas fa-tags text-indigo-600"></i> <b>Kategori:</b> ${item.kategori ? item.kategori.kategori : '-'}</p>
                        <p><i class="fas fa-calendar text-indigo-600"></i> <b>Tahun Terbit:</b> ${new Date(item.tahun_terbit).getFullYear()}</p>
                    </div>
                </div>
            `,
            width: 700,
            showCloseButton: true,
            confirmButtonText: "Tutup",
            confirmButtonColor: "#2563eb",
            background: "#f9fafb",
            customClass: {
                popup: 'rounded-2xl shadow-lg p-6'
            }
        });
    }
</script>
@endsection