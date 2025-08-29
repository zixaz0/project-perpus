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
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
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
                        <th class="px-4 py-2 text-center">Aksi</th>
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
                            <td class="px-4 py-2 text-center">
                                <!-- Tombol Hapus & Tombol Edit -->
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('admin.buku.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('buku.edit', $item->id) }}"
                                        class="w-10 h-10 flex items-center justify-center bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <button type="button"
                                        onclick="confirmDelete({{ $item->id }}, '{{ $item->judul_buku }}')"
                                        class="bg-red-500 hover:bg-red-600 text-white w-10 h-10 flex items-center justify-center rounded-full shadow">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                </form>
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
