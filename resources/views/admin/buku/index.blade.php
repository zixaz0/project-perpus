@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">Management Buku</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header + Tombol Tambah + Search -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Buku</h1>

            <div class="flex items-center space-x-3">
                <!-- Form Pencarian & Filter -->
                <form action="{{ route('admin.buku.index') }}" method="GET" class="flex space-x-2">
                    <!-- Pencarian -->
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul / kode..."
                        class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">

                    <!-- Dropdown kategori - jenis -->
                    <select name="kategori_id"
                        class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $group => $items)
                            <optgroup label="{{ $group }}">
                                @foreach ($items as $kat)
                                    <option value="{{ $kat->id }}"
                                        {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->jenis }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>

                    <!-- Tombol cari -->
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md cursor-pointer">
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
                            <th class="px-4 py-2 text-left">Jenis</th>
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
                                <td class="px-4 py-2">{{ $item->kategori?->kategori ?? '' }}</td>
                                <td class="px-4 py-2">{{ $item->kategori->jenis ?? '' }}</td>
                                <td class="px-4 py-2">
                                    <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover"
                                        class="w-12 h-16 object-cover rounded">
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
                width: 750,
                padding: "1.5rem",
                background: "#f9fafb",
                showCloseButton: true,
                confirmButtonText: "Tutup",
                confirmButtonColor: "#2563eb",
                customClass: {
                    popup: 'rounded-2xl shadow-xl p-6'
                },
                html: `
        <div class="flex flex-col md:flex-row gap-6 items-start">
            
            <!-- Cover Buku -->
            <div class="flex-shrink-0 mx-auto md:mx-0">
                <img src="/storage/${item.cover_buku}" 
                     class="w-44 h-64 object-cover rounded-xl shadow-lg border border-gray-200">
            </div>

            <!-- Detail Buku -->
            <div class="flex-1 text-left space-y-4">
                <h2 class="text-2xl font-bold text-gray-800 border-b pb-2">
                    ${item.judul_buku}
                </h2>

                <!-- Grid 2 Kolom -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm md:text-base text-gray-700">

                    <p class="flex items-center gap-2">
                        <i class="fas fa-barcode text-indigo-600"></i> 
                        <span><b>Kode:</b> ${item.kode_buku}</span>
                    </p>

                    <p class="flex items-center gap-2">
                        <i class="fas fa-cubes text-indigo-600"></i> 
                        <span><b>Stok:</b> ${item.stok_harga ? item.stok_harga.stok + " pcs" : '-'}</span>
                    </p>

                    <p class="flex items-center gap-2">
                        <i class="fas fa-building text-indigo-600"></i> 
                        <span><b>Penerbit:</b> ${item.penerbit}</span>
                    </p>

                    <p class="flex items-center gap-2">
                        <i class="fas fa-dollar-sign text-indigo-600"></i> 
                        <span><b>Harga:</b> ${item.stok_harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.stok_harga.harga) : '-'}</span>
                    </p>

                    <p class="flex items-center gap-2">
                        <i class="fas fa-user text-indigo-600"></i> 
                        <span><b>Pengarang:</b> ${item.pengarang}</span>
                    </p>

                    <p class="flex items-center gap-2">
                        <i class="fas fa-calendar text-indigo-600"></i> 
                        <span><b>Tahun Terbit:</b> ${new Date(item.tahun_terbit).getFullYear()}</span>
                    </p>

                    <p class="flex items-center gap-2">
                        <i class="fas fa-tags text-indigo-600"></i> 
                        <span><b>Kategori:</b> ${item.kategori ? item.kategori.kategori : '-'}</span>
                    </p>

                    <p class="flex items-center gap-2">
                        <i class="fas fa-list text-indigo-600"></i> 
                        <span><b>Jenis:</b> ${item.kategori ? item.kategori.jenis : '-'}</span>
                    </p>
                </div>
            </div>
        </div>
    `
            });
        }
    </script>
