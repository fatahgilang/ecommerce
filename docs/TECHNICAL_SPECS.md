# 🔧 Technical Specifications - Aplikasi Toko

## 📋 System Requirements

### Server Requirements (Minimum)
- **CPU**: 2 cores
- **RAM**: 2 GB
- **Storage**: 10 GB SSD
- **OS**: Linux (Ubuntu 20.04+, CentOS 7+)
- **Web Server**: Nginx or Apache

### Server Requirements (Recommended)
- **CPU**: 4 cores
- **RAM**: 4 GB
- **Storage**: 20 GB SSD
- **OS**: Linux (Ubuntu 22.04 LTS)
- **Web Server**: Nginx 1.18+

---

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 12.26
- **Language**: PHP 8.2+
- **Database**: MySQL 8.0+ / MariaDB 10.6+
- **Cache**: Redis (optional)
- **Queue**: Redis / Database

### Frontend
- **Framework**: Vue.js 3.5
- **UI Library**: Tailwind CSS 3.4
- **Build Tool**: Vite 7.1
- **State Management**: Inertia.js 2.0

### Admin Panel
- **Framework**: Filament 3.3
- **Components**: Livewire 3.5
- **UI**: Tailwind CSS

### Additional Libraries
- **Charts**: Chart.js
- **Icons**: Heroicons
- **Printer**: QZ Tray
- **HTTP Client**: Axios
- **Date**: Carbon

---

## 📊 Database Schema

### Tables (15 tables)

1. **users** - User accounts & authentication
2. **products** - Product catalog
3. **product_categories** - Product categorization
4. **orders** - Order items
5. **transactions** - Transaction records
6. **discounts** - Discount codes
7. **cash_registers** - Cash register management
8. **navigation_categories** - Frontend navigation
9. **payment_splits** - Split payment records
10. **password_reset_tokens** - Password recovery
11. **sessions** - User sessions
12. **cache** - Application cache
13. **cache_locks** - Cache locking
14. **jobs** - Queue jobs
15. **failed_jobs** - Failed queue jobs

### Indexes
- Primary keys on all tables
- Foreign key indexes
- Search indexes (product_name, etc)
- Composite indexes for performance

---

## 🔐 Security Features

### Authentication
- Laravel Sanctum
- Session-based auth
- CSRF protection
- Password hashing (bcrypt)

### Authorization
- Role-based access control
- Permission system
- Route middleware
- Policy classes

### Data Protection
- SQL injection prevention
- XSS protection
- Input validation
- Output sanitization
- HTTPS enforcement
