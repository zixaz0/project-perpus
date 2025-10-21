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
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-4">
                <i class="fas fa-book text-indigo-600"></i>
                Management Buku
            </h1>

            <!-- Form Pencarian dan Tombol Tambah -->
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <form action="{{ route('admin.buku.index') }}" method="GET" class="flex-1 flex flex-col sm:flex-row gap-3">
                        <!-- Input Pencarian -->
                        <div class="flex-1 max-w-xs">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="q" 
                                    value="{{ request('q') }}" 
                                    placeholder="Cari judul atau kode..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Dropdown Kategori -->
                        <div class="w-full sm:w-48">
                            <select 
                                name="kategori_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer"
                            >
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
                        </div>
                        
                        <!-- Tombol Cari dan Reset -->
                        <div class="flex gap-2">
                            <button 
                                type="submit" 
                                class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                            >
                                <i class="fas fa-search"></i>
                                <span>Cari</span>
                            </button>
                            
                            @if(request('q') || request('kategori_id'))
                                <a 
                                    href="{{ route('admin.buku.index') }}" 
                                    class="cursor-pointer bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                                >
                                    <i class="fas fa-times"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Tombol Tambah Buku -->
                    <a href="{{ route('admin.buku.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2 whitespace-nowrap">
                        <i class="fa fa-plus"></i>
                        <span>Tambah Buku</span>
                    </a>
                </div>
                
                @if(request('q') || request('kategori_id'))
                    <div class="mt-3 text-sm text-gray-600">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        @if(request('q'))
                            Hasil pencarian untuk: <strong>"{{ request('q') }}"</strong>
                        @endif
                        @if(request('kategori_id'))
                            @php
                                $selectedKat = \App\Models\Kategori::find(request('kategori_id'));
                            @endphp
                            @if($selectedKat)
                                dengan kategori: <strong>{{ $selectedKat->kategori }} - {{ $selectedKat->jenis }}</strong>
                            @endif
                        @endif
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
                            <th class="px-4 py-2 text-left">Kategori</th>
                            <th class="px-4 py-2 text-left">Jenis</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buku as $index => $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $buku->firstItem() + $index }}</td>
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
                                <td class="px-4 py-2">
                                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                        {{ $item->kode_buku }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <div>
                                        <p>{{ $item->judul_buku }}</p>
                                        @if($item->pengarang)
                                            <p class="text-xs text-gray-500">{{ $item->pengarang }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2">{{ $item->kategori?->kategori ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $item->kategori->jenis ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex gap-2 justify-center">
                                        <!-- Tombol Detail -->
                                        <button 
                                            onclick="showDetail({{ $item->id }})"
                                            class=" cursor-pointer group relative w-9 h-9 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-blue-600 shadow">
                                            <i class="fa fa-eye text-sm"></i>
                                            <span
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                Detail
                                            </span>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('buku.edit', $item->id) }}"
                                            class="group relative w-9 h-9 flex items-center justify-center bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow">
                                            <i class="fa fa-edit text-sm"></i>
                                            <span
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                Edit
                                            </span>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('admin.buku.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->judul_buku }}')"
                                                class="group relative w-9 h-9 flex items-center justify-center bg-red-500 text-white rounded-full hover:bg-red-600 shadow cursor-pointer">
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
                                    <i class="fas fa-inbox text-5xl text-gray-300 mb-3 cursor-pointer"></i>
                                    <p class="text-gray-500">
                                        @if(request('q') || request('kategori_id'))
                                            Tidak ada data yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data buku
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

@push('scripts')
<script>
    function confirmDelete(id, judul) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Buku \"" + judul + "\" akan dihapus permanen.",
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
@endpush