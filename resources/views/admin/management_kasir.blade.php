@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Kasir</h1>

        <div class="flex items-center space-x-3">
            <!-- Search Bar -->
            <form action="{{ route('kasir.index') }}" method="GET" class="flex">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/email..."
                    class="px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md cursor-pointer">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <!-- Tombol Tambah -->
            <a href="{{ route('kasir.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow flex items-center space-x-2">
                <i class="fa fa-plus"></i>
                <span>Tambah Kasir</span>
            </a>
        </div>
    </div>

    <!-- Tabel Kasir -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Foto</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kasir as $index => $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $kasir->firstItem() + $index }}</td>

                        <!-- Foto Kasir -->
                        <td class="px-4 py-2">
                            <img src="{{ $item->foto ? asset('storage/'.$item->foto) : 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=random' }}"
                                 class="w-10 h-10 rounded-full border shadow-sm" alt="Foto {{ $item->name }}">
                        </td>

                        <td class="px-4 py-2 font-medium">{{ $item->name }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $item->email }}</td>

                        <!-- Tombol Aksi -->
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center items-center space-x-3">
                                <!-- Tombol Edit -->
                                <a href="{{ route('kasir.edit', $item->id) }}"
                                    class="w-10 h-10 flex items-center justify-center bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow transition"
                                    title="Edit Kasir">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus -->
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('kasir.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="confirmDelete({{ $item->id }}, '{{ $item->name }}')"
                                        class="w-10 h-10 flex items-center justify-center bg-red-500 text-white rounded-full hover:bg-red-600 shadow transition cursor-pointer"
                                        title="Hapus Kasir">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                            Belum ada data kasir ❗
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $kasir->withQueryString()->links() }}
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Kasir \"" + name + "\" akan dihapus permanen.",
            icon: 'warning',
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
@endsection