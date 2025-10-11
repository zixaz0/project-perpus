@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('owner.dashboard') }}" class="text-indigo-600 hover:underline font-medium">Dashboard</a>
            </li>
            <li class="text-gray-400">/</li>
            <li>
                <a href="" class="text-indigo-600 hover:underline font-medium">Data Buku</a>
            </li>
           
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold"> 
                <i class="fas fa-book text-indigo-600"></i> Data Buku</h1>
            <div class="flex items-center space-x-3">
                <!-- Search Bar -->
                <form action="{{ route('owner.buku.index') }}" method="GET" class="flex">
                    <input type="text" name="qu" value="{{ request('qu') }}" placeholder="Cari judul / kode..."
                        class="px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Buku -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Kode</th>
                        <th class="px-4 py-2 text-left">Judul</th>
                        <th class="px-4 py-2 text-left">Penerbit</th>
                        <th class="px-4 py-2 text-left">Pengarang</th>
                        <th class="px-4 py-2 text-left">kategori</th>
                        <th class="px-4 py-2 text-left">Tahun Terbit</th>
                        <th class="px-4 py-2 text-left">Cover</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buku as $index => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $item->kode_buku }}</td>
                            <td class="px-4 py-2">{{ $item->judul_buku }}</td>
                            <td class="px-4 py-2">{{ $item->penerbit }}</td>
                            <td class="px-4 py-2">{{ $item->pengarang }}</td>
                            <td class="px-4 py-2">{{ $item->kategori->kategori }}</td>
                            <td class="px-4 py-2">{{ $item->tahun_terbit->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">
                                <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover"
                                    class="w-12 h-16 object-cover rounded">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data buku ❗
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
