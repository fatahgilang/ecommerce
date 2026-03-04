# Dokumentasi Frontend E-Commerce dengan Inertia.js

## Daftar Isi
1. [Pengenalan](#pengenalan)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Struktur Proyek](#struktur-proyek)
4. [Halaman yang Tersedia](#halaman-yang-tersedia)
5. [Komponen](#komponen)
6. [Cara Menjalankan](#cara-menjalankan)
7. [Customization](#customization)

## Pengenalan

Frontend e-commerce ini dibangun menggunakan:
- **Inertia.js** - Modern monolith framework
- **Vue 3** - Progressive JavaScript framework
- **Tailwind CSS** - Utility-first CSS framework
- **Heroicons** - Beautiful hand-crafted SVG icons

## Teknologi yang Digunakan

### Backend
- Laravel 12
- Inertia.js Laravel Adapter
- Ziggy (Laravel route helper untuk JavaScript)

### Frontend
- Vue 3 (Composition API)
- Inertia.js Vue 3 Adapter
- Tailwind CSS 4
- Heroicons Vue
- Vite (Build tool)

## Struktur Proyek

```
resources/
├── js/
│   ├── Components/
│   │   ├── ProductCard.vue       # Card produk
│   │   └── Pagination.vue        # Komponen pagination
│   │
│   ├── Layouts/
│   │   └── AppLayout.vue         # Layout utama
│   │
│   ├── Pages/
│   │   ├── Home.vue              # Halaman home
│   │   └── Products/
│   │       ├── Index.vue         # Listing produk
│   │       └── Show.vue          # Detail produk
│   │
│   ├── app.js                    # Entry point
│   └── bootstrap.js              # Bootstrap file
│
├── views/
│   └── app.blade.php             # Root template Inertia
│
└── css/
    └── app.css                   # Tailwind CSS

app/Http/Controllers/Frontend/
├── HomeController.php            # Controller home
└── ProductController.php         # Controller produk
```

## Halaman yang Tersedia

### 1. Home Page (`/`)
**File**: `resources/js/Pages/Home.vue`

**Fitur**:
- Hero section dengan CTA
- Kategori populer (6 kategori)
- Produk unggulan (8 produk)
- Produk terlaris (8 produk)
- Features section (Gratis ongkir, Pembayaran aman, CS 24/7)

**Data yang ditampilkan**:
```javascript
{
    featuredProducts: Array,  // 8 produk random
    bestSellers: Array,       // 8 produk terlaris
}
```

### 2. Products Listing (`/products`)
**File**: `resources/js/Pages/Products/Index.vue`

**Fitur**:
- Grid produk (2-4 kolom responsive)
- Sidebar filter:
  - Kategori (checkbox multiple)
  - Range harga (min-max)
- Sorting:
  - Terbaru
  - Harga terendah
  - Harga tertinggi
  - Terpopuler
- Pagination
- Empty state
- Breadcrumb

**Query Parameters**:
- `search` - Pencarian produk
- `category` - Filter kategori tunggal
- `categories[]` - Filter kategori multiple
- `min_price` - Harga minimum
- `max_price` - Harga maksimum
- `sort` - Sorting (newest, price_low, price_high, popular)

### 3. Product Detail (`/products/{id}`)
**File**: `resources/js/Pages/Products/Show.vue` (belum dibuat)

**Fitur yang akan dibuat**:
- Gambar produk
- Informasi produk lengkap
- Harga dan stok
- Deskripsi
- Informasi toko
- Reviews dan rating
- Tombol add to cart
- Produk terkait

## Komponen

### 1. AppLayout
**File**: `resources/js/Layouts/AppLayout.vue`

**Fitur**:
- Navigation bar dengan logo
- Search bar (desktop & mobile)
- Cart icon dengan badge
- Login/Register buttons
- Categories menu
- Footer dengan informasi lengkap

**Props**: Tidak ada (menggunakan slot)

### 2. ProductCard
**File**: `resources/js/Components/ProductCard.vue`

**Props**:
```javascript
{
    product: {
        id: Number,
        product_name: String,
        product_price: Number,
        unit: String,
        stock: Number,
        image: String,
        shop: Object,
        average_rating: Number,
        reviews_count: Number,
    }
}
```

**Fitur**:
- Gambar produk dengan hover effect
- Nama produk (2 baris max)
- Rating dan jumlah ulasan
- Harga dengan format Rupiah
- Badge stok rendah
- Nama toko
- Tombol add to cart
- Disabled state untuk stok habis

### 3. Pagination
**File**: `resources/js/Components/Pagination.vue`

**Props**:
```javascript
{
    links: Array  // Laravel pagination links
}
```

**Fitur**:
- Previous/Next buttons
- Page numbers
- Active state
- Disabled state
- Preserve scroll

## Cara Menjalankan

### Development Mode

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

Akses: `http://localhost:8000`

### Production Build

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan optimize
```

### Clear Cache

```bash
# Clear all cache
php artisan optimize:clear

# Clear specific cache
php artisan view:clear
php artisan route:clear
php artisan config:clear
```

## Customization

### Mengubah Warna Primary

Edit `tailwind.config.js`:

```javascript
theme: {
    extend: {
        colors: {
            primary: {
                // Your custom colors
            },
        },
    },
}
```

Kemudian rebuild:
```bash
npm run build
```

### Menambah Halaman Baru

1. Buat Vue component di `resources/js/Pages/`:
```vue
<template>
    <AppLayout>
        <!-- Your content -->
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>
```

2. Buat controller:
```php
use Inertia\Inertia;

public function index()
{
    return Inertia::render('YourPage', [
        'data' => $data,
    ]);
}
```

3. Tambah route di `routes/web.php`:
```php
Route::get('/your-page', [YourController::class, 'index']);
```

### Menambah Komponen Baru

Buat file di `resources/js/Components/`:

```vue
<template>
    <!-- Your component -->
</template>

<script setup>
defineProps({
    // Your props
});
</script>
```

Import dan gunakan:
```vue
<script setup>
import YourComponent from '@/Components/YourComponent.vue';
</script>

<template>
    <YourComponent :prop="value" />
</template>
```

### Mengubah Layout

Edit `resources/js/Layouts/AppLayout.vue`:

```vue
<template>
    <div class="min-h-screen">
        <!-- Your custom layout -->
        <slot />
    </div>
</template>
```

## API Integration

### Menggunakan Inertia Link

```vue
<Link href="/products" class="...">
    Products
</Link>
```

### Form Submission

```vue
<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
});

const submit = () => {
    form.post('/submit', {
        onSuccess: () => {
            // Handle success
        },
    });
};
</script>
```

### Manual Visit

```vue
<script setup>
import { router } from '@inertiajs/vue3';

const navigate = () => {
    router.visit('/products', {
        method: 'get',
        data: { category: 'electronics' },
        preserveState: true,
        preserveScroll: true,
    });
};
</script>
```

## Best Practices

1. **Gunakan Composition API** untuk Vue 3
2. **Preserve state** saat navigasi filter/pagination
3. **Lazy load images** untuk performa
4. **Use Tailwind utilities** daripada custom CSS
5. **Component reusability** - buat komponen yang reusable
6. **Type safety** - gunakan PropTypes yang jelas
7. **Error handling** - handle error dengan baik
8. **Loading states** - tampilkan loading indicator
9. **Responsive design** - test di berbagai device
10. **Accessibility** - gunakan semantic HTML dan ARIA labels

## Troubleshooting

### Assets tidak ter-load

```bash
npm run build
php artisan optimize:clear
```

### Inertia version mismatch

```bash
npm install @inertiajs/vue3@latest
composer update inertiajs/inertia-laravel
```

### Vite error

```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### Route not found

```bash
php artisan route:clear
php artisan optimize:clear
```

## Next Steps

Halaman yang perlu dibuat:
1. ✅ Home page - DONE
2. ✅ Products listing - DONE
3. ⏳ Product detail page
4. ⏳ Shopping cart
5. ⏳ Checkout
6. ⏳ User authentication
7. ⏳ User profile
8. ⏳ Order history
9. ⏳ Wishlist
10. ⏳ Search results

## Resources

- [Inertia.js Documentation](https://inertiajs.com)
- [Vue 3 Documentation](https://vuejs.org)
- [Tailwind CSS Documentation](https://tailwindcss.com)
- [Heroicons](https://heroicons.com)
- [Laravel Documentation](https://laravel.com/docs)

## Support

Untuk pertanyaan atau issue:
- Check dokumentasi di `docs/`
- GitHub Issues
- Email: support@ecommerce.com
