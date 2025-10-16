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
    <div class="container mx-auto px-6 py-8 flex gap-6">
        <!-- Main Content -->
        <div class="flex-1 transition-all duration-300" id="mainContent">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-book text-indigo-600"></i> Data Buku
                </h1>
            </div>

            <!-- Filter & Search Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Buku</h2>

                    <form action="{{ route('kasir.buku.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                        <!-- Toggle Hanya Tersedia -->
                        <label class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg">
                            <input type="checkbox" id="toggleAvailable"
                                class="h-4 w-4 text-indigo-600 rounded cursor-pointer" checked>
                            <span class="text-sm text-gray-700">Hanya tersedia</span>
                        </label>

                        <!-- Search Input -->
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul / kode..."
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                        <!-- Kategori Dropdown -->
                        <select name="kategori_id"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
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

                        <!-- Search Button -->
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg cursor-pointer transition duration-200 flex items-center gap-2">
                            <i class="fa fa-search"></i>
                            <span>Cari</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Grid Buku -->
            <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @php $cart = session('cart', []); @endphp
                @forelse($buku as $item)
                    @php
                        $inCart = isset($cart[$item->id]);
                        $stok = $item->stokHarga->stok ?? 0;
                    @endphp
                    <div class="book-card relative bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-4 flex flex-col
                    {{ $stok <= 0 ? 'opacity-50' : 'hover:-translate-y-1' }}"
                        data-title="{{ strtolower($item->judul_buku) }} {{ strtolower($item->kode_buku) }} {{ strtolower($item->kategori->kategori ?? '') }}"
                        data-stok="{{ $stok }}"
                        data-book-id="{{ $item->id }}">

                        <div class="relative w-full mb-4" style="padding-top: 150%;">
                            <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover {{ $item->judul_buku }}"
                                class="absolute inset-0 w-full h-full object-cover rounded-lg 
                                    {{ $stok <= 0 ? 'opacity-40' : '' }}">

                            @if ($stok <= 0)
                                <div class="absolute inset-0 flex items-center justify-center rounded-lg">
                                    <span class="bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                                        Stok Habis
                                    </span>
                                </div>
                            @else
                                <div
                                    class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                    {{ $stok }} pcs
                                </div>
                            @endif
                        </div>

                        <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $item->judul_buku }}</h3>

                        <div class="space-y-2 mb-4">
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i class="fas fa-tag text-blue-500 w-4"></i>
                                <span class="truncate">{{ $item->kategori->kategori ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-600 flex items-center gap-2">
                                <i class="fas fa-calendar text-green-500 w-4"></i>
                                {{ $item->tahun_terbit->format('Y') }}
                            </p>
                            <p class="text-lg font-bold text-indigo-600 flex items-center gap-2">
                                <i class="fas fa-money-bill-wave w-4"></i>
                                {{ $item->stokHarga ? 'Rp ' . number_format($item->stokHarga->harga, 0, ',', '.') : '-' }}
                            </p>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-auto flex gap-2" id="actions-{{ $item->id }}">
                            <!-- Tombol Detail -->
                            <button onclick="showDetail({{ $item->id }})"
                                class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg shadow-md transition-all duration-200 font-medium cursor-pointer hover:shadow-lg">
                                <i class="fas fa-eye"></i> Detail
                            </button>

                            @if ($stok > 0)
                                @if (!$inCart)
                                    <!-- Tombol Add to Cart -->
                                    <form action="{{ route('kasir.transaksi.add', $item) }}" method="POST"
                                        class="w-1/2 add-cart-form">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg shadow-md transition-all duration-200 font-medium cursor-pointer hover:shadow-lg">
                                            <i class="fas fa-cart-plus"></i> Tambah
                                        </button>
                                    </form>
                                @else
                                    <!-- Counter Quantity -->
                                    <form action="{{ route('kasir.transaksi.update', $item) }}" method="POST"
                                        class="flex w-1/2 border-2 border-gray-200 rounded-lg overflow-hidden update-cart-form"
                                        data-stok="{{ $stok }}"
                                        data-book-id="{{ $item->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="qty" value="{{ $cart[$item->id]['qty'] - 1 }}"
                                            class="px-3 bg-gray-100 hover:bg-gray-200 cursor-pointer transition font-bold text-gray-700 btn-minus">−</button>
                                        <span
                                            class="flex-1 text-center py-2 font-semibold text-gray-800 bg-white qty-display">{{ $cart[$item->id]['qty'] }}</span>
                                        <button type="submit" name="qty" value="{{ $cart[$item->id]['qty'] + 1 }}"
                                            class="px-3 bg-gray-100 hover:bg-gray-200 cursor-pointer transition font-bold text-gray-700 btn-plus">+</button>
                                    </form>
                                @endif
                            @else
                                <!-- Tombol Disabled untuk Stok Habis -->
                                <button disabled
                                    class="w-1/2 bg-gray-400 text-white py-2.5 rounded-lg shadow cursor-not-allowed font-medium">
                                    <i class="fas fa-ban"></i> Habis
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Belum ada buku.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 🛒 SIDEBAR CART (Desktop) -->
        <div id="cartSidebar" class="hidden lg:block fixed right-0 top-0 h-full transition-all duration-300 ease-in-out z-40"
             style="width: 384px; transform: translateX(384px);">
            <div class="h-full bg-white shadow-2xl border-l-2 border-gray-200 flex flex-col">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 p-5 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-shopping-cart text-2xl"></i>
                            <div>
                                <h3 class="text-xl font-bold">Keranjang</h3>
                                <p class="text-sm text-green-100" id="cartCount">0 item</p>
                            </div>
                        </div>
                        <button onclick="clearCartConfirm()"
                            class="group relative cursor-pointer hover:bg-green-800 p-2 rounded-lg transition"
                            title="Kosongkan keranjang">
                            <i class="fas fa-trash-alt text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Cart Items -->
                <div id="cartItems" class="flex-1 p-4 space-y-3 overflow-y-auto">
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-shopping-basket text-5xl mb-3"></i>
                        <p class="text-sm">Keranjang masih kosong</p>
                    </div>
                </div>

                <!-- Summary & Checkout -->
                <div id="cartSummary" class="hidden border-t-2 border-gray-200 p-5 bg-gray-50">
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-700">
                            <span class="font-medium">Total Item:</span>
                            <span id="totalQty" class="font-bold text-indigo-600">0 pcs</span>
                        </div>
                        <div class="flex justify-between text-gray-900 text-lg pt-2 border-t border-gray-300">
                            <span class="font-bold">Total Harga:</span>
                            <span id="totalPrice" class="font-bold text-green-600">Rp 0</span>
                        </div>
                    </div>
                    <a href="{{ route('kasir.transaksi.index') }}"
                        class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg font-bold transition-all duration-200 hover:shadow-lg cursor-pointer">
                        <i class="fas fa-cash-register"></i> Checkout Sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- 🔘 Desktop Cart Toggle Button -->
        <button id="desktopCartToggle" onclick="toggleDesktopCart()"
            class="hidden lg:flex fixed right-0 top-1/2 -translate-y-1/2 bg-green-600 hover:bg-green-700 text-white px-3 py-4 rounded-l-xl shadow-2xl items-center justify-center z-50 transition-all duration-300"
            style="transform: translateY(-50%);">
            <div class="flex flex-col items-center gap-2">
                <i class="fas fa-shopping-cart text-xl"></i>
                <span id="desktopCartBadge"
                    class="hidden bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">0</span>
                <i id="toggleIcon" class="fas fa-chevron-left text-sm"></i>
            </div>
        </button>

        <!-- 📱 Mobile Cart Button (Floating) -->
        <button id="mobileCartBtn" onclick="toggleMobileCart()"
            class="lg:hidden fixed bottom-6 right-6 bg-green-600 hover:bg-green-700 text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center z-50 transition-all duration-200 hover:scale-110">
            <i class="fas fa-shopping-cart text-2xl"></i>
            <span id="mobileCartBadge"
                class="hidden absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">0</span>
        </button>

        <!-- 📱 Mobile Cart Modal -->
        <div id="mobileCartModal" class="lg:hidden hidden fixed inset-0 bg-black bg-opacity-50 z-50"
            onclick="toggleMobileCart()">
            <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl max-h-[80vh] flex flex-col"
                onclick="event.stopPropagation()">
                <!-- Mobile Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 p-5 text-white rounded-t-3xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-shopping-cart text-2xl"></i>
                            <div>
                                <h3 class="text-xl font-bold">Keranjang</h3>
                                <p class="text-sm text-green-100" id="mobileCartCount">0 item</p>
                            </div>
                        </div>
                        <button onclick="toggleMobileCart()" class="hover:bg-green-800 p-2 rounded-lg transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Cart Items -->
                <div id="mobileCartItems" class="flex-1 p-4 space-y-3 overflow-y-auto">
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-shopping-basket text-5xl mb-3"></i>
                        <p class="text-sm">Keranjang masih kosong</p>
                    </div>
                </div>

                <!-- Mobile Summary -->
                <div id="mobileCartSummary" class="hidden border-t-2 border-gray-200 p-5 bg-gray-50">
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-700">
                            <span class="font-medium">Total Item:</span>
                            <span id="mobileTotalQty" class="font-bold text-indigo-600">0 pcs</span>
                        </div>
                        <div class="flex justify-between text-gray-900 text-lg pt-2 border-t border-gray-300">
                            <span class="font-bold">Total Harga:</span>
                            <span id="mobileTotalPrice" class="font-bold text-green-600">Rp 0</span>
                        </div>
                    </div>
                    <a href="{{ route('kasir.transaksi.index') }}"
                        class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 rounded-lg font-bold transition-all duration-200 cursor-pointer">
                        <i class="fas fa-cash-register"></i> Checkout Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Script -->
    <script>
        document.getElementById('toggleAvailable').addEventListener('change', function() {
            const bookCards = document.querySelectorAll('.book-card ');
            bookCards.forEach(card => {
                const stok = parseInt(card.getAttribute('data-stok'));
                if (this.checked) {
                    if (stok <= 0) card.style.display = 'none';
                } else {
                    card.style.display = 'flex';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('toggleAvailable');
            if (toggle.checked) {
                const bookCards = document.querySelectorAll('.book-card');
                bookCards.forEach(card => {
                    const stok = parseInt(card.getAttribute('data-stok'));
                    if (stok <= 0) card.style.display = 'none';
                });
            }
        });
    </script>

    <!-- Main Script -->
    <script>
        const cart = {!! json_encode($cart) !!};
        const allBooks = @json($buku->items());
        let isCartOpen = false;

        // 🔄 Toggle Desktop Cart
        function toggleDesktopCart() {
            const sidebar = document.getElementById('cartSidebar');
            const toggleBtn = document.getElementById('desktopCartToggle');
            const toggleIcon = document.getElementById('toggleIcon');
            
            isCartOpen = !isCartOpen;
            
            if (isCartOpen) {
                sidebar.style.transform = 'translateX(0)';
                toggleBtn.style.right = '384px';
                toggleIcon.className = 'fas fa-chevron-right text-sm';
            } else {
                sidebar.style.transform = 'translateX(384px)';
                toggleBtn.style.right = '0';
                toggleIcon.className = 'fas fa-chevron-left text-sm';
            }
        }

        // 🛒 Render Cart Sidebar
        function renderCart() {
            const cartItemsDesktop = document.getElementById('cartItems');
            const cartItemsMobile = document.getElementById('mobileCartItems');
            const cartSummary = document.getElementById('cartSummary');
            const mobileCartSummary = document.getElementById('mobileCartSummary');

            const cartKeys = Object.keys(cart);

            if (cartKeys.length === 0) {
                const emptyHTML = `
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-shopping-basket text-5xl mb-3"></i>
                        <p class="text-sm">Keranjang masih kosong</p>
                    </div>
                `;
                cartItemsDesktop.innerHTML = emptyHTML;
                cartItemsMobile.innerHTML = emptyHTML;
                cartSummary.classList.add('hidden');
                mobileCartSummary.classList.add('hidden');
                document.getElementById('mobileCartBadge').classList.add('hidden');
                document.getElementById('desktopCartBadge').classList.add('hidden');
                document.getElementById('cartSidebar').classList.add('hidden');
                document.getElementById('desktopCartToggle').classList.add('hidden');
                return;
            }

            let itemsHTML = '';
            let totalQty = 0;
            let totalPrice = 0;

            cartKeys.forEach(bookId => {
                const cartItem = cart[bookId];
                const book = allBooks.find(b => b.id == bookId);

                if (book) {
                    const subtotal = cartItem.harga * cartItem.qty;
                    totalQty += cartItem.qty;
                    totalPrice += subtotal;

                    itemsHTML += `
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-green-300 transition">
                            <div class="flex gap-3">
                                <img src="/storage/${book.cover_buku}" 
                                     class="w-16 h-20 object-cover rounded-lg shadow-sm"
                                     alt="${book.judul_buku}">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-sm text-gray-800 mb-1 line-clamp-2">${book.judul_buku}</h4>
                                    <p class="text-xs text-gray-500 mb-2">Qty: ${cartItem.qty} × Rp ${new Intl.NumberFormat('id-ID').format(cartItem.harga)}</p>
                                    <p class="text-sm font-bold text-green-600">Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            cartItemsDesktop.innerHTML = itemsHTML;
            cartItemsMobile.innerHTML = itemsHTML;

            document.getElementById('cartCount').textContent = `${totalQty} item`;
            document.getElementById('mobileCartCount').textContent = `${totalQty} item`;
            document.getElementById('totalQty').textContent = `${totalQty} pcs`;
            document.getElementById('mobileTotalQty').textContent = `${totalQty} pcs`;
            document.getElementById('totalPrice').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
            document.getElementById('mobileTotalPrice').textContent =
                `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;

            cartSummary.classList.remove('hidden');
            mobileCartSummary.classList.remove('hidden');

            const mobileBadge = document.getElementById('mobileCartBadge');
            const desktopBadge = document.getElementById('desktopCartBadge');
            mobileBadge.textContent = totalQty;
            desktopBadge.textContent = totalQty;
            mobileBadge.classList.remove('hidden');
            desktopBadge.classList.remove('hidden');

            document.getElementById('cartSidebar').classList.remove('hidden');
            document.getElementById('desktopCartToggle').classList.remove('hidden');
        }

        renderCart();

        // 📱 Toggle Mobile Cart
        function toggleMobileCart() {
            const modal = document.getElementById('mobileCartModal');
            modal.classList.toggle('hidden');
        }

        // 🗑️ Clear Cart
        function clearCartConfirm() {
            if (Object.keys(cart).length === 0) return;

            Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: "Semua item akan dihapus dari keranjang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kosongkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('kasir.transaksi.clear') }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // ============ AJAX HANDLERS ============

        // Handle Add to Cart dengan AJAX
        document.querySelectorAll(".add-cart-form").forEach(form => {
            form.addEventListener("submit", function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const button = this.querySelector('button');
                const originalHTML = button.innerHTML;
                
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Object.keys(cart).forEach(key => delete cart[key]);
                        Object.assign(cart, data.cart);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menambahkan ke keranjang'
                    });
                });
            });
        });

        // Handle Update Cart dengan AJAX
        document.querySelectorAll(".update-cart-form").forEach(form => {
            const plusButton = form.querySelector(".btn-plus");
            const minusButton = form.querySelector(".btn-minus");
            
            [plusButton, minusButton].forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    
                    const stok = parseInt(form.dataset.stok);
                    const bookId = form.dataset.bookId;
                    const qtyDisplay = form.querySelector(".qty-display");
                    const currentQty = parseInt(qtyDisplay.textContent);
                    const newQty = parseInt(this.value);

                    if (this === plusButton && currentQty >= stok) {
                        Swal.fire({
                            icon: "warning",
                            title: "Stok terbatas!",
                            text: `Jumlah tidak boleh melebihi stok (${stok}).`,
                            confirmButtonColor: "#2563eb"
                        });
                        return;
                    }

                    if (newQty <= 0) {
                        Swal.fire({
                            title: 'Hapus dari keranjang?',
                            text: "Item akan dihapus dari keranjang",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                sendUpdateRequest(form, newQty, plusButton, minusButton, qtyDisplay, bookId);
                            }
                        });
                        return;
                    }

                    sendUpdateRequest(form, newQty, plusButton, minusButton, qtyDisplay, bookId);
                });
            });
        });

        function sendUpdateRequest(form, newQty, plusButton, minusButton, qtyDisplay, bookId) {
            plusButton.disabled = true;
            minusButton.disabled = true;
            
            const formData = new FormData(form);
            formData.set('qty', newQty);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Object.keys(cart).forEach(key => delete cart[key]);
                    Object.assign(cart, data.cart);
                    
                    if (newQty <= 0 || !data.cart[bookId]) {
                        location.reload();
                        return;
                    }
                    
                    qtyDisplay.textContent = newQty;
                    minusButton.value = newQty - 1;
                    plusButton.value = newQty + 1;
                    renderCart();
                    
                    plusButton.disabled = false;
                    minusButton.disabled = false;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan'
                    });
                    plusButton.disabled = false;
                    minusButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui keranjang'
                });
                plusButton.disabled = false;
                minusButton.disabled = false;
            });
        }

        // 📖 Detail buku
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
                <div class="flex-shrink-0 mx-auto md:mx-0">
                    <img src="/storage/${item.cover_buku}" 
                         class="w-44 h-64 object-cover rounded-xl shadow-lg border border-gray-200">
                </div>
                <div class="flex-1 text-left space-y-4">
                    <h2 class="text-2xl font-bold text-gray-800 border-b pb-2">
                        ${item.judul_buku}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-sm md:text-base text-gray-700">
                        <p><i class="fas fa-barcode text-indigo-600"></i> <b>Kode:</b> ${item.kode_buku}</p>
                        <p><i class="fas fa-cubes text-indigo-600"></i> <b>Stok:</b> ${item.stok_harga ? item.stok_harga.stok + " pcs" : '-'}</p>
                        <p><i class="fas fa-building text-indigo-600"></i> <b>Penerbit:</b> ${item.penerbit}</p>
                        <p><i class="fas fa-dollar-sign text-indigo-600"></i> <b>Harga:</b> ${item.stok_harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.stok_harga.harga) : '-'}</p>
                        <p><i class="fas fa-user text-indigo-600"></i> <b>Pengarang:</b> ${item.pengarang}</p>
                        <p><i class="fas fa-calendar text-indigo-600"></i> <b>Tahun Terbit:</b> ${new Date(item.tahun_terbit).getFullYear()}</p>
                        <p><i class="fas fa-tags text-indigo-600"></i> <b>Kategori:</b> ${item.kategori ? item.kategori.kategori : '-'}</p>
                        <p><i class="fas fa-list text-indigo-600"></i> <b>Jenis:</b> ${item.kategori ? item.kategori.jenis : '-'}</p>
                    </div>
                </div>
            </div>
        `
            });
        }
    </script>
@endsection