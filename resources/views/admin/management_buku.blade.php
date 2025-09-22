@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header + Tombol Tambah + Search -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Buku</h1>

            <div class="flex items-center space-x-3">
                <!-- Search Bar -->
                <form action="{{ route('admin.buku.index') }}" method="GET" class="flex">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul / kode..."
                        class="px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md cursor-pointer">
                        <i class="fa fa-search"></i>
                    </button>
                </form>

                <!-- Tombol Tambah -->
                <a href="{{ route('admin.buku.create') }}"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow flex items-center space-x-2">
                    <span>Tambah Buku</span>
                </a>
            </div>
        </div>

<!-- Tabel Buku -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Kode</th>
                    <th class="px-4 py-2 text-left">Judul</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-left">Cover</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buku as $index => $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $item->kode_buku }}</td>
                        <td class="px-4 py-2">{{ $item->judul_buku }}</td>
                        <td class="px-4 py-2">{{ $item->kategori->kategori }}</td>
                        <td class="px-4 py-2">
                            <img src="{{ asset('storage/' . $item->cover_buku) }}" 
                                 alt="cover" class="w-12 h-16 object-cover rounded">
                        </td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col items-center space-y-1">
                                    <!-- Baris Atas: Edit + Detail -->
                                    <div class="flex gap-1">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('buku.edit', $item->id) }}"
                                            class="w-9 h-9 flex items-center justify-center bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow"
                                            title="Edit">
                                            <i class="fa fa-edit text-sm"></i>
                                        </a>
                            
                                        <!-- Tombol Detail -->
                                        <a href="javascript:void(0);" onclick="showDetail({{ $item->id }})"
                                            class="w-9 h-9 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-blue-600 shadow"
                                            title="Detail">
                                            <i class="fa fa-eye text-sm"></i>
                                        </a>
                                    </div>
                            
                                    <!-- Baris Bawah: Hapus -->
                                    <form id="delete-form-{{ $item->id }}"
                                        action="{{ route('admin.buku.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->judul_buku }}')"
                                            class="w-9 h-9 flex items-center justify-center bg-red-500 text-white rounded-full hover:bg-red-600 shadow cursor-pointer"
                                            title="Hapus">
                                            <i class="fa fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>                                                    
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada data buku ❗
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
@endsection

<script>
    function confirmDelete(id, judul) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Buku \"" + judul + "\" akan dihapus permanen.",
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

<script>
    function showDetail(id) {
        const buku = @json($buku->items());
        let item = buku.find(b => b.id === id);
        if (!item) return;

        Swal.fire({
            title: `<h2 class="text-xl font-bold text-gray-800 mb-4">${item.judul_buku}</h2>`,
            html: `
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <!-- Cover -->
                    <div class="flex-shrink-0 mx-auto md:mx-0">
                        <img src="/storage/${item.cover_buku}" 
                             class="w-40 h-56 object-cover rounded-lg shadow-md border">
                    </div>

                    <!-- Detail -->
                    <div class="text-left space-y-3 text-sm md:text-base">
                        <p><i class="fas fa-barcode text-indigo-600"></i> 
                           <b>Kode:</b> ${item.kode_buku}</p>
                        <p><i class="fas fa-building text-indigo-600"></i> 
                           <b>Penerbit:</b> ${item.penerbit}</p>
                        <p><i class="fas fa-user text-indigo-600"></i> 
                           <b>Pengarang:</b> ${item.pengarang}</p>
                        <p><i class="fas fa-tags text-indigo-600"></i> 
                           <b>Kategori:</b> ${item.kategori ? item.kategori.kategori : '-'}</p>
                        <p><i class="fas fa-calendar text-indigo-600"></i> 
                           <b>Tahun Terbit:</b> ${new Date(item.tahun_terbit).getFullYear()}</p>
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