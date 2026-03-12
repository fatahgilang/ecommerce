# Fitur Transaksi Otomatis dari Pesanan

## Deskripsi
Sistem otomatis yang membuat transaksi di menu Transaksi ketika pesanan diubah statusnya menjadi "Sudah Dibayar" (paid). Sistem ini juga mencatat kasir yang memproses pesanan tersebut.

## Cara Kerja

### 1. Trigger Otomatis
- Transaksi dibuat otomatis ketika status pesanan diubah dari status apapun menjadi "paid"
- Menggunakan Observer pattern untuk mendeteksi perubahan status
- Hanya berjalan sekali per pesanan (tidak akan membuat transaksi duplikat)

### 2. Data yang Dicatat
- **Nomor Transaksi**: Dibuat otomatis dengan format `TRX-YYYYMMDD-XXXX`
- **Kasir**: User yang sedang login saat mengubah status pesanan
- **Waktu Diproses**: Timestamp saat status diubah ke "paid"
- **Cash Register**: Menggunakan kasir yang sedang aktif (status 'open')
- **Metode Pembayaran**: Dipetakan dari metode pembayaran pesanan

### 3. Pemetaan Metode Pembayaran
```php
'bca' => 'transfer'
'mandiri' => 'transfer' 
'bri' => 'transfer'
'gopay' => 'ewallet'
'ovo' => 'ewallet'
'dana' => 'ewallet'
```

## Fitur Utama

### 1. Menu Pesanan (Orders)
- **Kolom Tambahan**:
  - No. Transaksi: Menampilkan nomor transaksi yang dibuat otomatis
  - Diproses Oleh: Nama kasir yang mengubah status ke "paid"
  - Waktu Diproses: Timestamp pemrosesan

- **Form Edit**:
  - Section "Informasi Transaksi" muncul setelah transaksi dibuat
  - Menampilkan nomor transaksi, kasir, dan waktu pemrosesan (read-only)

### 2. Menu Transaksi (Transactions)
- **Kolom Tambahan**:
  - Pesanan Terkait: Menampilkan ID pesanan yang memicu transaksi

- **Detail Transaksi**:
  - Informasi pesanan terkait dalam view detail
  - Catatan otomatis: "Transaksi otomatis dari pesanan #X - metode_pembayaran"

### 3. Integrasi Cash Register
- Menggunakan cash register yang sedang aktif (status 'open')
- Jika tidak ada cash register aktif, sistem membuat cash register default
- Update total penjualan berdasarkan metode pembayaran

## Database Schema

### Tabel `orders` (kolom tambahan):
- `transaction_id`: Foreign key ke transactions (nullable)
- `processed_by`: Foreign key ke users (kasir yang memproses)
- `processed_at`: Timestamp pemrosesan

### Relasi Baru:
- Order belongsTo Transaction
- Order belongsTo User (processedBy)
- Transaction hasMany Orders

## Implementasi Teknis

### 1. Observer (OrderObserver)
```php
// Mendeteksi perubahan status ke 'paid'
public function updated(Order $order): void
{
    if ($order->isDirty('status') && 
        $order->status === 'paid' && 
        $order->getOriginal('status') !== 'paid' &&
        !$order->transaction_id) {
        
        $this->createTransactionFromOrder($order);
    }
}
```

### 2. Pembuatan Transaksi
- Menggunakan database transaction untuk konsistensi
- Membuat TransactionItem dari data pesanan
- Update cash register balance
- Logging untuk audit trail

### 3. Error Handling
- Rollback jika terjadi error
- Logging error untuk debugging
- Tidak mengganggu proses update pesanan jika transaksi gagal

## Penggunaan

### 1. Admin/Kasir
1. Buka menu "Pesanan"
2. Edit pesanan yang ingin diproses
3. Ubah status menjadi "Sudah Dibayar"
4. Simpan perubahan
5. Sistem otomatis membuat transaksi

### 2. Melihat Hasil
1. **Di menu Pesanan**:
   - Kolom "No. Transaksi" akan terisi
   - Kolom "Diproses Oleh" menampilkan nama kasir
   - Kolom "Waktu Diproses" menampilkan timestamp

2. **Di menu Transaksi**:
   - Transaksi baru muncul dengan status "Selesai"
   - Kolom "Pesanan Terkait" menampilkan ID pesanan
   - Detail transaksi berisi informasi lengkap

## Keamanan & Validasi

### 1. Validasi
- Hanya status 'paid' yang memicu pembuatan transaksi
- Tidak membuat transaksi duplikat
- Validasi keberadaan produk dan data pesanan

### 2. Audit Trail
- Logging setiap pembuatan transaksi
- Mencatat kasir yang memproses
- Timestamp yang akurat

### 3. Rollback Protection
- Database transaction untuk konsistensi
- Error handling yang proper
- Tidak merusak data pesanan jika transaksi gagal

## Troubleshooting

### Transaksi Tidak Dibuat
1. **Cek Observer Registration**:
   ```bash
   # Pastikan observer terdaftar di AppServiceProvider
   php artisan optimize:clear
   ```

2. **Cek Cash Register**:
   ```bash
   # Pastikan ada cash register aktif
   php artisan db:seed --class=CashRegisterSeeder
   ```

3. **Cek Log**:
   ```bash
   # Lihat log error
   tail -f storage/logs/laravel.log
   ```

### Error "No Active Cash Register"
```bash
# Buat cash register default
php artisan tinker
>>> App\Models\CashRegister::create([
...     'register_name' => 'Kasir Utama',
...     'user_id' => 1,
...     'opening_balance' => 0,
...     'status' => 'open',
...     'opened_at' => now()
... ]);
```

### Transaksi Duplikat
- Sistem sudah dilindungi dari duplikasi
- Jika terjadi, cek kondisi `!$order->transaction_id` di observer

## Monitoring

### 1. Query untuk Monitoring
```sql
-- Pesanan yang sudah dibayar hari ini
SELECT o.id, o.status, o.processed_by, t.transaction_number 
FROM orders o 
LEFT JOIN transactions t ON o.transaction_id = t.id 
WHERE o.status = 'paid' AND DATE(o.processed_at) = CURDATE();

-- Transaksi otomatis hari ini
SELECT * FROM transactions 
WHERE notes LIKE 'Transaksi otomatis%' 
AND DATE(created_at) = CURDATE();
```

### 2. Statistik
- Total pesanan yang diproses per kasir
- Total transaksi otomatis per hari
- Metode pembayaran yang paling sering digunakan

## Catatan Penting

1. **Backup Data**: Selalu backup sebelum update status pesanan massal
2. **User Login**: Pastikan kasir login sebelum memproses pesanan
3. **Cash Register**: Minimal satu cash register harus aktif
4. **Performance**: Observer berjalan sinkron, pertimbangkan queue untuk volume tinggi
5. **Testing**: Test di environment staging sebelum production