@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('kasir.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">Data Buku</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Data Buku</h1>
        </div>

        <!-- Filter & Search -->
        <div class="flex flex-wrap items-center justify-between mb-4 gap-3">
            <form action="{{ route('kasir.buku.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" id="searchCard" name="q" value="{{ request('q') }}"
                    placeholder="Cari judul / kode..."
                    class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

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

                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md cursor-pointer">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Grid Buku -->
        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php $cart = session('cart', []); @endphp
            @forelse($buku as $item)
                @php $inCart = isset($cart[$item->id]); @endphp
                <div class="book-card bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col"
                    data-title="{{ strtolower($item->judul_buku) }} {{ strtolower($item->kode_buku) }} {{ strtolower($item->kategori->kategori ?? '') }}">

                    <div class="relative w-full mb-4" style="padding-top: 150%;">
                        <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover {{ $item->judul_buku }}"
                            class="absolute inset-0 w-full h-full object-cover rounded">
                    </div>

                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $item->judul_buku }}</h3>

                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-tag text-blue-500"></i>
                        {{ $item->kategori->kategori ?? '-' }}
                    </p>

                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-calendar text-green-500"></i>
                        {{ $item->tahun_terbit->format('Y') }}
                    </p>

                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-cubes text-purple-500"></i>
                        Stok: {{ $item->stokHarga->stok ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-dollar-sign text-yellow-500"></i>
                        Harga: {{ $item->stokHarga ? 'Rp ' . number_format($item->stokHarga->harga, 0, ',', '.') : '-' }}
                    </p>

                    <!-- Tombol Aksi -->
                    <div class="mt-auto flex gap-2">
                        <button onclick="showDetail({{ $item->id }})"
                            class="w-1/2 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg shadow transition cursor-pointer">
                            <i class="fas fa-eye"></i>
                        </button>

                        @if (!$inCart)
                            <form action="{{ route('kasir.transaksi.add', $item->id) }}" method="POST"
                                class="w-1/2 add-cart-form">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg shadow transition cursor-pointer">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('kasir.transaksi.update', $item->id) }}" method="POST"
                                class="flex w-1/2 border rounded overflow-hidden update-cart-form">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="qty" value="{{ $cart[$item->id]['qty'] - 1 }}"
                                    class="px-3 bg-gray-100 cursor-pointer">−</button>
                                <span class="flex-1 text-center py-2">{{ $cart[$item->id]['qty'] }}</span>
                                <button type="submit" name="qty" value="{{ $cart[$item->id]['qty'] + 1 }}"
                                    class="px-3 bg-gray-100 cursor-pointer">+</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center text-gray-500">Belum ada buku.</p>
            @endforelse
        </div>

        <!-- Checkout Button (Sticky) -->
        <div id="checkoutButton" class="hidden fixed bottom-6 right-6 z-50">
            <a href="{{ route('kasir.transaksi.index') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i>
                <span id="checkoutInfo">Checkout</span>
            </a>
        </div>

        <!-- JS Data -->
        @php
            $bukuJSON = json_encode($buku);
            $cartJSON = json_encode($cart);
        @endphp

        <script>
            const buku = {!! $bukuJSON !!};
            const cart = {!! $cartJSON !!};

            function showDetail(id) {
                let item = buku.find(b => b && (b.id === id || String(b.id) === String(id)));
                if (!item) return;

                const rel = item.stokHarga ?? item.stok_harga ?? null;
                const stokText = rel ? (rel.stok ?? '-') : '-';
                const hargaText = rel ? ('Rp ' + new Intl.NumberFormat('id-ID').format(rel.harga ?? 0)) : '-';

                Swal.fire({
                    title: `<h2 class="text-xl font-bold text-gray-800 mb-4">${item.judul_buku}</h2>`,
                    html: `
                    <div class="flex flex-col md:flex-row items-start gap-6">
                        <div class="flex-shrink-0 w-40 aspect-[2/3]">
                            <img src="/storage/${item.cover_buku}" class="w-full h-full object-cover rounded-lg shadow-md border">
                        </div>
                        <div class="hidden md:block w-px bg-gray-200"></div>
                        <div class="text-left space-y-3 text-sm md:text-base">
                            <p><i class="fas fa-barcode text-indigo-600"></i> <b>Kode:</b> ${item.kode_buku}</p>
                            <p><i class="fas fa-building text-indigo-600"></i> <b>Penerbit:</b> ${item.penerbit}</p>
                            <p><i class="fas fa-user text-indigo-600"></i> <b>Pengarang:</b> ${item.pengarang}</p>
                            <p><i class="fas fa-tags text-indigo-600"></i> <b>Kategori:</b> ${item.kategori ? item.kategori.kategori : '-'}</p>
                            <p><i class="fas fa-list text-indigo-600"></i> <b>Jenis :</b> ${item.kategori ? item.kategori.jenis : '-'}</p>
                            <p><i class="fas fa-calendar text-indigo-600"></i> <b>Tahun Terbit:</b> ${item.tahun_terbit ? new Date(item.tahun_terbit).getFullYear() : '-'}</p>
                            <p><i class="fas fa-cubes text-indigo-600"></i> <b>Stok:</b> ${stokText}</p>
                            <p><i class="fas fa-dollar-sign text-indigo-600"></i> <b>Harga:</b> ${hargaText}</p>
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

            // 🔎 Search realtime
            document.getElementById("searchCard").addEventListener("keyup", function() {
                let keyword = this.value.toLowerCase();
                document.querySelectorAll(".book-card").forEach(card => {
                    let text = card.getAttribute("data-title");
                    card.style.display = text.includes(keyword) ? "flex" : "none";
                });
            });

            // ✅ Update Checkout Button
            function updateCheckoutButton() {
                const btn = document.getElementById("checkoutButton");
                const info = document.getElementById("checkoutInfo");
                const qty = Object.values(cart).reduce((sum, item) => sum + item.qty, 0);
                const total = Object.values(cart).reduce((sum, item) => sum + (item.harga ?? 0) * item.qty, 0);

                if (qty > 0) {
                    btn.classList.remove("hidden");
                    info.innerText = `Checkout (${qty} item - Rp ${new Intl.NumberFormat('id-ID').format(total)})`;
                } else {
                    btn.classList.add("hidden");
                }
            }

            updateCheckoutButton();
        </script>
    </div>
@endsection
