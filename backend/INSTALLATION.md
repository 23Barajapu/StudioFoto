# Panduan Instalasi Photo Studio Backend

## Prasyarat

Pastikan sistem Anda sudah menginstall:
- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (optional)

## Langkah Instalasi

### 1. Setup Project

```bash
cd photo-studio-backend
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

```bash
# Copy file .env.example ke .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=photo_studio
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Buat Database

Buka MySQL dan jalankan:

```sql
CREATE DATABASE photo_studio;
```

Atau menggunakan command line:

```bash
mysql -u root -p -e "CREATE DATABASE photo_studio"
```

### 6. Jalankan Migration

```bash
php artisan migrate
```

### 7. Jalankan Seeder (Data Dummy)

```bash
php artisan db:seed
```

Seeder akan membuat:
- 1 Admin user: `admin@photostudio.com` / `password123`
- 2 Customer users untuk testing
- 4 Paket layanan (Basic, Standard, Premium, Wedding)
- Jadwal untuk 30 hari ke depan
- Sample gallery items

### 8. Setup Storage Link

```bash
php artisan storage:link
```

### 9. Jalankan Development Server

```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

### 10. Test API

Buka browser dan akses:
```
http://localhost:8000/api/health
```

Seharusnya menampilkan:
```json
{
  "success": true,
  "message": "API is running",
  "timestamp": "2024-01-01T00:00:00.000000Z"
}
```

## Akun Testing

### Admin
- **Email:** admin@photostudio.com
- **Password:** password123

### Customer
- **Email:** budi@example.com
- **Password:** password123

## Testing dengan Postman

1. Import collection dari dokumentasi API
2. Set base URL: `http://localhost:8000/api`
3. Login untuk mendapatkan token
4. Gunakan token di header: `Authorization: Bearer {token}`

## Commands Berguna

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting

### Error: SQLSTATE[HY000] [2002] Connection refused
- Pastikan MySQL sudah berjalan
- Check konfigurasi DB di file `.env`

### Error: Class not found
```bash
composer dump-autoload
```

### Permission denied untuk storage
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Port 8000 sudah digunakan
```bash
php artisan serve --port=8001
```

## Deployment ke Production

### 1. Setup Environment Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Optimize

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Security

- Pastikan file `.env` tidak dapat diakses public
- Setup SSL certificate
- Configure firewall
- Set proper file permissions
- Enable rate limiting
- Configure CORS dengan benar

### 4. Database

- Backup database secara regular
- Setup database replication (optional)
- Monitor query performance

### 5. Monitoring

- Setup error logging
- Monitor server resources
- Setup uptime monitoring

## Support

Untuk pertanyaan dan bantuan, silakan hubungi tim development.
