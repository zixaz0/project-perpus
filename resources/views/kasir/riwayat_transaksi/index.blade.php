@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600 mb-4" aria-label="breadcrumb">
        <ol class="list-reset flex items-center space-x-2">
            <li>
                <a href="{{ route('kasir.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li>/</li>
            <a href="" class="text-blue-600 hover:underline">Riwayat Transaksi</a>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-history text-indigo-600"></i> Riwayat Transaksi
        </h1>

        @forelse($transaksi as $t)
            @php
                // Cek apakah masih dalam 24 jam
                $batasWaktu = \Carbon\Carbon::parse($t->created_at)->addHours(24);
                $masihBisaRefund = now()->lessThanOrEqualTo($batasWaktu);

                // Format sisa waktu dalam jam dan menit
                if ($masihBisaRefund) {
                    $diffInMinutes = now()->diffInMinutes($batasWaktu);
                    $hours = floor($diffInMinutes / 60);
                    $minutes = $diffInMinutes % 60;

                    if ($hours > 0) {
                        $sisaWaktu = $hours . ' jam' . ($minutes > 0 ? ' ' . $minutes . ' menit' : '');
                    } else {
                        $sisaWaktu = $minutes . ' menit';
                    }
                } else {
                    $sisaWaktu = null;
                }
            @endphp

            <div
                class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition mb-8 overflow-hidden border-l-4 {{ $t->status === 'refund' ? 'border-red-500' : 'border-indigo-500' }}">
                <!-- Header -->
                <div
                    class="px-6 py-4 border-b {{ $t->status === 'refund' ? 'bg-gradient-to-r from-red-50 to-white' : 'bg-gradient-to-r from-indigo-50 to-white' }} flex justify-between items-center">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs text-gray-500">ID Transaksi</p>
                            @if ($t->status === 'refund')
                                <span class="px-2 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-full">REFUND</span>
                            @elseif(!$masihBisaRefund)
                                <span class="px-2 py-1 text-xs font-bold bg-gray-100 text-gray-600 rounded-full">
                                    <i class="fas fa-lock"></i> Refund Expired
                                </span>
                            @endif
                        </div>
                        <p class="font-bold text-gray-800">#{{ $t->id }}</p>
                        <p class="text-sm text-gray-600 mt-1">Kasir: <span
                                class="font-medium">{{ $t->kasir->name ?? '-' }}</span></p>
                        <p class="text-sm text-gray-600">Tanggal: {{ $t->created_at->format('d M Y, H:i') }}</p>

                        @if ($t->status === 'refund' && $t->refund_at)
                            <p class="text-xs text-red-600 mt-1">
                                <i class="fas fa-undo"></i> Refund pada
                                {{ \Carbon\Carbon::parse($t->refund_at)->format('d M Y, H:i') }}
                                @if ($t->refundBy)
                                    oleh {{ $t->refundBy->name }}
                                @endif
                            </p>
                        @elseif($masihBisaRefund && ($t->status === 'selesai' || !$t->status))
                            <p class="text-xs text-orange-600 mt-1">
                                <i class="fas fa-clock"></i> Batas refund: {{ $sisaWaktu }} lagi
                            </p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p
                            class="text-2xl font-extrabold {{ $t->status === 'refund' ? 'text-red-600 line-through' : 'text-indigo-600' }}">
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </p>
                        <div class="flex gap-2 mt-3">
                            <a href="{{ route('kasir.transaksi.struk', $t->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md">
                                <i class="fas fa-receipt"></i> Lihat Struk
                            </a>

                            {{-- Button refund hanya muncul jika: status selesai/null DAN masih dalam 24 jam --}}
                            @if (($t->status === 'selesai' || !$t->status) && $masihBisaRefund)
                                <button onclick="openRefundModal({{ $t->id }})"
                                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-md">
                                    <i class="fas fa-undo"></i> Refund
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr
                                class="{{ $t->status === 'refund' ? 'bg-red-100 text-red-700' : 'bg-indigo-100 text-indigo-700' }} text-sm uppercase">
                                <th class="px-5 py-3 text-left">Buku</th>
                                <th class="px-5 py-3 text-center">Qty</th>
                                <th class="px-5 py-3 text-right">Harga</th>
                                <th class="px-5 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                            @foreach ($t->items as $item)
                                <tr class="hover:bg-gray-50 {{ $t->status === 'refund' ? 'opacity-60' : '' }}">
                                    <td class="px-5 py-3">{{ $item->buku->judul_buku }}</td>
                                    <td class="px-5 py-3 text-center">{{ $item->qty }}</td>
                                    <td class="px-5 py-3 text-right">
                                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">Belum ada transaksi.</p>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $transaksi->links() }}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        async function openRefundModal(transaksiId) {
            const {
                value: password
            } = await Swal.fire({
                title: '<div class="text-2xl font-bold text-gray-800 mb-2"><i class="fas fa-lock text-red-600 mr-2"></i> Verifikasi Admin</div>',
                html: `
            <div class="text-left space-y-4">
                <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-red-900 mb-1">Perhatian!</h4>
                            <p class="text-sm text-red-700 leading-relaxed">
                                Proses refund akan <strong>membatalkan transaksi</strong> dan mengembalikan stok barang. 
                                Diperlukan password admin untuk melanjutkan.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 text-blue-800">
                        <i class="fas fa-info-circle"></i>
                        <span class="text-sm font-medium">Silakan panggil admin untuk memasukkan password</span>
                    </div>
                </div>
            </div>
        `,
                input: 'password',
                inputLabel: '',
                inputPlaceholder: 'Masukkan password admin',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off',
                    class: 'swal2-input-custom'
                },
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check-circle mr-2"></i>Proses Refund',
                cancelButtonText: '<i class="fas fa-times-circle mr-2"></i>Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                buttonsStyling: false,
                customClass: {
                    popup: 'swal-refund-popup',
                    htmlContainer: 'swal-html-custom',
                    input: 'swal-input-custom',
                    confirmButton: 'swal-confirm-btn',
                    cancelButton: 'swal-cancel-btn',
                    actions: 'swal-actions-custom'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return '<i class="fas fa-lock mr-2"></i> Password admin wajib diisi!'
                    }
                    if (value.length < 3) {
                        return '<i class="fas fa-lock mr-2"></i> Password terlalu pendek!'
                    }
                },
                showLoaderOnConfirm: true,
                preConfirm: async (password) => {
                    try {
                        const response = await fetch(`/kasir/refund/${transaksiId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                admin_password: password
                            })
                        });

                        const data = await response.json();

                        if (!data.success) {
                            throw new Error(data.message || 'Refund gagal diproses');
                        }

                        return data;
                    } catch (error) {
                        Swal.showValidationMessage(
                            `<i class="fas fa-exclamation-circle mr-2"></i>${error.message}`
                        );
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            });

            if (password) {
                await Swal.fire({
                    icon: 'success',
                    title: '<div class="text-2xl font-bold text-green-600">Refund Berhasil!</div>',
                    html: '<p class="text-gray-600">Transaksi berhasil di-refund dan stok barang telah dikembalikan.</p>',
                    confirmButtonColor: '#10b981',
                    confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'swal-success-btn',
                        popup: 'swal-success-popup'
                    }
                });
                window.location.reload();
            }
        }
    </script>

    <style>
        /* Popup Styling */
        .swal-refund-popup {
            border-radius: 1.5rem !important;
            padding: 2rem !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            max-width: 560px !important;
            width: 100% !important;
        }

        .swal-success-popup {
            border-radius: 1.5rem !important;
            padding: 2rem !important;
        }

        .swal-html-custom {
            margin: 1.5rem 0 !important;
            padding: 0 !important;
            text-align: left !important;
        }

        /* Title Styling */
        .swal2-title {
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        /* Fix validation message alignment */
        .swal2-validation-message {
            text-align: center !important;
            margin: 0.5rem 0 0 0 !important;
        }

        /* Input Styling */
        .swal-input-custom {
            width: 100% !important;
            padding: 0.875rem 1rem !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            font-size: 1rem !important;
            transition: all 0.3s ease !important;
            margin: 0.5rem 0 0 0 !important;
            box-sizing: border-box !important;
        }

        .swal-input-custom:focus {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
            outline: none !important;
        }

        /* Input Label */
        .swal2-input-label {
            text-align: left !important;
            margin-bottom: 0 !important;
        }

        /* Button Styling */
        .swal-actions-custom {
            gap: 0.75rem !important;
            margin-top: 1.5rem !important;
            display: flex !important;
            justify-content: center !important;
        }

        .swal-confirm-btn {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            color: white !important;
            padding: 0.875rem 2rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .swal-confirm-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.4) !important;
        }

        .swal-cancel-btn {
            background: #f3f4f6 !important;
            color: #4b5563 !important;
            padding: 0.875rem 2rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            border: 2px solid #e5e7eb !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .swal-cancel-btn:hover {
            background: #e5e7eb !important;
            transform: translateY(-2px) !important;
        }

        .swal-success-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
            padding: 0.875rem 2.5rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .swal-success-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4) !important;
        }

        /* Loader Animation */
        .swal2-loader {
            border-color: #dc2626 transparent #dc2626 transparent !important;
        }
    </style>
@endpush