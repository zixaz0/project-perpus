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
                <span class="text-blue-600 hover:underline">Keranjang Belanja</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <i class="fas fa-cart-shopping text-indigo-600"></i> Keranjang Belanja
        </h1>

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

                                <div class="mt-2">
                                    <span class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Aksi -->
                            <div class="flex flex-col items-center ml-4">
                                <button type="button"
                                    onclick="event.preventDefault(); document.getElementById('remove-{{ $id }}').submit();"
                                    class="text-gray-400 hover:text-red-500 cursor-pointer">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <div class="flex items-center mt-8 border rounded">
                                    <form action="{{ route('kasir.transaksi.update', $id) }}" method="POST"
                                        class="flex items-center update-cart-form" data-stok="{{ $item['stok'] }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="px-3 cursor-pointer btn-minus">−</button>
                                        <span class="px-3 qty-text">{{ $item['qty'] }}</span>
                                        <button type="button" class="px-3 cursor-pointer btn-plus">+</button>
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

                    <!-- ✅ Form Checkout -->
                    <form id="checkout-form" action="{{ route('kasir.transaksi.checkout') }}" method="POST"
                        class="mt-4 space-y-3">
                        @csrf
                        <input type="hidden" name="total" value="{{ $total }}" id="total-input">

                        <!-- Diskon -->
                        <input type="text" name="diskon" id="diskon"
                            class="w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-500"
                            placeholder="Masukkan Diskon (Opsional)">

                        <!-- Dibayar -->
                        <div id="dibayar-wrapper">
                            <input type="text" name="dibayar" id="dibayar" required
                                class="w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-500"
                                placeholder="Nominal Dibayar">
                        </div>

                        <!-- Metode Bayar -->
                        <select name="metode_bayar" id="metode_bayar"
                            class="cursor-pointer w-full border px-3 py-2 rounded focus:ring focus:ring-indigo-500">
                            <option value="cash">Cash</option>
                            <option value="debit">Cashless</option>
                        </select>

                        <button id="btn-bayar" type="button"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                            Bayar Sekarang
                        </button>

                        <button id="btn-batal" type="button"
                            class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                            Batalkan Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Hidden form hapus item -->
            @foreach ($cart as $id => $item)
                <form id="remove-{{ $id }}" action="{{ route('kasir.transaksi.remove', $id) }}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        const totalHarga = {{ $total ?? 0 }};
        const diskonInput = document.getElementById('diskon');
        const dibayarInput = document.getElementById('dibayar');
        const dibayarWrapper = document.getElementById('dibayar-wrapper');
        const diskonText = document.getElementById('diskon-text');
        const subtotalText = document.getElementById('subtotal-text');
        const checkoutForm = document.getElementById('checkout-form');
        const metodeSelect = document.getElementById('metode_bayar');
        const totalInput = document.getElementById('total-input');
        const btnBayar = document.getElementById('btn-bayar');
        const btnBatal = document.getElementById('btn-batal');

        // Variable untuk menyimpan order_id dari Midtrans
        let currentOrderId = null;

        // Format Rupiah
        function formatRupiah(angka) {
            angka = angka.toString().replace(/[^0-9]/g, "");
            return angka ? "Rp " + angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "Rp 0";
        }

        function getAngka(str) {
            return parseInt(str.replace(/[^0-9]/g, "")) || 0;
        }

        function updateSubtotal() {
            const diskon = getAngka(diskonInput.value);
            const subtotal = Math.max(totalHarga - diskon, 0);
            diskonText.textContent = "-" + formatRupiah(diskon);
            subtotalText.textContent = formatRupiah(subtotal);
        }

        // Toggle input "dibayar" kalau pilih Debit
        function toggleDibayar() {
            if (metodeSelect.value === 'debit') {
                dibayarWrapper.style.display = 'none';
                dibayarInput.removeAttribute('required');
            } else {
                dibayarWrapper.style.display = 'block';
                dibayarInput.setAttribute('required', 'true');
            }
        }

        metodeSelect.addEventListener('change', toggleDibayar);
        toggleDibayar();

        [diskonInput, dibayarInput].forEach(input => {
            input.addEventListener('input', function() {
                let angka = getAngka(this.value);
                this.value = formatRupiah(angka);
                if (this.id === 'diskon') updateSubtotal();
            });
        });

        updateSubtotal();

        // ✅ Tombol tambahan (awal disembunyikan)
        const tombolCetak = document.createElement("button");
        tombolCetak.textContent = "Cetak Struk";
        tombolCetak.id = "btnCetak";
        tombolCetak.type = "button";
        tombolCetak.className = "hidden w-full bg-green-600 text-white px-4 py-2 rounded mt-3 hover:bg-green-700";
        checkoutForm.appendChild(tombolCetak);

        tombolCetak.addEventListener("click", function() {
            window.location.href = "{{ route('kasir.dashboard') }}";
        });

        // ✅ Tombol "Batalkan Pembayaran" - DIPERBAIKI
        btnBatal.addEventListener("click", async function(e) {
            e.preventDefault();

            // Cek apakah ada transaksi Midtrans yang sedang berjalan
            if (!currentOrderId) {
                Swal.fire({
                    title: "Tidak Ada Transaksi",
                    text: "Tidak ada transaksi yang perlu dibatalkan.",
                    icon: "info",
                    confirmButtonColor: "#3b82f6",
                });
                return;
            }

            const result = await Swal.fire({
                title: "Batalkan Pembayaran?",
                html: `
                    <p>Pilih cara pembatalan transaksi:</p>
                    <div class="mt-4 space-y-2">
                        <button id="btn-cancel-midtrans" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Batalkan di Midtrans
                        </button>
                        <button id="btn-cancel-local" class="w-full bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                            Batalkan Lokal Saja
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Order ID: ${currentOrderId}</p>
                `,
                icon: "warning",
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: "Batal",
                cancelButtonColor: "#6b7280",
                didOpen: () => {
                    // Handler untuk tombol batalkan di Midtrans
                    document.getElementById('btn-cancel-midtrans').addEventListener('click',
                        async () => {
                            Swal.close();
                            await cancelViaMidtrans();
                        });

                    // Handler untuk tombol batalkan lokal
                    document.getElementById('btn-cancel-local').addEventListener('click', () => {
                        Swal.close();
                        cancelLocal();
                    });
                }
            });
        });

        // Function untuk cancel via Midtrans API
        async function cancelViaMidtrans() {
            // Tampilkan loading
            Swal.fire({
                title: 'Membatalkan...',
                text: 'Sedang membatalkan transaksi di Midtrans',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch("{{ route('kasir.midtrans.cancel') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        order_id: currentOrderId
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    Swal.fire({
                        title: "Dibatalkan!",
                        text: data.message,
                        icon: "success",
                        confirmButtonColor: "#22c55e",
                    }).then(() => {
                        currentOrderId = null;
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: "Gagal Membatalkan",
                        html: `
                            <p>${data.message}</p>
                            <p class="text-sm text-gray-600 mt-2">Order ID: ${currentOrderId}</p>
                            <p class="text-xs text-gray-500 mt-2">Tip: Transaksi akan expired otomatis dalam 24 jam</p>
                        `,
                        icon: "error",
                        confirmButtonColor: "#ef4444",
                    });
                    console.error('Error response:', data);
                }
            } catch (error) {
                Swal.fire({
                    title: "Error",
                    text: "Terjadi kesalahan saat membatalkan pembayaran: " + error.message,
                    icon: "error",
                    confirmButtonColor: "#ef4444",
                });
                console.error('Catch error:', error);
            }
        }

        // Function untuk cancel lokal (tanpa API Midtrans)
        function cancelLocal() {
            Swal.fire({
                title: "Transaksi Dibatalkan",
                html: `
                    <p>Transaksi lokal telah dibatalkan.</p>
                    <p class="text-sm text-gray-600 mt-2">Transaksi di Midtrans akan expired otomatis dalam 24 jam.</p>
                `,
                icon: "info",
                confirmButtonColor: "#3b82f6",
            }).then(() => {
                currentOrderId = null;
                location.reload();
            });
        }

        // ✅ Submit pembayaran - DIPERBAIKI
        btnBayar.addEventListener('click', async function(e) {
            e.preventDefault();
            const metode = metodeSelect.value;
            const total = totalHarga;
            const diskon = getAngka(diskonInput.value);
            const subtotal = Math.max(total - diskon, 0);
            const dibayar = getAngka(dibayarInput.value);

            // Validasi Cash manual
            if (metode !== 'debit' && dibayar < subtotal) {
                Swal.fire({
                    title: "Nominal Kurang!",
                    text: "Nominal dibayar kurang dari subtotal!",
                    icon: "warning",
                    confirmButtonColor: "#ef4444",
                });
                return;
            }

            // 🔹 Kalau Debit → Snap Midtrans
            if (metode === 'debit') {
                try {
                    const res = await fetch("{{ route('kasir.midtrans.token') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            total: subtotal
                        })
                    });
                    const data = await res.json();

                    // Simpan order_id untuk bisa dibatalkan
                    currentOrderId = data.order_id;

                    snap.pay(data.token, {
                        onSuccess: function(result) {
                            // Simpan transaksi ke backend
                            fetch("{{ route('kasir.transaksi.checkout') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    total: subtotal,
                                    diskon: diskon,
                                    metode_bayar: 'debit',
                                    order_id: data.order_id,
                                    payment_result: result
                                })
                            }).then(() => {
                                Swal.fire({
                                    title: "Pembayaran Berhasil!",
                                    text: "Transaksi debit telah selesai.",
                                    icon: "success",
                                    confirmButtonColor: "#22c55e",
                                }).then(() => {
                                    tombolCetak.classList.remove("hidden");
                                    btnBayar.disabled = true;
                                    btnBatal.disabled = true;
                                    // Reset order ID karena sudah berhasil
                                    currentOrderId = null;
                                });
                            });
                        },
                        onPending: function(result) {
                            Swal.fire({
                                title: "Menunggu Pembayaran",
                                text: "Status: Pending",
                                icon: "info",
                                confirmButtonColor: "#3b82f6",
                            });
                        },
                        onError: function(result) {
                            Swal.fire({
                                title: "Gagal!",
                                text: "Terjadi kesalahan pembayaran.",
                                icon: "error",
                                confirmButtonColor: "#ef4444",
                            });
                            console.error(result);
                            // Reset order ID karena error
                            currentOrderId = null;
                        },
                        onClose: function() {
                            Swal.fire({
                                title: "Pop-up Ditutup",
                                text: "Kamu menutup pop-up sebelum pembayaran selesai. Gunakan tombol 'Batalkan Pembayaran' untuk membatalkan transaksi di Midtrans.",
                                icon: "warning",
                                confirmButtonColor: "#f59e0b",
                            });
                            // Order ID tetap tersimpan untuk bisa dibatalkan
                        }
                    });
                } catch (error) {
                    Swal.fire({
                        title: "Error",
                        text: "Gagal membuat token Midtrans.",
                        icon: "error",
                        confirmButtonColor: "#ef4444",
                    });
                    console.error(error);
                    currentOrderId = null;
                }
                return;
            }

            // 🔹 Kalau Cash manual tetap sama
            checkoutForm.submit();
        });
    </script>
    <script>
        document.querySelectorAll(".update-cart-form").forEach(form => {
            const stok = parseInt(form.dataset.stok);
            const qtySpan = form.querySelector(".qty-text");
            const btnPlus = form.querySelector(".btn-plus");
            const btnMinus = form.querySelector(".btn-minus");

            let qty = parseInt(qtySpan.innerText);

            // ✅ Buat hidden input untuk qty
            let qtyInput = form.querySelector('input[name="qty"]');
            if (!qtyInput) {
                qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = 'qty';
                form.appendChild(qtyInput);
            }

            function updateDisplay() {
                qtySpan.innerText = qty;
                qtyInput.value = qty; // ✅ Update value hidden input

                // ✅ Disable tombol sesuai kondisi
                if (qty >= stok) {
                    btnPlus.disabled = true;
                    btnPlus.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btnPlus.disabled = false;
                    btnPlus.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                if (qty <= 1) {
                    btnMinus.disabled = true;
                    btnMinus.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btnMinus.disabled = false;
                    btnMinus.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            btnPlus.addEventListener("click", function() {
                if (qty < stok) {
                    qty++;
                    updateDisplay();
                    form.submit(); // submit otomatis ke backend
                } else {
                    // ✅ Alert SweetAlert ketika stok habis
                    Swal.fire({
                        icon: "error",
                        title: "Stok Tidak Mencukupi!",
                        html: `
                        <p>Jumlah yang Anda pilih sudah mencapai batas stok.</p>
                        <p class="text-sm text-gray-600 mt-2">Stok tersedia: <strong>${stok}</strong></p>
                    `,
                        confirmButtonColor: "#ef4444",
                        confirmButtonText: "OK"
                    });
                }
            });

            btnMinus.addEventListener("click", function() {
                if (qty > 1) {
                    qty--;
                    updateDisplay();
                    form.submit(); // ✅ Submit dengan qty yang sudah dikurangi
                } else {
                    // ✅ Alert jika ingin mengurangi qty = 1
                    Swal.fire({
                        icon: "warning",
                        title: "Hapus Item?",
                        text: "Jumlah minimal adalah 1. Apakah Anda ingin menghapus item ini dari keranjang?",
                        showCancelButton: true,
                        confirmButtonColor: "#ef4444",
                        cancelButtonColor: "#6b7280",
                        confirmButtonText: "Ya, Hapus",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form hapus
                            const formId = form.closest('.flex.items-start').querySelector(
                                    'button[onclick*="remove-"]')
                                .getAttribute('onclick').match(/remove-(\d+)/)[1];
                            document.getElementById('remove-' + formId).submit();
                        }
                    });
                }
            });

            updateDisplay(); // awal
        });
    </script>
@endsection
