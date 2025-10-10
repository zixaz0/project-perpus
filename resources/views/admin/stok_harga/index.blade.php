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
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">
                <i class="fas fa-dollar text-indigo-600"></i>
                Stok & Harga Buku</h1>

            <!-- Tombol Tambah -->
            <a href="{{ route('admin.stok_harga.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow flex items-center space-x-2">
                <i class="fa fa-plus"></i>
                <span>Tambah Data</span>
            </a>
        </div>

        <!-- Tabel Stok & Harga -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">Cover</th>
                            <th class="px-4 py-2 text-left">Judul Buku</th>
                            <th class="px-4 py-2 text-left">Stok</th>
                            <th class="px-4 py-2 text-left">Harga</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokHarga as $index => $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">
                                    <img src="{{ asset('storage/' . $item->buku->cover_buku) }}" alt="cover"
                                        class="w-12 h-16 object-cover rounded">
                                </td>
                                <td class="px-4 py-2">{{ $item->buku->judul_buku }}</td>
                                <td class="px-4 py-2">{{ $item->stok }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
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
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                    Tidak ada data stok & harga
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

<script>
    function confirmDelete(id, judul) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data stok & harga untuk \"" + judul + "\" akan dihapus permanen.",
            icon: 'warning',
            iconColor: 'red',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>