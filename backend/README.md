# Photo Studio Backend API

Backend API untuk aplikasi foto studio yang dibangun dengan Laravel 10 dan MySQL.

**🎉 READY untuk Web Dashboard & Mobile App!**

## Fitur Utama

- 🔐 **Authentication & Authorization** - Sistem login/register dengan Laravel Sanctum
- 👤 **Manajemen Profil Pengguna** - CRUD profil user dengan role (admin, customer)
- 📅 **Sistem Jadwal** - Manajemen jadwal ketersediaan studio
- 📝 **Pemesanan (Booking)** - Sistem booking lengkap dengan validasi
- 💳 **Pembayaran** - Integrasi payment gateway (Midtrans)
- 📦 **Paket Layanan** - Manajemen paket foto dengan harga
- 🖼️ **Galeri** - Upload dan manajemen foto portfolio
- 📊 **Dashboard Admin** - Statistik dan monitoring
- ✉️ **Notifikasi** - Email notification untuk booking & payment
- 📱 **Mobile API** - RESTful API untuk Flutter/React Native/Native Apps
- 💻 **Web Dashboard** - Laravel Blade admin dashboard

## Tech Stack

- **Framework**: Laravel 10.x
- **Database**: MySQL 8.0
- **Authentication**: Laravel Sanctum (API) + Session (Web)
- **Frontend**: Laravel Blade + Tailwind CSS
- **Payment Gateway**: Midtrans (Ready)
- **File Storage**: Local/S3
- **CORS**: Enabled untuk mobile apps

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Laravel 10.x

## Akses Aplikasi

### 🌐 Web Dashboard (Admin/Internal)
- **URL:** `http://localhost:8000`
- **Login:** `admin@photostudio.com` / `password123`
- **Teknologi:** Laravel Blade + Tailwind CSS
- **Fitur:** CRUD lengkap, stats, reports

### 📱 Mobile API (Customer App)
- **Base URL:** `http://localhost:8000/api`
- **Authentication:** Bearer Token (Laravel Sanctum)
- **Format:** JSON REST API
- **CORS:** Enabled
- **Dokumentasi:** `MOBILE_INTEGRATION_GUIDE.md`

**Database SAMA untuk Web & Mobile!**

## Instalasi

### 1. Clone & Setup

```bash
# Masuk ke direktori project
cd photo-studio-backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 2. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=photo_studio
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Buat Database

```bash
mysql -u root -p
CREATE DATABASE photo_studio;
EXIT;
```

### 4. Run Migrations & Seeders

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder (data dummy)
php artisan db:seed
```

### 5. Jalankan Server

```bash
php artisan serve
```

**Aplikasi berjalan di:**
- Web Dashboard: `http://localhost:8000`
- API Endpoint: `http://localhost:8000/api`

### 6. Login & Test

**Web Dashboard:**
- Buka: `http://localhost:8000`
- Login: `admin@photostudio.com` / `password123`

**Mobile API:**
- Lihat: `MOBILE_INTEGRATION_GUIDE.md`
- Test dengan Postman: Import `postman_collection.json`

## Struktur Database

### Tables

1. **users** - Data pengguna (admin & customer)
2. **packages** - Paket layanan foto
3. **schedules** - Jadwal ketersediaan
4. **bookings** - Data pemesanan
5. **payments** - Data pembayaran
6. **galleries** - Portfolio foto
7. **reviews** - Review dari customer

## API Endpoints

### Authentication

```
POST   /api/register           - Register pengguna baru
POST   /api/login              - Login pengguna
POST   /api/logout             - Logout pengguna
GET    /api/user               - Get user profile
PUT    /api/user/profile       - Update profile
```

### Packages (Public)

```
GET    /api/packages           - List semua paket
GET    /api/packages/{id}      - Detail paket
```

### Bookings

```
GET    /api/bookings           - List bookings user
POST   /api/bookings           - Buat booking baru
GET    /api/bookings/{id}      - Detail booking
PUT    /api/bookings/{id}      - Update booking
DELETE /api/bookings/{id}      - Cancel booking
```

### Schedules

```
GET    /api/schedules          - List jadwal tersedia
GET    /api/schedules/available - Cek ketersediaan
```

### Payments

```
POST   /api/payments           - Proses pembayaran
GET    /api/payments/{id}      - Status pembayaran
POST   /api/payments/callback  - Webhook callback
```

### Galleries (Public)

```
GET    /api/galleries          - List foto portfolio
```

### Admin Routes

```
GET    /api/admin/dashboard         - Dashboard stats
GET    /api/admin/bookings          - All bookings
PUT    /api/admin/bookings/{id}     - Update booking status
GET    /api/admin/users             - User management
POST   /api/admin/packages          - Create package
PUT    /api/admin/packages/{id}     - Update package
DELETE /api/admin/packages/{id}     - Delete package
POST   /api/admin/schedules         - Create schedule
DELETE /api/admin/schedules/{id}    - Delete schedule
POST   /api/admin/galleries         - Upload gallery
```

## Security Features

- ✅ Password hashing dengan bcrypt
- ✅ Token-based authentication (Sanctum)
- ✅ CORS configuration
- ✅ Rate limiting
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection
- ✅ CSRF protection
- ✅ Input validation & sanitization
- ✅ Role-based access control

## Request Validation

Semua request divalidasi dengan Laravel Form Request:
- Required fields check
- Data type validation
- Custom business rules
- Error messages dalam bahasa Indonesia

## Error Handling

Response format yang konsisten:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Error detail"]
  }
}
```

## Response Format

Success response:

```json
{
  "success": true,
  "message": "Success message",
  "data": {}
}
```

## Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=BookingTest
```

## Deployment

### Production Checklist

1. ✅ Set `APP_ENV=production`
2. ✅ Set `APP_DEBUG=false`
3. ✅ Configure proper database credentials
4. ✅ Set up payment gateway keys
5. ✅ Configure mail server
6. ✅ Set up SSL certificate
7. ✅ Run `php artisan config:cache`
8. ✅ Run `php artisan route:cache`
9. ✅ Run `php artisan view:cache`
10. ✅ Set proper file permissions

## Support

Untuk pertanyaan dan bantuan, silakan hubungi tim development.

## License

Proprietary - All rights reserved
