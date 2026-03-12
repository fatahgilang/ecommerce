<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Report Type Selection -->
        <x-filament::section>
            <x-slot name="heading">
                Pilih Jenis Laporan
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model="reportType">
                        <option value="sales">Laporan Penjualan</option>
                        <option value="products">Laporan Produk</option>
                        <option value="cashiers">Laporan Kasir</option>
                        <option value="cashflow">Laporan Kas</option>
                        <option value="profitloss">Laporan Laba Rugi</option>
                        <option value="inventory">Laporan Inventory</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                @if($reportType !== 'inventory')
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="date"
                            wire:model="startDate"
                            placeholder="Tanggal Mulai"
                        />
                    </x-filament::input.wrapper>

                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="date"
                            wire:model="endDate"
                            placeholder="Tanggal Akhir"
                        />
                    </x-filament::input.wrapper>
                @endif
            </div>

            <div class="mt-4 flex gap-2">
                <x-filament::button wire:click="generateReport">
                    Generate Laporan
                </x-filament::button>

                @if($reportData)
                    <x-filament::button color="success" wire:click="exportReport">
                        Export CSV
                    </x-filament::button>
                @endif
            </div>
        </x-filament::section>

        <!-- Report Display -->
        @if($reportData)
            @if($reportType === 'sales')
                @include('filament.admin.reports.sales')
            @elseif($reportType === 'products')
                @include('filament.admin.reports.products')
            @elseif($reportType === 'cashiers')
                @include('filament.admin.reports.cashiers')
            @elseif($reportType === 'cashflow')
                @include('filament.admin.reports.cashflow')
            @elseif($reportType === 'profitloss')
                @include('filament.admin.reports.profitloss')
            @elseif($reportType === 'inventory')
                @include('filament.admin.reports.inventory')
            @endif
        @endif
    </div>
</x-filament-panels::page>
