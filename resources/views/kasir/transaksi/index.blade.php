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
    // ========== GLOBAL VARIABLES (PAKAI LET BIAR BISA DIUPDATE!) ==========
    let totalHarga = {{ $total ?? 0 }}; // ⚠️ PENTING: Pakai let, bukan const
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
    let currentOrderId = null;

    // ========== HELPER FUNCTIONS ==========
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

    function toggleDibayar() {
        if (metodeSelect.value === 'debit') {
            dibayarWrapper.style.display = 'none';
            dibayarInput.removeAttribute('required');
        } else {
            dibayarWrapper.style.display = 'block';
            dibayarInput.setAttribute('required', 'true');
        }
    }

    // ========== EVENT LISTENERS - DISKON & METODE BAYAR ==========
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

    // ========== TOMBOL CETAK ==========
    const tombolCetak = document.createElement("button");
    tombolCetak.textContent = "Cetak Struk";
    tombolCetak.id = "btnCetak";
    tombolCetak.type = "button";
    tombolCetak.className = "hidden w-full bg-green-600 text-white px-4 py-2 rounded mt-3 hover:bg-green-700";
    checkoutForm.appendChild(tombolCetak);

    tombolCetak.addEventListener("click", function() {
        window.location.href = "{{ route('kasir.dashboard') }}";
    });

    // ========== TOMBOL BAYAR - LANGSUNG REDIRECT KE STRUK ==========
    btnBayar.addEventListener('click', async function(e) {
        e.preventDefault();
        const metode = metodeSelect.value;
        const diskon = getAngka(diskonInput.value);
        const subtotal = Math.max(totalHarga - diskon, 0);
        const dibayar = getAngka(dibayarInput.value);

        // Validasi Cash
        if (metode === 'cash') {
            if (!dibayar || dibayar === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nominal Dibayar Kosong',
                    text: 'Silakan masukkan nominal yang dibayar!',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            if (dibayar < subtotal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nominal Kurang',
                    html: `
                        <p>Nominal yang dibayar kurang dari total tagihan.</p>
                        <p class="mt-2"><strong>Total: ${formatRupiah(subtotal)}</strong></p>
                        <p><strong>Dibayar: ${formatRupiah(dibayar)}</strong></p>
                    `,
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Proses Cash langsung ke backend
            btnBayar.disabled = true;
            btnBayar.textContent = 'Memproses...';

            const formData = new FormData(checkoutForm);
            formData.set('total', subtotal);
            formData.set('diskon', diskon);
            formData.set('dibayar', dibayar);
            formData.set('metode_bayar', 'cash');

            try {
                const response = await fetch(checkoutForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success && data.transaksi_id) {
                    // LANGSUNG REDIRECT KE STRUK TANPA SWAL
                    window.location.href = `/kasir/transaksi/struk/${data.transaksi_id}`;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan',
                        confirmButtonColor: '#ef4444'
                    });
                    btnBayar.disabled = false;
                    btnBayar.textContent = 'Bayar Sekarang';
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memproses pembayaran',
                    confirmButtonColor: '#ef4444'
                });
                btnBayar.disabled = false;
                btnBayar.textContent = 'Bayar Sekarang';
            }
            return;
        }

        // ========== CASHLESS/DEBIT - MIDTRANS ==========
        if (metode === 'debit') {
            btnBayar.disabled = true;
            btnBayar.textContent = 'Memproses...';

            try {
                // Request token ke backend
                const response = await fetch("{{ route('kasir.midtrans.token') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        total: subtotal,
                        diskon: diskon
                    })
                });

                const data = await response.json();

                if (!data.token) {
                    throw new Error('Token tidak ditemukan');
                }

                // Simpan order_id untuk cancel
                currentOrderId = data.order_id;

                // Buka Midtrans Snap
                snap.pay(data.token, {
                    onSuccess: async function(result) {
                        // Simpan transaksi ke backend
                        try {
                            const saveResponse = await fetch("{{ route('kasir.transaksi.checkout') }}", {
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
                            });

                            const saveData = await saveResponse.json();

                            if (saveData.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Transaksi cashless telah selesai.',
                                    confirmButtonColor: '#10b981',
                                    confirmButtonText: 'Cetak Struk'
                                }).then(() => {
                                    // Redirect ke halaman cetak struk
                                    if (saveData.transaksi_id) {
                                        window.location.href = `/kasir/transaksi/struk/${saveData.transaksi_id}`;
                                    } else {
                                        location.reload();
                                    }
                                });
                            } else {
                                throw new Error(saveData.message || 'Gagal menyimpan transaksi');
                            }
                        } catch (error) {
                            console.error('Error saving transaction:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Menyimpan',
                                text: 'Pembayaran berhasil tapi gagal menyimpan ke database',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    },
                    onPending: function(result) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Menunggu Pembayaran',
                            text: 'Status: Pending. Silakan selesaikan pembayaran.',
                            confirmButtonColor: '#3b82f6'
                        });
                        btnBayar.disabled = false;
                        btnBayar.textContent = 'Bayar Sekarang';
                    },
                    onError: function(result) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: 'Terjadi kesalahan saat memproses pembayaran.',
                            confirmButtonColor: '#ef4444'
                        });
                        console.error(result);
                        currentOrderId = null;
                        btnBayar.disabled = false;
                        btnBayar.textContent = 'Bayar Sekarang';
                    },
                    onClose: function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pop-up Ditutup',
                            text: 'Anda menutup jendela pembayaran. Gunakan tombol "Batalkan Pembayaran" untuk membatalkan transaksi.',
                            confirmButtonColor: '#f59e0b'
                        });
                        btnBayar.disabled = false;
                        btnBayar.textContent = 'Bayar Sekarang';
                    }
                });
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal membuat token Midtrans: ' + error.message,
                    confirmButtonColor: '#ef4444'
                });
                currentOrderId = null;
                btnBayar.disabled = false;
                btnBayar.textContent = 'Bayar Sekarang';
            }
        }
    });

    // ========== TOMBOL BATAL ==========
    btnBatal.addEventListener("click", async function(e) {
        e.preventDefault();

        if (!currentOrderId) {
            Swal.fire({
                title: "Tidak Ada Transaksi",
                text: "Tidak ada transaksi yang perlu dibatalkan.",
                icon: "info",
                confirmButtonColor: "#3b82f6",
            });
            return;
        }

        Swal.fire({
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
                document.getElementById('btn-cancel-midtrans').addEventListener('click', async () => {
                    Swal.close();
                    await cancelViaMidtrans();
                });

                document.getElementById('btn-cancel-local').addEventListener('click', () => {
                    Swal.close();
                    cancelLocal();
                });
            }
        });
    });

    async function cancelViaMidtrans() {
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
                    `,
                    icon: "error",
                    confirmButtonColor: "#ef4444",
                });
            }
        } catch (error) {
            Swal.fire({
                title: "Error",
                text: "Terjadi kesalahan: " + error.message,
                icon: "error",
                confirmButtonColor: "#ef4444",
            });
        }
    }

    function cancelLocal() {
        Swal.fire({
            title: "Transaksi Dibatalkan",
            html: `
                <p>Transaksi lokal telah dibatalkan.</p>
                <p class="text-sm text-gray-600 mt-2">Transaksi di Midtrans akan expired otomatis.</p>
            `,
            icon: "info",
            confirmButtonColor: "#3b82f6",
        }).then(() => {
            currentOrderId = null;
            location.reload();
        });
    }

    // ========== AJAX UPDATE QUANTITY (TANPA RELOAD!) ==========
    document.querySelectorAll(".update-cart-form").forEach(form => {
        const stok = parseInt(form.dataset.stok);
        const qtySpan = form.querySelector(".qty-text");
        const btnPlus = form.querySelector(".btn-plus");
        const btnMinus = form.querySelector(".btn-minus");

        let qty = parseInt(qtySpan.innerText);

        // Buat hidden input untuk qty
        let qtyInput = form.querySelector('input[name="qty"]');
        if (!qtyInput) {
            qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = 'qty';
            form.appendChild(qtyInput);
        }

        function updateDisplay() {
            qtySpan.innerText = qty;
            qtyInput.value = qty;

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

        // ⚠️ PENTING: AJAX UPDATE - TIDAK RELOAD HALAMAN!
        function sendUpdateAjax(newQty) {
            const formData = new FormData(form);
            formData.set('qty', newQty);

            btnPlus.disabled = true;
            btnMinus.disabled = true;

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        qty = newQty;
                        updateDisplay();
                        updateRingkasanBelanja(data.cart);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan'
                        });
                        btnPlus.disabled = false;
                        btnMinus.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memperbarui keranjang'
                    });
                    btnPlus.disabled = false;
                    btnMinus.disabled = false;
                });
        }

        // ⚠️ UPDATE RINGKASAN BELANJA TANPA RELOAD
        function updateRingkasanBelanja(cart) {
            let newTotal = 0;
            let totalItems = 0;

            Object.values(cart).forEach(item => {
                newTotal += item.qty * item.harga;
                totalItems += item.qty;
            });

            // Update total harga
            document.getElementById('total-harga').textContent = formatRupiah(newTotal);
            document.getElementById('total-input').value = newTotal;

            // Update jumlah barang
            const ringkasanText = document.querySelector('.text-sm.mb-2 span:first-child');
            if (ringkasanText) {
                ringkasanText.textContent = `Total Harga (${totalItems} Barang)`;
            }

            // Hitung ulang subtotal dengan diskon
            const diskon = getAngka(diskonInput.value);
            const subtotal = Math.max(newTotal - diskon, 0);
            subtotalText.textContent = formatRupiah(subtotal);

            // ⚠️ UPDATE GLOBAL VARIABLE!
            totalHarga = newTotal;
        }

        // Handler tombol Plus
        btnPlus.addEventListener("click", function(e) {
            e.preventDefault();

            if (qty < stok) {
                sendUpdateAjax(qty + 1); // ⚠️ AJAX, BUKAN form.submit()!
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Stok Tidak Mencukupi!",
                    html: `
                        <p>Jumlah yang Anda pilih sudah mencapai batas stok.</p>
                        <p class="text-sm text-gray-600 mt-2">Stok tersedia: <strong>${stok}</strong></p>
                    `,
                    confirmButtonColor: "#ef4444"
                });
            }
        });

        // Handler tombol Minus
        btnMinus.addEventListener("click", function(e) {
            e.preventDefault();

            if (qty > 1) {
                sendUpdateAjax(qty - 1); // ⚠️ AJAX, BUKAN form.submit()!
            } else {
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
                        const itemCard = form.closest('.flex.items-start');
                        const removeButton = itemCard.querySelector('button[onclick*="remove-"]');
                        if (removeButton) {
                            const formId = removeButton.getAttribute('onclick').match(/remove-(\d+)/)[1];
                            document.getElementById('remove-' + formId).submit();
                        }
                    }
                });
            }
        });

        updateDisplay();
    });
</script>
@endsection