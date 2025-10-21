@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('admin.stok_harga.index') }}" class="text-blue-600 hover:underline">Stok & Harga Buku</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header dengan Search dan Tombol Tambah -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-4">
                <i class="fas fa-dollar text-indigo-600"></i>
                Stok & Harga Buku
            </h1>

            <!-- Form Pencarian dan Tombol Tambah -->
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <form method="GET" action="{{ route('admin.stok_harga.index') }}" class="flex-1 flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Cari judul atau kode buku..." 
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button 
                                type="submit" 
                                class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                            >
                                <i class="fas fa-search"></i>
                                <span>Cari</span>
                            </button>
                            
                            @if(request('search'))
                                <a 
                                    href="{{ route('admin.stok_harga.index') }}" 
                                    class="cursor-pointer bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                                >
                                    <i class="fas fa-times"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Tombol Tambah Data -->
                    <a href="{{ route('admin.stok_harga.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2 whitespace-nowrap">
                        <i class="fa fa-plus"></i>
                        <span>Tambah Data</span>
                    </a>
                </div>
                
                @if(request('search'))
                    <div class="mt-3 text-sm text-gray-600">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                        ({{ $stokHarga->total() }} data ditemukan)
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabel Stok & Harga -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">Cover</th>
                            <th class="px-4 py-2 text-left">Kode Buku</th>
                            <th class="px-4 py-2 text-left">Judul Buku</th>
                            <th class="px-4 py-2 text-left">Stok</th>
                            <th class="px-4 py-2 text-left">Harga</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokHarga as $index => $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $stokHarga->firstItem() + $index }}</td>
                                <td class="px-4 py-2">
                                    @if($item->buku->cover_buku)
                                        <img src="{{ asset('storage/' . $item->buku->cover_buku) }}" alt="cover"
                                            class="w-12 h-16 object-cover rounded shadow">
                                    @else
                                        <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-book text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                        {{ $item->buku->kode_buku }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <div>
                                        <p>{{ $item->buku->judul_buku }}</p>
                                        @if($item->buku->pengarang)
                                            <p class="text-xs text-gray-500">{{ $item->buku->pengarang }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs
                                        {{ $item->stok == 0 ? 'bg-red-100 text-red-700' : ($item->stok < 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                        {{ $item->stok }} pcs
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex gap-2 justify-center">
                                        <!-- Tambah Stok -->
                                        <a href="{{ route('admin.stok_harga.tambah_stok_form', $item->id) }}"
                                            class="group relative w-9 h-9 flex items-center justify-center bg-green-500 text-white rounded-full hover:bg-green-600 shadow"
                                            title="Tambah Stok">
                                            <i class="fa fa-plus text-sm"></i>
                                            <span
                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                            Tambah Stok
                                            </span>
                                        </a>

                                        <!-- Edit Harga -->
                                        <a href="{{ route('admin.stok_harga.edit', $item->id) }}"
                                            class="group relative w-9 h-9 flex items-center justify-center bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow"
                                            title="Edit Harga">
                                            <i class="fa fa-edit text-sm"></i>
                                            <span
                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                            Edit Harga
                                            </span>
                                        </a>

                                        <!-- Hapus -->
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('admin.stok_harga.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->buku->judul_buku }}')"
                                                class="group relative w-9 h-9 flex items-center justify-center bg-red-500 text-white rounded-full hover:bg-red-600 shadow cursor-pointer"
                                                title="Hapus">
                                                <i class="fa fa-trash text-sm"></i>
                                                <span
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                Hapus
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center">
                                    <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">
                                        @if(request('search'))
                                            Tidak ada data yang sesuai dengan pencarian "{{ request('search') }}"
                                        @else
                                            Tidak ada data stok & harga
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
                {{ $stokHarga->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, judul) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data stok & harga untuk \"" + judul + "\" akan dihapus permanen.",
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg mr-2',
                cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush