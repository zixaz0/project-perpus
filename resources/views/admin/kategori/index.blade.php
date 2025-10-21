@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600 mb-4" aria-label="breadcrumb">
        <ol class="list-reset flex items-center space-x-2">
            <li> <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a> </li>
            <li>/</li>
            <li> <a href="{{ route('admin.kategori.index') }}" class="text-blue-600 hover:underline">Management Kategori</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header + Tombol Tambah -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-3">
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                <i class="fas fa-tags text-indigo-600"></i>
                Management Kategori</h1>
            <a href="{{ route('admin.kategori.create') }}"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-md transition duration-200">
                <i class="fas fa-plus"></i>
                <span>Tambah Kategori</span>
            </a>
        </div>

        <!-- Tabel Kategori -->
        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold">Jenis</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategori as $namaKategori => $group)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Nomor urut -->
                            <td class="px-4 py-3 text-gray-700 font-medium">{{ $loop->iteration }}</td>

                            <!-- Nama kategori -->
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $namaKategori }}</td>

                            <!-- Daftar jenis -->
                            <td class="px-4 py-3" colspan="2">
                                <div x-data="{ open: false }" class="space-y-2">
                                    <!-- Trigger -->
                                    <button @click="open = !open"
                                        class="cursor-pointer flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition group">
                                        <!-- Ikon caret -->
                                        <i :class="open ? 'fas fa-caret-down rotate-90' : 'fas fa-caret-right'"
                                            class="mr-2 transform transition-transform duration-300"></i>
                                        <span>{{ $group->count() }} Jenis</span>
                                    </button>
                                    <!-- List Jenis -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-2" class="ml-6 space-y-2">
                                        @foreach ($group as $item)
                                            <div
                                                class="flex justify-between items-center bg-gray-50 px-4 py-2 rounded-md shadow-sm hover:bg-gray-100 transition">
                                                <span class="text-gray-700">{{ $item->jenis }}</span>
                                                <div class="flex items-center gap-2">
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('admin.kategori.edit', $item->id) }}"
                                                        class="group relative flex items-center justify-center w-8 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full shadow transition">
                                                        <i class="fa fa-edit text-xs"></i>
                                                        <span
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                        Edit
                                                        </span>
                                                    </a>
                                                    <!-- Tombol Hapus -->
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('admin.kategori.destroy', $item->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->jenis }}')"
                                                            class="group relative flex items-center justify-center w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition cursor-pointer">
                                                            <i class="fa fa-trash text-xs"></i>
                                                            <span
                                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                            Hapus
                                                            </span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center space-y-3">
                                    <i class="fas fa-folder-open text-5xl text-gray-300"></i>
                                    <p class="text-lg text-gray-600 font-medium">Belum ada kategori ❗</p>
                                    <a href="{{ route('admin.kategori.create') }}"
                                        class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium transition">
                                        <i class="fas fa-plus"></i>
                                        <span>Tambah kategori pertama</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id, namaKategori) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: "Jenis \"" + namaKategori + "\" akan dihapus permanen.",
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
