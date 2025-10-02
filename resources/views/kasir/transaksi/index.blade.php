@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('kasir.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="{{ route('kasir.buku.index') }}" class="text-blue-600 hover:underline">Data Buku</a>
            </li>
            <li class="mx-2">/</li>
            <li>
                <a href="" class="text-blue-600 hover:underline">Keranjang Belanja</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-cart-shopping text-indigo-600"></i> Keranjang Belanja</h1>

        @if (empty($cart))
            <div class="p-6 bg-blue-100 text-blue-600 rounded-lg">
                Keranjang masih kosong. Silakan tambahkan buku dari data buku.
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- ✅ Daftar Item -->
                <div class="lg:col-span-2 space-y-4">
                    @php $total = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php
                            $subtotal = $item['qty'] * $item['harga'];
                            $total += $subtotal;
                        @endphp

                        <div class="flex items-start bg-white shadow rounded-lg p-4 border">
                            <!-- Thumbnail Buku -->
                            <img src="{{ isset($item['cover_buku']) && $item['cover_buku']
                                ? asset('storage/' . $item['cover_buku'])
                                : 'https://via.placeholder.com/80x100' }}"
                                alt="{{ $item['judul_buku'] }}" class="w-20 h-28 object-cover rounded">

                            <!-- Info Buku -->
                            <div class="flex-1 ml-4">
                                <h3 class="font-semibold text-gray-800">{{ $item['judul_buku'] }}</h3>
                                <p class="text-sm text-gray-500">Soft Cover</p>

                                <!-- Harga -->
                                <div class="mt-2">
                                    <span class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Aksi -->
                            <div class="flex flex-col items-center ml-4">
                                <!-- Hapus -->
                                <button type="button"
                                    onclick="event.preventDefault(); document.getElementById('remove-{{ $id }}').submit();"
                                    class="text-gray-400 hover:text-red-500 cursor-pointer">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Qty Update Form -->
                                <div class="flex items-center mt-8 border rounded">
                                    <form action="{{ route('kasir.transaksi.update', $id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="qty" value="{{ $item['qty'] - 1 }}" class="px-3 cursor-pointer">−</button>
                                        <span class="px-3">{{ $item['qty'] }}</span>
                                        <button type="submit" name="qty" value="{{ $item['qty'] + 1 }}" class="px-3 cursor-pointer">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ✅ Ringkasan Belanja -->
                <div class="bg-white shadow rounded-lg p-6 h-fit">
                    <h2 class="text-lg font-semibold mb-4">Ringkasan Keranjang</h2>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Total Harga ({{ count($cart) }} Barang)</span>
                        <span id="total-harga">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-red-600 mb-2">
                        <span>Diskon Belanja</span>
                        <span id="diskon-text">-Rp0</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Subtotal</span>
                        <span id="subtotal-text">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <!-- Form Checkout -->
                    <form id="checkout-form" action="{{ route('kasir.transaksi.checkout') }}" method="POST" class="mt-4 space-y-3">
                        @csrf
                        <input type="hidden" name="total" value="{{ $total }}" id="total-input">

                        <!-- Diskon -->
                        <input type="text" name="diskon" id="diskon" 
                            class="w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-500"
                            placeholder="Masukkan Diskon">

                        <!-- Dibayar -->
                        <input type="text" name="dibayar" id="dibayar" required
                            class="w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-500"
                            placeholder="Nominal Dibayar">

                        <!-- Metode Bayar -->
                        <select name="metode_bayar"
                            class="cursor-pointer w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-500">
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                            <option value="debit">Debit</option>
                        </select>

                        <button type="submit"
                            class="cursor-pointer w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
                            Checkout
                        </button>
                    </form>
                </div>
            </div>

            <!-- ✅ Hidden form hapus item -->
            @foreach ($cart as $id => $item)
                <form id="remove-{{ $id }}" action="{{ route('kasir.transaksi.remove', $id) }}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>

    <!-- ✅ Script Format Rupiah & Update Subtotal -->
    <script>
        const totalHarga = {{ $total ?? 0 }};
        const diskonInput = document.getElementById('diskon');
        const dibayarInput = document.getElementById('dibayar');
        const diskonText = document.getElementById('diskon-text');
        const subtotalText = document.getElementById('subtotal-text');
        const checkoutForm = document.getElementById('checkout-form');

        // Format angka ke Rp
        function formatRupiah(angka) {
            angka = angka.toString().replace(/[^0-9]/g, "");
            return angka ? "Rp " + angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "Rp 0";
        }

        // Ambil angka asli dari input yang sudah diformat
        function getAngka(str) {
            return parseInt(str.replace(/[^0-9]/g, "")) || 0;
        }

        function updateSubtotal() {
            const diskon = getAngka(diskonInput.value);
            const subtotal = Math.max(totalHarga - diskon, 0);

            diskonText.textContent = "-" + formatRupiah(diskon);
            subtotalText.textContent = formatRupiah(subtotal);
        }

        // Event: format input & update subtotal
        [diskonInput, dibayarInput].forEach(input => {
            input.addEventListener('input', function() {
                let angka = getAngka(this.value);
                this.value = formatRupiah(angka);

                if (this.id === 'diskon') {
                    updateSubtotal();
                }
            });
        });

        // Validasi dibayar >= subtotal sebelum submit
        checkoutForm.addEventListener('submit', function(e) {
            const diskon = getAngka(diskonInput.value);
            const subtotal = Math.max(totalHarga - diskon, 0);
            const dibayar = getAngka(dibayarInput.value);

            if (dibayar < subtotal) {
                e.preventDefault();
                alert("Nominal dibayar kurang dari subtotal!");
            }
        });

        // Init awal
        updateSubtotal();
    </script>
@endsection