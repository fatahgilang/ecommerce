<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Services\ReportService;
use Carbon\Carbon;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.admin.pages.reports';
    
    protected static ?string $navigationLabel = 'Laporan';
    
    protected static ?string $title = 'Laporan';
    
    protected static ?string $navigationGroup = 'Laporan';
    
    protected static ?int $navigationSort = 10;

    public $reportType = 'sales';
    public $startDate;
    public $endDate;
    public $reportData = null;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function generateReport()
    {
        $service = new ReportService();

        switch($this->reportType) {
            case 'sales':
                $this->reportData = $service->salesReport($this->startDate, $this->endDate);
                break;
            case 'products':
                $this->reportData = $service->productReport($this->startDate, $this->endDate);
                break;
            case 'cashiers':
                $this->reportData = $service->cashierReport($this->startDate, $this->endDate);
                break;
            case 'cashflow':
                $this->reportData = $service->cashFlowReport($this->startDate, $this->endDate);
                break;
            case 'profitloss':
                $this->reportData = $service->profitLossReport($this->startDate, $this->endDate);
                break;
            case 'inventory':
                $this->reportData = $service->inventoryReport();
                break;
        }
    }

    public function exportReport()
    {
        $this->generateReport();
        
        if (!$this->reportData) {
            return;
        }
        
        // Prepare data for export based on report type
        $exportData = $this->prepareExportData();
        
        // Add summary rows at the beginning
        $exportData = $this->addSummaryToExport($exportData);
        
        $service = new ReportService();
        $filename = $this->reportType . '_report_' . $this->startDate . '_to_' . $this->endDate . '.csv';
        
        return $service->exportToCSV($exportData, $filename);
    }

    private function addSummaryToExport($data)
    {
        $summary = [];
        
        // Get column count from data
        $columnCount = !empty($data) ? count($data[0]) : 3;
        
        // Add report header - fill all columns
        $headerRow = [
            strtoupper($this->getReportTitle()),
            ($this->reportType !== 'inventory') ? $this->startDate . ' s/d ' . $this->endDate : 'Snapshot saat ini',
            date('Y-m-d H:i:s'),
        ];
        // Pad with empty strings to match column count
        while (count($headerRow) < $columnCount) {
            $headerRow[] = '';
        }
        $summary[] = $headerRow;
        
        // Add empty row
        $emptyRow = array_fill(0, $columnCount, '');
        $summary[] = $emptyRow;
        
        // Add summary data based on report type
        if (isset($this->reportData['summary'])) {
            $summaryHeaderRow = ['=== RINGKASAN ==='];
            while (count($summaryHeaderRow) < $columnCount) {
                $summaryHeaderRow[] = '';
            }
            $summary[] = $summaryHeaderRow;
            
            foreach ($this->reportData['summary'] as $key => $value) {
                $label = $this->formatSummaryLabel($key);
                $valueFormatted = is_numeric($value) ? number_format($value, 0, ',', '.') : $value;
                $summaryRow = [$label, $valueFormatted];
                while (count($summaryRow) < $columnCount) {
                    $summaryRow[] = '';
                }
                $summary[] = $summaryRow;
            }
            
            $summary[] = $emptyRow;
            
            $detailHeaderRow = ['=== DETAIL ==='];
            while (count($detailHeaderRow) < $columnCount) {
                $detailHeaderRow[] = '';
            }
            $summary[] = $detailHeaderRow;
        }
        
        // Merge summary with data
        return array_merge($summary, $data);
    }

    private function getReportTitle()
    {
        return match($this->reportType) {
            'sales' => 'Laporan Penjualan',
            'products' => 'Laporan Produk',
            'cashiers' => 'Laporan Kasir',
            'cashflow' => 'Laporan Kas',
            'profitloss' => 'Laporan Laba Rugi',
            'inventory' => 'Laporan Inventory',
            default => 'Laporan',
        };
    }

    private function formatSummaryLabel($key)
    {
        $labels = [
            'total_revenue' => 'Total Pendapatan',
            'total_transactions' => 'Total Transaksi',
            'average_transaction' => 'Rata-rata Transaksi',
            'daily_average' => 'Rata-rata Harian',
            'total_products_sold' => 'Total Produk Terjual',
            'unique_products' => 'Produk Unik',
            'out_of_stock_count' => 'Stok Habis',
            'total_cashiers' => 'Total Kasir',
            'cash_revenue' => 'Pendapatan Tunai',
            'non_cash_revenue' => 'Pendapatan Non-Tunai',
            'total_products' => 'Total Produk',
            'total_stock_qty' => 'Total Stok',
            'total_stock_value' => 'Nilai Stok',
            'low_stock' => 'Stok Menipis',
            'out_of_stock' => 'Stok Habis',
            'in_stock' => 'Stok Aman',
        ];
        
        return $labels[$key] ?? ucwords(str_replace('_', ' ', $key));
    }

    private function prepareExportData()
    {
        if (!$this->reportData) {
            return [];
        }

        $exportData = [];

        switch($this->reportType) {
            case 'sales':
                // Add header row
                $exportData[] = ['Periode', 'Jumlah Transaksi', 'Pendapatan'];
                
                // Add data rows
                foreach ($this->reportData['chart_data'] as $item) {
                    $exportData[] = [
                        $item['period'],
                        $item['transactions'],
                        $item['revenue'],
                    ];
                }
                break;
                
            case 'products':
                // Add header row
                $exportData[] = ['ID Produk', 'Nama Produk', 'Kategori', 'Total Terjual', 'Total Pendapatan', 'Jumlah Order', 'Stok Saat Ini', 'Nilai Stok'];
                
                // Add data rows
                foreach ($this->reportData['all_products'] as $item) {
                    $exportData[] = [
                        $item['product_id'],
                        $item['product_name'],
                        $item['category'],
                        $item['total_sold'],
                        $item['total_revenue'],
                        $item['order_count'],
                        $item['current_stock'],
                        $item['stock_value'],
                    ];
                }
                break;
                
            case 'cashiers':
                // Add header row
                $exportData[] = ['ID Kasir', 'Nama Kasir', 'Jumlah Transaksi', 'Total Pendapatan', 'Rata-rata Transaksi'];
                
                // Add data rows
                foreach ($this->reportData['cashiers'] as $item) {
                    $exportData[] = [
                        $item['cashier_id'],
                        $item['cashier_name'],
                        $item['transaction_count'],
                        $item['total_revenue'],
                        $item['average_transaction'],
                    ];
                }
                break;
                
            case 'cashflow':
                // Add header row
                $exportData[] = ['Tanggal', 'Jumlah Transaksi', 'Pendapatan Tunai', 'Pendapatan Non-Tunai', 'Total Pendapatan'];
                
                // Add data rows
                foreach ($this->reportData['daily_cash_flow'] as $item) {
                    $exportData[] = [
                        $item['date'],
                        $item['transactions'],
                        $item['cash'],
                        $item['non_cash'],
                        $item['revenue'],
                    ];
                }
                break;
                
            case 'profitloss':
                // Add header row
                $exportData[] = ['Kategori', 'Jumlah'];
                
                // Add data rows
                $exportData[] = ['Pendapatan Kotor', $this->reportData['revenue']['gross_revenue']];
                $exportData[] = ['Diskon', -$this->reportData['revenue']['discounts']];
                $exportData[] = ['Pendapatan Bersih', $this->reportData['revenue']['net_revenue']];
                $exportData[] = ['COGS', -$this->reportData['costs']['cogs']];
                $exportData[] = ['Biaya Operasional', -$this->reportData['costs']['operating_expenses']];
                $exportData[] = ['Laba Kotor', $this->reportData['profit']['gross_profit']];
                $exportData[] = ['Laba Bersih', $this->reportData['profit']['net_profit']];
                break;
                
            case 'inventory':
                // Add header row
                $exportData[] = ['ID Produk', 'Nama Produk', 'Kategori', 'Stok', 'Harga', 'Nilai Stok', 'Status'];
                
                // Add data rows
                foreach ($this->reportData['products'] as $item) {
                    $exportData[] = [
                        $item['product_id'],
                        $item['product_name'],
                        $item['category'],
                        $item['stock'],
                        $item['price'],
                        $item['stock_value'],
                        $item['status'],
                    ];
                }
                break;
                
            default:
                return [];
        }

        return $exportData;
    }
}
