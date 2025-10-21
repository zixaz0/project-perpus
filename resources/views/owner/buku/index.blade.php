@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('owner.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">Data Buku</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-4">
                <i class="fas fa-book text-indigo-600"></i>
                Data Buku
            </h1>

            <!-- Form Pencarian -->
            <div class="bg-white shadow rounded-lg p-4">
                <form action="{{ route('owner.buku.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <!-- Input Pencarian -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="qu" 
                                value="{{ request('qu') }}" 
                                placeholder="Cari judul atau kode buku..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Tombol Cari dan Reset -->
                    <div class="flex gap-2">
                        <button 
                            type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                        >
                            <i class="fas fa-search"></i>
                            <span>Cari</span>
                        </button>
                        
                        @if(request('qu'))
                            <a 
                                href="{{ route('owner.buku.index') }}" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                            >
                                <i class="fas fa-times"></i>
                                <span>Reset</span>
                            </a>
                        @endif
                    </div>
                </form>
                
                @if(request('qu'))
                    <div class="mt-3 text-sm text-gray-600">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        Hasil pencarian untuk: <strong>"{{ request('qu') }}"</strong>
                        ({{ $buku->total() }} data ditemukan)
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabel Buku -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">Cover</th>
                            <th class="px-4 py-2 text-left">Kode</th>
                            <th class="px-4 py-2 text-left">Judul</th>
                            <th class="px-4 py-2 text-left">Penerbit</th>
                            <th class="px-4 py-2 text-left">Pengarang</th>
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-left">Tahun Terbit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buku as $index => $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $buku->firstItem() + $index }}</td>
                                
                                <!-- Cover Buku -->
                                <td class="px-4 py-2">
                                    @if($item->cover_buku)
                                        <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover"
                                            class="w-12 h-16 object-cover rounded shadow">
                                    @else
                                        <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-book text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Kode Buku -->
                                <td class="px-4 py-2">
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                        {{ $item->kode_buku }}
                                    </span>
                                </td>
                                
                                <!-- Judul -->
                                <td class="px-4 py-2">
                                    <div>
                                        <p>{{ $item->judul_buku }}</p>
                                        @if($item->stokHarga)
                                            <p class="text-xs text-gray-500">
                                                Stok: {{ $item->stokHarga->stok }} | 
                                                Rp {{ number_format($item->stokHarga->harga, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                </td>
                                
                                <td class="px-4 py-2">{{ $item->penerbit }}</td>
                                <td class="px-4 py-2">{{ $item->pengarang }}</td>
                                <td class="px-4 py-2">
                                    <div>
                                        <p class="text-sm">{{ $item->kategori->kategori }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->kategori->jenis }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-2">{{ $item->tahun_terbit->format('d-m-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center">
                                    <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">
                                        @if(request('qu'))
                                            Tidak ada data yang sesuai dengan pencarian "{{ request('qu') }}"
                                        @else
                                            Belum ada data buku
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t">
                {{ $buku->links() }}
            </div>
        </div>
    </div>
@endsection