# Quick Start Guide - Photo Studio Backend

## 🚀 Mulai Cepat dalam 5 Menit

### 1. Install Dependencies
```bash
cd photo-studio-backend
composer install
```

### 2. Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```

### 3. Configure Database
Edit `.env`:
```env
DB_DATABASE=photo_studio
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Create & Migrate Database
```bash
# Buat database (jika belum)
mysql -u root -p -e "CREATE DATABASE photo_studio"

# Jalankan migration & seeder
php artisan migrate --seed
```

### 5. Run Server
```bash
php artisan serve
```

✅ **API berjalan di:** `http://localhost:8000`

### 6. Akses Dashboard Web

**Login Page:** `http://localhost:8000/login.html`

**Credentials:**
- Admin: `admin@photostudio.com` / `password123`
- Customer: `budi@example.com` / `password123`

Setelah login, Anda akan diarahkan ke dashboard admin yang modern dan responsive!

📖 **Panduan lengkap dashboard:** Lihat file `DASHBOARD_GUIDE.md`

---

## 🧪 Test API

### Test dengan Browser
```
http://localhost:8000/api/health
```

### Test dengan Postman
1. Import `postman_collection.json`
2. Set variable `base_url` = `http://localhost:8000/api`
3. Login untuk get token
4. Test endpoints lainnya

---

## 👤 Akun Default

### Admin
- **Email:** admin@photostudio.com
- **Password:** password123

### Customer
- **Email:** budi@example.com
- **Password:** password123

---

## 📝 Alur Penggunaan Dasar

### 1. Register/Login
```bash
POST /api/register
POST /api/login
```
Simpan `access_token` dari response.

### 2. Lihat Paket
```bash
GET /api/packages
```

### 3. Cek Jadwal Tersedia
```bash
GET /api/schedules?available=true
```

### 4. Buat Booking (Customer)
```bash
POST /api/bookings
Headers: Authorization: Bearer {token}
Body: {
  "package_id": 1,
  "schedule_id": 1,
  "customer_notes": "..."
}
```

### 5. Buat Pembayaran
```bash
POST /api/payments
Headers: Authorization: Bearer {token}
Body: {
  "booking_id": 1,
  "payment_method": "bank_transfer"
}
```

### 6. Admin: Konfirmasi Booking
```bash
PUT /api/bookings/1
Headers: Authorization: Bearer {admin_token}
Body: {
  "status": "confirmed",
  "admin_notes": "Booking confirmed"
}
```

### 7. Admin: Update Status Pembayaran
```bash
PUT /api/admin/payments/1/status
Headers: Authorization: Bearer {admin_token}
Body: {
  "status": "success"
}
```

---

## 📚 File Struktur

```
photo-studio-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/    # API Controllers
│   │   └── Middleware/         # Custom Middleware
│   ├── Models/                 # Eloquent Models
│   └── Exceptions/             # Exception Handlers
├── database/
│   ├── migrations/             # Database Migrations
│   └── seeders/                # Data Seeders
├── routes/
│   ├── api.php                 # API Routes
│   └── web.php                 # Web Routes
├── config/                     # Configuration Files
├── storage/                    # File Storage
├── README.md                   # Dokumentasi Utama
├── API_DOCUMENTATION.md        # Dokumentasi API Lengkap
├── INSTALLATION.md             # Panduan Instalasi Detail
├── SECURITY.md                 # Panduan Keamanan
└── postman_collection.json     # Postman Collection
```

---

## 🔑 Fitur Utama

### ✅ Authentication
- Register & Login
- Token-based auth (Sanctum)
- Role-based access (Admin/Customer)
- Profile management

### ✅ Packages
- CRUD packages
- Package features & pricing
- Image upload

### ✅ Schedules
- Available schedules
- Time slot management
- Bulk schedule generation
- Conflict detection

### ✅ Bookings
- Create booking
- Cancel booking
- Booking history
- Status management

### ✅ Payments
- Multiple payment methods
- Payment status tracking
- Payment history
- Admin verification

### ✅ Gallery
- Portfolio images
- Category filtering
- Featured galleries

### ✅ Reviews
- Customer reviews
- Rating system
- Admin approval

### ✅ Dashboard
- Customer dashboard
- Admin analytics
- Revenue reports

---

## 🛠️ Commands Berguna

### Reset & Reseed Database
```bash
php artisan migrate:fresh --seed
```

### Clear All Cache
```bash
php artisan optimize:clear
```

### Generate API Documentation (Optional)
```bash
# Install Scribe
composer require --dev knuckleswtf/scribe
php artisan scribe:generate
```

### Run Tests (Optional - needs setup)
```bash
php artisan test
```

---

## 🔐 Security Tips

1. **Jangan** commit file `.env`
2. Gunakan HTTPS di production
3. Set `APP_DEBUG=false` di production
4. Generate strong `APP_KEY`
5. Use strong database passwords
6. Enable rate limiting
7. Regular backup database
8. Monitor logs for suspicious activity

---

## 📞 Troubleshooting

### Error: Class not found
```bash
composer dump-autoload
```

### Error: Permission denied
```bash
chmod -R 775 storage bootstrap/cache
```

### Error: Database connection
- Check MySQL service running
- Verify `.env` credentials
- Check database exists

### Error: Token mismatch
- Clear browser cache
- Regenerate APP_KEY: `php artisan key:generate`

---

## 📖 Dokumentasi Lengkap

- **README.md** - Overview & features
- **INSTALLATION.md** - Detailed installation guide
- **API_DOCUMENTATION.md** - Complete API reference
- **SECURITY.md** - Security guidelines

---

## 🎯 Next Steps

1. ✅ Backend API sudah siap
2. 🔄 Integrasikan dengan frontend (React/Vue/Flutter)
3. 🔄 Setup payment gateway (Midtrans)
4. 🔄 Configure email notifications
5. 🔄 Deploy to production
6. 🔄 Setup monitoring & backup

---

## 💡 Tips Pengembangan

### Testing API dengan curl
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@photostudio.com","password":"password123"}'

# Get packages
curl http://localhost:8000/api/packages
```

### Database Query Monitoring
```bash
# Enable query log in .env
DB_LOG_QUERIES=true

# View logs
tail -f storage/logs/laravel.log
```

---

## 🌟 Best Practices

1. **Always validate input** - Gunakan Form Requests
2. **Use transactions** - Untuk operasi multiple database
3. **Handle errors gracefully** - Return proper status codes
4. **Document your code** - Add PHPDoc comments
5. **Follow Laravel conventions** - Keep code consistent
6. **Test your endpoints** - Before pushing to production

---

## 📧 Support

Untuk bantuan dan pertanyaan:
- Check dokumentasi lengkap
- Review code comments
- Check error logs: `storage/logs/laravel.log`

---

**Happy Coding! 🚀**
