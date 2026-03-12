/**
 * Thermal Printer Utility
 * Mendukung cetak struk ke printer thermal tanpa dialog print browser
 */

import qz from 'qz-tray';

class ThermalPrinter {
    constructor() {
        this.connected = false;
        this.printer = null;
    }

    /**
     * Inisialisasi koneksi ke QZ Tray
     */
    async connect() {
        if (this.connected) return true;

        try {
            // Check if QZ Tray is running
            if (!qz.websocket.isActive()) {
                await qz.websocket.connect();
            }
            
            this.connected = true;
            console.log('QZ Tray connected successfully');
            return true;
        } catch (error) {
            console.error('Failed to connect to QZ Tray:', error);
            throw new Error('QZ Tray tidak terdeteksi. Pastikan QZ Tray sudah terinstall dan berjalan.');
        }
    }

    /**
     * Disconnect dari QZ Tray
     */
    async disconnect() {
        if (qz.websocket.isActive()) {
            await qz.websocket.disconnect();
        }
        this.connected = false;
    }

    /**
     * Dapatkan daftar printer yang tersedia
     */
    async getPrinters() {
        await this.connect();
        return await qz.printers.find();
    }

    /**
     * Set printer default
     */
    setPrinter(printerName) {
        this.printer = printerName;
        localStorage.setItem('thermal_printer', printerName);
    }

    /**
     * Get printer yang dipilih
     */
    getPrinter() {
        if (this.printer) return this.printer;
        
        // Try to get from localStorage
        const saved = localStorage.getItem('thermal_printer');
        if (saved) {
            this.printer = saved;
            return saved;
        }
        
        return null;
    }

    /**
     * Format struk untuk printer thermal (58mm)
     */
    formatReceipt(orderData) {
        const lines = [];
        const width = 32; // Character width for 58mm thermal printer
        
        // Helper functions
        const center = (text) => {
            const padding = Math.max(0, Math.floor((width - text.length) / 2));
            return ' '.repeat(padding) + text;
        };
        
        const line = () => '='.repeat(width);
        const dottedLine = () => '-'.repeat(width);
        
        const leftRight = (left, right) => {
            const spaces = width - left.length - right.length;
            return left + ' '.repeat(Math.max(1, spaces)) + right;
        };

        // Header
        lines.push(center('TOKO MAKMUR JAYA'));
        lines.push(center('Jl. Contoh No. 123'));
        lines.push(center('Telp: 0812-3456-7890'));
        lines.push(line());
        lines.push('');
        
        // Order info
        lines.push(leftRight('No. Pesanan', '#' + orderData.order_id));
        lines.push(leftRight('Tanggal', orderData.date));
        lines.push(leftRight('Kasir', orderData.cashier || 'Frontend'));
        lines.push(leftRight('Pembayaran', orderData.payment_method.toUpperCase()));
        lines.push(dottedLine());
        lines.push('');
        
        // Items
        orderData.items.forEach(item => {
            // Product name
            lines.push(item.product_name);
            
            // Quantity x Price = Total
            const qtyPrice = `${item.quantity} x ${this.formatPrice(item.price)}`;
            const total = this.formatPrice(item.total);
            lines.push(leftRight(qtyPrice, total));
            lines.push('');
        });
        
        lines.push(dottedLine());
        
        // Subtotal
        lines.push(leftRight('Subtotal', this.formatPrice(orderData.subtotal)));
        
        // Discount if any
        if (orderData.discount_amount && orderData.discount_amount > 0) {
            lines.push(leftRight('Diskon (' + (orderData.discount_code || '') + ')', '-' + this.formatPrice(orderData.discount_amount)));
        }
        
        lines.push(line());
        
        // Total
        lines.push(leftRight('TOTAL', this.formatPrice(orderData.total)));
        lines.push(line());
        lines.push('');
        
        // Payment info
        if (orderData.payment_method === 'cash') {
            lines.push(center('PEMBAYARAN TUNAI'));
        } else {
            lines.push(center('TRANSFER ' + orderData.payment_method.toUpperCase()));
        }
        
        lines.push('');
        lines.push(center('Terima Kasih'));
        lines.push(center('Atas Kunjungan Anda'));
        lines.push('');
        lines.push('');
        lines.push('');
        
        return lines.join('\n');
    }

    /**
     * Format harga ke Rupiah
     */
    formatPrice(price) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(price);
    }

    /**
     * Print struk
     */
    async print(orderData) {
        try {
            await this.connect();
            
            const printer = this.getPrinter();
            if (!printer) {
                throw new Error('Printer belum dipilih. Silakan pilih printer terlebih dahulu.');
            }

            // Format receipt
            const receipt = this.formatReceipt(orderData);
            
            // Create print config
            const config = qz.configs.create(printer);
            
            // Create print data (ESC/POS commands for thermal printer)
            const data = [
                '\x1B\x40', // Initialize printer
                '\x1B\x61\x01', // Center align
                receipt,
                '\x1B\x64\x03', // Feed 3 lines
                '\x1D\x56\x00', // Cut paper (full cut)
            ];

            // Print
            await qz.print(config, data);
            
            console.log('Print successful');
            return true;
        } catch (error) {
            console.error('Print failed:', error);
            throw error;
        }
    }

    /**
     * Print dengan fallback ke browser print
     */
    async printWithFallback(orderData) {
        try {
            // Try thermal printer first
            await this.print(orderData);
            return { success: true, method: 'thermal' };
        } catch (error) {
            console.warn('Thermal print failed, falling back to browser print:', error);
            
            // Fallback to browser print
            this.printToBrowser(orderData);
            return { success: true, method: 'browser' };
        }
    }

    /**
     * Print menggunakan browser print dialog (fallback)
     */
    printToBrowser(orderData) {
        const receipt = this.formatReceipt(orderData);
        
        // Create print window
        const printWindow = window.open('', '_blank', 'width=300,height=600');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Struk Pesanan #${orderData.order_id}</title>
                <style>
                    @media print {
                        @page {
                            size: 58mm auto;
                            margin: 0;
                        }
                    }
                    body {
                        font-family: 'Courier New', monospace;
                        font-size: 12px;
                        line-height: 1.4;
                        margin: 0;
                        padding: 10px;
                        width: 58mm;
                    }
                    pre {
                        margin: 0;
                        white-space: pre-wrap;
                        word-wrap: break-word;
                    }
                </style>
            </head>
            <body>
                <pre>${receipt}</pre>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() {
                            window.close();
                        }, 100);
                    };
                </script>
            </body>
            </html>
        `);
        
        printWindow.document.close();
    }
}

// Export singleton instance
export default new ThermalPrinter();
