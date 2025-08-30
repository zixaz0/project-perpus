@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header + Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Kategori</h1>
            <a href="{{ route('admin.kategori.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>

        <!-- Tabel Kategori -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Kategori</th>
                        <th class="px-4 py-2 text-left">Jenis</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $index => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ $item->kategori }}</td>
                            <td class="px-4 py-2">{{ $item->jenis }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <!-- Tombol Edit -->
                                <a href="{{ route('admin.kategori.edit', $item->id) }}"
                                    class="inline-flex items-center justify-center w-10 h-10 bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus -->
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('admin.kategori.destroy', $item->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="confirmDelete({{ $item->id }}, '{{ $item->kategori }}')"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-full shadow">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                Belum ada kategori ❗
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t">
            {{ $kategori->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, namaKategori) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Kategori \"" + namaKategori + "\" akan dihapus permanen.",
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
@endpush