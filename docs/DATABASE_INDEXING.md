# Database Indexing & Search Optimization

## Overview
Database indexing telah ditambahkan pada tabel `products` untuk meningkatkan performa pencarian dan filtering data produk.

## Indexes yang Ditambahkan

### 1. Single Column Indexes

#### `idx_product_name`
- **Column**: `product_name`
- **Purpose**: Mempercepat pencarian berdasarkan nama produk
- **Use Case**: Query dengan `WHERE product_name LIKE 'keyword%'`

#### `idx_shop_id`
- **Column**: `shop_id`
- **Purpose**: Mempercepat filter produk berdasarkan toko
- **Use Case**: Query dengan `WHERE shop_id = ?`

#### `idx_product_price`
- **Column**: `product_price`
- **Purpose**: Mempercepat sorting dan filter berdasarkan harga
- **Use Case**: Query dengan `WHERE product_price BETWEEN ? AND ?` atau `ORDER BY product_price`

#### `idx_stock`
- **Column**: `stock`
- **Purpose**: Mempercepat filter produk yang tersedia
- **Use Case**: Query dengan `WHERE stock > 0`

### 2. Composite Indexes

#### `idx_shop_product_name`
- **Columns**: `shop_id`, `product_name`
- **Purpose**: Mempercepat pencarian produk dalam toko tertentu
- **Use Case**: Query dengan `WHERE shop_id = ? AND product_name LIKE ?`

#### `idx_price_stock`
- **Columns**: `product_price`, `stock`
- **Purpose**: Mempercepat filter kombinasi harga dan ketersediaan
- **Use Case**: Query dengan `WHERE product_price BETWEEN ? AND ? AND stock > 0`

### 3. Fulltext Index

#### `idx_fulltext_search`
- **Columns**: `product_name`, `product_description`
- **Purpose**: Pencarian text yang lebih advanced dan relevan
- **Use Case**: Fulltext search dengan `MATCH() AGAINST()`

## Implementasi Search

### Frontend ProductController

```php
// Fulltext search untuk keyword >= 3 karakter
if (strlen($searchTerm) >= 3) {
    $query->whereRaw(
        "MATCH(product_name, product_description) AGAINST(? IN BOOLEAN MODE)",
        [$searchTerm . '*']
    );
} else {
    // LIKE search dengan index untuk keyword pendek
    $query->where('product_name', 'like', $searchTerm . '%');
}
```

### API ProductController

API endpoint mendukung parameter berikut:

- `search`: Keyword pencarian (menggunakan fulltext atau LIKE)
- `shop_id`: Filter berdasarkan toko
- `min_price`: Harga minimum
- `max_price`: Harga maksimum
- `in_stock`: Filter produk yang tersedia (boolean)
- `sort_by`: Kolom untuk sorting (product_name, product_price, stock, created_at)
- `sort_order`: Urutan sorting (asc, desc)
- `per_page`: Jumlah item per halaman

## Contoh Penggunaan API

### 1. Pencarian Produk
```bash
GET /api/products?search=smartphone
```

### 2. Filter by Shop
```bash
GET /api/products?shop_id=1
```

### 3. Filter by Price Range
```bash
GET /api/products?min_price=100000&max_price=500000
```

### 4. Filter In Stock Only
```bash
GET /api/products?in_stock=1
```

### 5. Kombinasi Filter & Search
```bash
GET /api/products?search=laptop&min_price=5000000&in_stock=1&sort_by=product_price&sort_order=asc
```

### 6. Pagination
```bash
GET /api/products?per_page=20&page=2
```

## Performance Benefits

### Sebelum Indexing
- Full table scan untuk setiap query
- Waktu query meningkat seiring bertambahnya data
- Tidak efisien untuk pencarian text

### Setelah Indexing
- Query menggunakan index untuk akses cepat
- Performa konsisten meskipun data bertambah
- Fulltext search lebih relevan dan cepat

### Benchmark Estimasi

| Jumlah Produk | Tanpa Index | Dengan Index | Improvement |
|---------------|-------------|--------------|-------------|
| 100 | 5ms | 2ms | 2.5x |
| 1,000 | 50ms | 3ms | 16.7x |
| 10,000 | 500ms | 5ms | 100x |
| 100,000 | 5000ms | 10ms | 500x |

*Note: Angka di atas adalah estimasi dan dapat bervariasi tergantung hardware dan konfigurasi*

## Best Practices

### 1. Gunakan Index yang Tepat
```php
// GOOD - Menggunakan index
$products = Product::where('shop_id', 1)
    ->where('product_name', 'like', 'laptop%')
    ->get();

// BAD - Tidak menggunakan index
$products = Product::where('product_name', 'like', '%laptop%')
    ->get();
```

### 2. Fulltext Search untuk Keyword Panjang
```php
// GOOD - Fulltext untuk keyword >= 3 karakter
if (strlen($keyword) >= 3) {
    $query->whereRaw("MATCH(product_name, product_description) AGAINST(? IN BOOLEAN MODE)", [$keyword . '*']);
}

// BAD - LIKE dengan wildcard di awal
$query->where('product_name', 'like', '%' . $keyword . '%');
```

### 3. Composite Index untuk Multiple Conditions
```php
// GOOD - Menggunakan composite index
$products = Product::where('shop_id', 1)
    ->where('product_name', 'like', 'laptop%')
    ->get();

// Urutan WHERE clause sesuai dengan urutan kolom di composite index
```

### 4. Limit Results
```php
// GOOD - Gunakan pagination atau limit
$products = Product::where('stock', '>', 0)
    ->paginate(20);

// BAD - Fetch semua data
$products = Product::where('stock', '>', 0)->get();
```

## Monitoring Index Usage

### Check Index Usage
```sql
EXPLAIN SELECT * FROM products WHERE product_name LIKE 'laptop%';
```

### Show All Indexes
```sql
SHOW INDEX FROM products;
```

### Analyze Table
```sql
ANALYZE TABLE products;
```

## Maintenance

### Rebuild Indexes (jika diperlukan)
```sql
ALTER TABLE products DROP INDEX idx_fulltext_search;
ALTER TABLE products ADD FULLTEXT INDEX idx_fulltext_search (product_name, product_description);
```

### Optimize Table
```sql
OPTIMIZE TABLE products;
```

## Rollback

Jika perlu menghapus indexes:

```bash
php artisan migrate:rollback --step=1
```

Atau manual:

```sql
ALTER TABLE products DROP INDEX idx_fulltext_search;
ALTER TABLE products DROP INDEX idx_price_stock;
ALTER TABLE products DROP INDEX idx_shop_product_name;
ALTER TABLE products DROP INDEX idx_stock;
ALTER TABLE products DROP INDEX idx_product_price;
ALTER TABLE products DROP INDEX idx_shop_id;
ALTER TABLE products DROP INDEX idx_product_name;
```

## Kesimpulan

Database indexing sangat penting untuk aplikasi e-commerce dengan banyak produk. Dengan indexing yang tepat:

- ✅ Pencarian produk lebih cepat
- ✅ Filtering lebih efisien
- ✅ Sorting lebih responsif
- ✅ User experience lebih baik
- ✅ Server load lebih rendah
- ✅ Scalability lebih baik

Pastikan untuk selalu monitor performa query dan tambahkan index sesuai kebutuhan.
