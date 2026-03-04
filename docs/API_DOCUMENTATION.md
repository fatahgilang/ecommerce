# Dokumentasi API E-Commerce

## Daftar Isi
- [Pengenalan](#pengenalan)
- [Authentication](#authentication)
- [Base URL](#base-url)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Endpoints](#endpoints)

## Pengenalan

API E-Commerce menyediakan akses programatik ke semua fitur sistem, termasuk manajemen produk, pesanan, pelanggan, dan sistem POS.

## Authentication

Saat ini API tidak memerlukan authentication untuk endpoint publik. Untuk endpoint yang memerlukan authentication, gunakan Laravel Sanctum token.

```bash
# Header untuk authenticated requests
Authorization: Bearer {your-token}
```

## Base URL

```
Development: http://localhost:8000/api/v1
Production: https://yourdomain.com/api/v1
```

## Response Format

Semua response menggunakan format JSON dengan struktur konsisten:

### Success Response
```json
{
  "data": {
    // Response data
  },
  "message": "Success message",
  "meta": {
    // Pagination atau metadata lainnya
  }
}
```

### Error Response
```json
{
  "message": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

## Error Handling

### HTTP Status Codes
- `200` - OK: Request berhasil
- `201` - Created: Resource berhasil dibuat
- `400` - Bad Request: Request tidak valid
- `404` - Not Found: Resource tidak ditemukan
- `422` - Unprocessable Entity: Validasi gagal
- `500` - Internal Server Error: Server error

## Endpoints

### Products

#### GET /products
Mendapatkan daftar produk dengan pagination

**Parameters:**
- `page` (optional): Nomor halaman (default: 1)
- `per_page` (optional): Jumlah item per halaman (default: 12)
- `search` (optional): Pencarian berdasarkan nama produk
- `shop_id` (optional): Filter berdasarkan toko
- `min_price` (optional): Harga minimum
- `max_price` (optional): Harga maksimum

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "product_name": "Laptop ASUS ROG",
      "product_description": "Gaming laptop dengan spesifikasi tinggi",
      "product_price": "15000000.00",
      "price_per_unit": "15000000.00",
      "unit": "pcs",
      "stock": 10,
      "weight": 2.5,
      "image": "http://localhost:8000/storage/products/laptop.jpg",
      "shop": {
        "id": 1,
        "shop_name": "Toko Elektronik",
        "phone": "08123456789"
      },
      "average_rating": 4.5,
      "reviews_count": 12
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 12,
    "total": 45,
    "last_page": 4
  }
}
```

#### GET /products/featured
Mendapatkan produk unggulan (rating tertinggi)

**Response:** Same as GET /products

#### GET /products/best-sellers
Mendapatkan produk terlaris

**Response:** Same as GET /products

#### GET /products/{id}
Mendapatkan detail produk

**Response:**
```json
{
  "id": 1,
  "product_name": "Laptop ASUS ROG",
  "product_description": "Gaming laptop dengan spesifikasi tinggi",
  "product_price": "15000000.00",
  "price_per_unit": "15000000.00",
  "unit": "pcs",
  "stock": 10,
  "weight": 2.5,
  "image": "http://localhost:8000/storage/products/laptop.jpg",
  "shop": {
    "id": 1,
    "shop_name": "Toko Elektronik",
    "description": "Toko elektronik terpercaya",
    "address": "Jl. Sudirman No. 123",
    "phone": "08123456789",
    "email": "elektronik@example.com"
  },
  "categories": [
    {
      "id": 1,
      "category_name": "Laptop Gaming"
    }
  ],
  "reviews": [
    {
      "id": 1,
      "rating": 5,
      "review": "Produk sangat bagus, pengiriman cepat",
      "is_verified_purchase": true,
      "created_at": "2025-02-01 10:30:00",
      "customer": {
        "id": 1,
        "name": "John Doe"
      }
    }
  ],
  "average_rating": 4.5,
  "reviews_count": 12
}
```

#### POST /products/{id}/reviews
Menambahkan review untuk produk

**Request Body:**
```json
{
  "customer_id": 1,
  "review": "Produk sangat bagus, sesuai deskripsi",
  "rating": 5
}
```

**Validation Rules:**
- `customer_id`: required, exists in customers table
- `review`: required, string, min:10 characters
- `rating`: required, integer, between 1-5

**Response:**
```json
{
  "message": "Review berhasil ditambahkan",
  "review": {
    "id": 1,
    "product_id": 1,
    "customer_id": 1,
    "review": "Produk sangat bagus, sesuai deskripsi",
    "rating": 5,
    "is_verified_purchase": false,
    "created_at": "2025-02-02 15:30:00"
  }
}
```

### Orders

#### GET /orders
Mendapatkan daftar pesanan

**Parameters:**
- `customer_id` (required): ID pelanggan
- `status` (optional): Filter berdasarkan status (pending, processing, completed, cancelled)
- `page` (optional): Nomor halaman

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "order_number": "ORD-20250202-0001",
      "product": {
        "id": 1,
        "product_name": "Laptop ASUS ROG",
        "product_price": "15000000.00",
        "image": "http://localhost:8000/storage/products/laptop.jpg"
      },
      "product_quantity": 1,
      "total_price": "15015000.00",
      "payment_method": "transfer",
      "status": "processing",
      "shipment": {
        "tracking_number": "JNE123456789",
        "shipment_type": "express",
        "delivery_cost": "15000.00",
        "status": "shipped"
      },
      "created_at": "02 Feb 2025 10:30",
      "formatted_total": "Rp 15.015.000"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 5
  }
}
```

#### POST /orders
Membuat pesanan baru

**Request Body:**
```json
{
  "customer_id": 1,
  "product_id": 1,
  "product_quantity": 2,
  "payment_method": "transfer",
  "shipping_address": "Jl. Merdeka No. 45, Jakarta Pusat",
  "recipient_name": "John Doe",
  "recipient_phone": "08123456789",
  "shipment_type": "express",
  "delivery_cost": 15000
}
```

**Validation Rules:**
- `customer_id`: required, exists in customers
- `product_id`: required, exists in products
- `product_quantity`: required, integer, min:1
- `payment_method`: required, in:transfer,cod,ewallet,credit_card
- `shipping_address`: required, string
- `recipient_name`: required, string
- `recipient_phone`: required, string
- `shipment_type`: required, in:regular,express,same_day
- `delivery_cost`: required, numeric, min:0

**Response:**
```json
{
  "message": "Pesanan berhasil dibuat",
  "order": {
    "id": 1,
    "order_number": "ORD-20250202-0001",
    "customer_id": 1,
    "product_id": 1,
    "product_quantity": 2,
    "total_price": "30015000.00",
    "payment_method": "transfer",
    "status": "pending",
    "shipment": {
      "tracking_number": "JNE123456789",
      "shipment_type": "express",
      "delivery_cost": "15000.00",
      "status": "pending"
    }
  }
}
```

#### POST /orders/{id}/cancel
Membatalkan pesanan

**Request Body:**
```json
{
  "reason": "Salah pilih produk, ingin ganti dengan yang lain"
}
```

**Validation Rules:**
- `reason`: required, string, min:10 characters

**Response:**
```json
{
  "message": "Pesanan berhasil dibatalkan",
  "order": {
    "id": 1,
    "order_number": "ORD-20250202-0001",
    "status": "cancelled",
    "cancellation_reason": "Salah pilih produk, ingin ganti dengan yang lain"
  }
}
```

### Customers

#### GET /customers
Mendapatkan daftar pelanggan

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "08123456789",
      "address": "Jl. Merdeka No. 45",
      "date_of_birth": "1990-01-15",
      "orders_count": 5,
      "total_spent": "5000000.00"
    }
  ]
}
```

#### POST /customers
Membuat pelanggan baru

**Request Body:**
```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "phone": "08198765432",
  "address": "Jl. Sudirman No. 123",
  "date_of_birth": "1995-05-20"
}
```

**Validation Rules:**
- `name`: required, string, max:255
- `email`: required, email, unique:customers
- `phone`: required, string
- `address`: required, string
- `date_of_birth`: nullable, date

#### GET /customers/{id}
Mendapatkan detail pelanggan

#### PUT /customers/{id}
Update data pelanggan

#### DELETE /customers/{id}
Hapus pelanggan

### Cash Registers

#### GET /cash-registers/current
Mendapatkan kasir aktif untuk user

**Parameters:**
- `user_id` (required): ID user/kasir

**Response:**
```json
{
  "cash_register": {
    "id": 1,
    "register_name": "Kasir Utama",
    "user_id": 1,
    "opening_balance": "500000.00",
    "closing_balance": null,
    "total_sales": "240000.00",
    "total_cash": "165000.00",
    "total_card": "75000.00",
    "total_ewallet": "0.00",
    "status": "open",
    "opened_at": "02 Feb 2025 08:00",
    "closed_at": null,
    "cashier": "Kasir 1",
    "transactions_count": 8
  }
}
```

#### POST /cash-registers/open
Membuka kasir baru

**Request Body:**
```json
{
  "register_name": "Kasir Utama",
  "opening_balance": 500000,
  "user_id": 1
}
```

**Response:**
```json
{
  "message": "Kasir berhasil dibuka",
  "cash_register": {
    "id": 1,
    "register_name": "Kasir Utama",
    "opening_balance": "500000.00",
    "status": "open",
    "opened_at": "02 Feb 2025 08:00"
  }
}
```

#### POST /cash-registers/{id}/close
Menutup kasir

**Request Body:**
```json
{
  "closing_balance": 650000,
  "notes": "Tutup shift sore, semua transaksi lancar"
}
```

**Response:**
```json
{
  "message": "Kasir berhasil ditutup",
  "cash_register": {
    "id": 1,
    "register_name": "Kasir Utama",
    "opening_balance": "500000.00",
    "closing_balance": "650000.00",
    "total_sales": "240000.00",
    "status": "closed",
    "closed_at": "02 Feb 2025 17:00"
  }
}
```

### Transactions

#### GET /transactions
Mendapatkan daftar transaksi

**Parameters:**
- `cash_register_id` (optional): Filter by cash register
- `status` (optional): Filter by status
- `date_from` (optional): Filter dari tanggal
- `date_to` (optional): Filter sampai tanggal
- `per_page` (optional): Items per page (default: 10)

#### POST /transactions
Membuat transaksi baru

**Request Body:**
```json
{
  "cash_register_id": 1,
  "customer_id": null,
  "user_id": 1,
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "discount_amount": 0
    },
    {
      "product_id": 2,
      "quantity": 1,
      "discount_amount": 5000
    }
  ],
  "payment_method": "cash",
  "paid_amount": 200000,
  "discount_code": "TODAY10",
  "tax_rate": 10,
  "notes": "Transaksi walk-in customer"
}
```

**Response:**
```json
{
  "message": "Transaksi berhasil dibuat",
  "transaction": {
    "id": 1,
    "transaction_number": "TRX-20250202-0001",
    "subtotal": "150000.00",
    "tax_amount": "15000.00",
    "discount_amount": "10000.00",
    "total_amount": "155000.00",
    "paid_amount": "200000.00",
    "change_amount": "45000.00",
    "payment_method": "cash",
    "status": "completed"
  }
}
```

#### GET /transactions/{id}
Mendapatkan detail transaksi lengkap

#### POST /transactions/{id}/cancel
Membatalkan transaksi

#### POST /transactions/{id}/refund
Refund transaksi

**Request Body:**
```json
{
  "reason": "Barang rusak, customer minta refund"
}
```

#### GET /transactions/{id}/receipt
Mendapatkan data untuk struk transaksi

### Discounts

#### GET /discounts
Mendapatkan daftar diskon aktif

**Parameters:**
- `code` (optional): Filter berdasarkan kode diskon

#### POST /discounts/validate
Validasi kode diskon

**Request Body:**
```json
{
  "code": "TODAY10",
  "amount": 100000
}
```

**Response:**
```json
{
  "valid": true,
  "discount": {
    "id": 1,
    "name": "Diskon Hari Ini",
    "code": "TODAY10",
    "type": "percentage",
    "value": "10.00",
    "discount_amount": "10000.00",
    "message": "Diskon 10% berhasil diterapkan"
  }
}
```

### Reports

#### GET /reports/sales
Laporan penjualan

**Parameters:**
- `date_from` (optional): Default awal bulan
- `date_to` (optional): Default hari ini
- `cash_register_id` (optional): Filter by cash register

#### GET /reports/products
Laporan produk terlaris

**Parameters:**
- `date_from` (optional)
- `date_to` (optional)
- `limit` (optional): Default 20

#### GET /reports/customers
Laporan pelanggan

#### GET /reports/cashiers
Laporan performa kasir

#### GET /reports/dashboard
Dashboard summary untuk hari ini

### Utilities

#### GET /search
Pencarian global

**Parameters:**
- `q` (required): Query pencarian

**Response:**
```json
{
  "products": [
    {
      "id": 1,
      "product_name": "Laptop ASUS ROG",
      "product_price": "15000000.00",
      "image": "..."
    }
  ],
  "shops": [
    {
      "id": 1,
      "shop_name": "Toko Elektronik",
      "description": "..."
    }
  ]
}
```

#### GET /home
Data untuk halaman utama

#### GET /health
Health check endpoint

**Response:**
```json
{
  "status": "ok",
  "timestamp": "2025-02-02 15:30:00",
  "database": "connected",
  "cache": "working"
}
```

## Rate Limiting

API menggunakan rate limiting untuk mencegah abuse:
- 60 requests per menit untuk endpoint umum
- 10 requests per menit untuk endpoint yang resource-intensive

## Pagination

Semua endpoint yang mengembalikan list menggunakan pagination:

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "last_page": 10,
    "from": 1,
    "to": 10
  },
  "links": {
    "first": "http://localhost:8000/api/v1/products?page=1",
    "last": "http://localhost:8000/api/v1/products?page=10",
    "prev": null,
    "next": "http://localhost:8000/api/v1/products?page=2"
  }
}
```

## Testing dengan cURL

### Contoh Request

```bash
# Get products
curl -X GET "http://localhost:8000/api/v1/products?page=1&per_page=12"

# Create order
curl -X POST "http://localhost:8000/api/v1/orders" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "product_id": 1,
    "product_quantity": 2,
    "payment_method": "transfer",
    "shipping_address": "Jl. Merdeka No. 45",
    "recipient_name": "John Doe",
    "recipient_phone": "08123456789",
    "shipment_type": "express",
    "delivery_cost": 15000
  }'

# Create transaction
curl -X POST "http://localhost:8000/api/v1/transactions" \
  -H "Content-Type: application/json" \
  -d '{
    "cash_register_id": 1,
    "user_id": 1,
    "items": [
      {"product_id": 1, "quantity": 2, "discount_amount": 0}
    ],
    "payment_method": "cash",
    "paid_amount": 200000
  }'
```

## Postman Collection

Import Postman collection untuk testing yang lebih mudah:
[Download Postman Collection](./postman_collection.json)

## Support

Untuk pertanyaan atau issue terkait API:
- Email: api-support@example.com
- GitHub Issues: https://github.com/fatahgilang/ecommerce/issues
