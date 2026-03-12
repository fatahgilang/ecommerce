<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Selamat Datang, {{ auth()->user()->name }}! 👋
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">
                            Berikut adalah ringkasan aktivitas toko Anda hari ini
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ now()->isoFormat('dddd, D MMMM Y') }}
                        </p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- Total Penjualan Hari Ini -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Penjualan Hari Ini
                    </p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->sum('total_amount'), 0, ',', '.') }}
                    </h3>
                    <p class="mt-1 text-xs text-green-600 dark:text-green-400">
                        {{ \App\Models\Transaction::whereDate('created_at', today())->count() }} transaksi
                    </p>
                </div>
            </div>

            <!-- Total Pesanan -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Pesanan
                    </p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Order::count() }}
                    </h3>
                    <p class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">
                        {{ \App\Models\Order::where('status', 'pending')->count() }} menunggu
                    </p>
                </div>
            </div>

            <!-- Total Produk -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Produk
                    </p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\Product::count() }}
                    </h3>
                    <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                        {{ \App\Models\Product::where('stock', '<', 10)->count() }} stok rendah
                    </p>
                </div>
            </div>

            <!-- Total Users -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        Total Pengguna
                    </p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ \App\Models\User::count() }}
                    </h3>
                    <p class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                        {{ \App\Models\User::whereDate('created_at', '>=', now()->subDays(7))->count() }} baru minggu ini
                    </p>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <!-- Kasir Aktif -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Kasir Aktif
                    </h3>
                    <div class="space-y-3">
                        @forelse(\App\Models\CashRegister::where('status', 'open')->with('user')->latest()->take(5)->get() as $register)
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $register->register_name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $register->user->name }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                        Rp {{ number_format($register->total_sales, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $register->opened_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500">
                                <p>Tidak ada kasir aktif</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Transaksi Terbaru -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Transaksi Terbaru
                    </h3>
                    <div class="space-y-3">
                        @forelse(\App\Models\Transaction::with(['user'])->latest()->take(5)->get() as $transaction)
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $transaction->transaction_number }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $transaction->user->name }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </p>
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                        @if($transaction->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($transaction->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500">
                                <p>Belum ada transaksi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk Stok Rendah & Pesanan Pending -->
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <!-- Produk Stok Rendah -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Produk Stok Rendah
                    </h3>
                    <div class="space-y-3">
                        @forelse(\App\Models\Product::where('stock', '<', 10)->orderBy('stock')->take(5)->get() as $product)
                            <div class="flex items-center justify-between rounded-lg border border-red-200 bg-red-50 p-3 dark:border-red-800 dark:bg-red-900/20">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $product->product_name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Rp {{ number_format($product->product_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-800 dark:bg-red-900 dark:text-red-300">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500">
                                <p>Semua produk stok aman</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pesanan Pending -->
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-content p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Pesanan Menunggu
                    </h3>
                    <div class="space-y-3">
                        @forelse(\App\Models\Order::where('status', 'pending')->with(['product'])->latest()->take(5)->get() as $order)
                            <div class="flex items-center justify-between rounded-lg border border-yellow-200 bg-yellow-50 p-3 dark:border-yellow-800 dark:bg-yellow-900/20">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Pesanan #{{ $order->id }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $order->product->product_name }} ({{ $order->product_quantity }}x)
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500">
                                <p>Tidak ada pesanan pending</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
