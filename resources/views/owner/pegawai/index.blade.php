@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('owner.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">Data Pegawai</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-4">
                <i class="fas fa-users text-indigo-600"></i>
                Management Pegawai
            </h1>

            <!-- Form Pencarian dan Tombol Tambah -->
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    <form action="{{ route('owner.pegawai.index') }}" method="GET" class="flex-1 flex flex-col sm:flex-row gap-3">
                        <!-- Input Pencarian -->
                        <div class="flex-1 max-w-md">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="q" 
                                    value="{{ request('q') }}" 
                                    placeholder="Cari nama atau email..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Tombol Cari dan Reset -->
                        <div class="flex gap-2">
                            <button 
                                type="submit" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                            >
                                <i class="fas fa-search"></i>
                                <span>Cari</span>
                            </button>
                            
                            @if(request('q'))
                                <a 
                                    href="{{ route('owner.pegawai.index') }}" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition"
                                >
                                    <i class="fas fa-times"></i>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Tombol Tambah Pegawai -->
                    <a href="{{ route('owner.pegawai.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow flex items-center gap-2 whitespace-nowrap">
                        <i class="fa fa-plus"></i>
                        <span>Tambah Pegawai</span>
                    </a>
                </div>
                
                @if(request('q'))
                    <div class="mt-3 text-sm text-gray-600">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                        Hasil pencarian untuk: <strong>"{{ request('q') }}"</strong>
                        ({{ $pegawai->total() }} data ditemukan)
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabel Pegawai -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">No</th>
                            <th class="px-4 py-2 text-left">Foto</th>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Role</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawai as $index => $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $pegawai->firstItem() + $index }}</td>

                                <!-- Foto Pegawai (Clickable) -->
                                <td class="px-4 py-2">
                                    <div class="cursor-pointer group relative" onclick="viewProfile({{ $item->id }}, '{{ $item->name }}', '{{ $item->email }}', '{{ $item->role }}', '{{ $item->foto ? asset('storage/'.$item->foto) : '' }}', '{{ $item->created_at ? $item->created_at->format('d M Y') : '' }}')">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/'.$item->foto) }}"
                                                 class="w-12 h-12 rounded-full border-2 border-indigo-200 shadow object-cover transition-transform group-hover:scale-110 group-hover:border-indigo-400" 
                                                 alt="Foto {{ $item->name }}">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow transition-transform group-hover:scale-110">
                                                {{ strtoupper(substr($item->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <!-- Overlay icon saat hover -->
                                        <div class="absolute inset-0 bg-black bg-opacity-40 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                            <i class="fas fa-eye text-white text-sm"></i>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-2">{{ $item->name }}</td>
                                <td class="px-4 py-2 text-gray-600">{{ $item->email }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-xs
                                        {{ $item->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($item->role) }}
                                    </span>
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="px-4 py-2">
                                    <div class="flex gap-2 justify-center">
                                        <!-- Tombol Lihat Detail -->
                                        <button onclick="viewProfile({{ $item->id }}, '{{ $item->name }}', '{{ $item->email }}', '{{ $item->role }}', '{{ $item->foto ? asset('storage/'.$item->foto) : '' }}', '{{ $item->created_at ? $item->created_at->format('d M Y') : '' }}')"
                                            class="group relative w-9 h-9 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-blue-600 shadow cursor-pointer">
                                            <i class="fa fa-eye text-sm"></i>
                                            <span
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                Lihat Detail
                                            </span>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('owner.pegawai.edit', $item->id) }}"
                                            class="group relative w-9 h-9 flex items-center justify-center bg-yellow-500 text-white rounded-full hover:bg-yellow-600 shadow">
                                            <i class="fa fa-edit text-sm"></i>
                                            <span
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                                Edit
                                            </span>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('owner.pegawai.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->name }}')"
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
                                <td colspan="6" class="px-4 py-8 text-center">
                                    <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">
                                        @if(request('q'))
                                            Tidak ada data yang sesuai dengan pencarian "{{ request('q') }}"
                                        @else
                                            Belum ada data pegawai
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
                {{ $pegawai->withQueryString()->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Fungsi untuk melihat profil pegawai
        function viewProfile(id, name, email, role, foto, createdAt) {
            // Generate foto URL atau avatar default
            const fotoUrl = foto ? foto : `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random&size=200`;
            
            // Icon berdasarkan role
            const roleIcon = role === 'admin' ? 'fa-user-shield' : 'fa-user-tie';
            const roleColor = role === 'admin' ? 'purple' : 'blue';
            
            Swal.fire({
                title: 'Profil Pegawai',
                html: `
                    <div class="flex flex-col items-center space-y-6">
                        <!-- Foto Profil Full Size -->
                        <div class="relative group w-48 h-48">
                            <img src="${fotoUrl}"
                                alt="Foto ${name}" 
                                class="w-48 h-48 rounded-2xl shadow-xl object-cover border-4 border-indigo-100 cursor-pointer transition-transform hover:scale-105"
                                onclick="viewFullImage('${fotoUrl}')">
                            <div class="absolute top-2 right-2 bg-white text-${roleColor}-600 px-3 py-1 rounded-full text-xs font-semibold shadow-md">
                                <i class="fas ${roleIcon} mr-1"></i>${role.charAt(0).toUpperCase() + role.slice(1)}
                            </div>
                        </div>
                        
                        <!-- Info Profil -->
                        <div class="w-full bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 space-y-3">
                            <div class="flex items-center gap-3 pb-3 border-b border-indigo-200">
                                <i class="fas fa-user text-indigo-600"></i>
                                <div class="text-left flex-1">
                                    <p class="text-xs text-gray-500">Nama Lengkap</p>
                                    <p class="font-semibold text-gray-800">${name}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 pb-3 border-b border-indigo-200">
                                <i class="fas fa-envelope text-indigo-600"></i>
                                <div class="text-left flex-1">
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-800">${email}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 pb-3 border-b border-indigo-200">
                                <i class="fas fa-id-badge text-indigo-600"></i>
                                <div class="text-left flex-1">
                                    <p class="text-xs text-gray-500">Role</p>
                                    <p class="font-semibold text-gray-800">${role.charAt(0).toUpperCase() + role.slice(1)}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-calendar-alt text-indigo-600"></i>
                                <div class="text-left flex-1">
                                    <p class="text-xs text-gray-500">Bergabung Sejak</p>
                                    <p class="font-semibold text-gray-800">${createdAt || 'N/A'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 italic">
                            <i class="fas fa-info-circle mr-1"></i>
                            Klik foto untuk melihat ukuran penuh
                        </p>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: '<p class=cursor-pointer ><i class="fas fa-times mr-2"></i>Tutup</p>',
                width: 500,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl',
                    confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all hover:shadow-md'
                },
                buttonsStyling: false
            });
        }

        // Fungsi untuk melihat foto fullscreen
        function viewFullImage(src) {
            Swal.fire({
                imageUrl: src,
                imageAlt: 'Foto Profil',
                showConfirmButton: false,
                showCloseButton: true,
                background: 'rgba(0, 0, 0, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                customClass: {
                    image: 'max-h-screen object-contain rounded-2xl',
                    closeButton: 'text-white hover:text-gray-300'
                }
            });
        }

        // Fungsi untuk konfirmasi hapus
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: "Pegawai \"" + name + "\" akan dihapus permanen.",
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
@endsection