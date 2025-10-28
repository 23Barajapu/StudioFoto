# Security Guidelines - Photo Studio Backend

## Security Features Implemented

### 1. Authentication & Authorization

- ✅ **Laravel Sanctum** untuk token-based authentication
- ✅ **Role-based access control** (Admin & Customer)
- ✅ **Password hashing** menggunakan bcrypt
- ✅ **Token expiration** dan revocation
- ✅ **User status check** middleware

### 2. Input Validation

- ✅ **Form Request Validation** untuk semua input
- ✅ **Type checking** dan data sanitization
- ✅ **SQL injection protection** via Eloquent ORM
- ✅ **XSS protection** via Laravel's blade escaping
- ✅ **Mass assignment protection** dengan `$fillable`

### 3. API Security

- ✅ **CORS configuration** untuk cross-origin requests
- ✅ **Rate limiting** (60 requests per minute)
- ✅ **CSRF protection** untuk web routes
- ✅ **API token authentication**
- ✅ **HTTPS enforcement** (production)

### 4. File Upload Security

- ✅ **File type validation** (only images)
- ✅ **File size limits** (max 5MB for galleries, 2MB for profiles)
- ✅ **Secure file storage** di storage/public
- ✅ **Filename sanitization**

### 5. Database Security

- ✅ **Prepared statements** via Eloquent
- ✅ **Database credentials** di .env (tidak di version control)
- ✅ **Soft deletes** untuk data penting
- ✅ **Foreign key constraints**

## Best Practices

### Environment Variables

**JANGAN PERNAH** commit file `.env` ke version control!

```bash
# .gitignore sudah include:
.env
.env.backup
.env.production
```

### Password Policy

Requirement minimal:
- Minimal 8 karakter
- Harus konfirmasi password saat register
- Password di-hash menggunakan bcrypt

### Token Management

```php
// Token akan otomatis expire setelah user logout
$request->user()->currentAccessToken()->delete();
```

### API Rate Limiting

Default: 60 requests per minute per user/IP

Untuk mengubah:
```php
// app/Providers/RouteServiceProvider.php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
});
```

## Production Security Checklist

### Sebelum Deploy

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Update `APP_URL` dengan domain production
- [ ] Configure database credentials yang aman
- [ ] Setup SSL certificate (HTTPS)
- [ ] Configure firewall rules
- [ ] Set proper file permissions
- [ ] Remove development dependencies
- [ ] Enable query logging untuk monitoring
- [ ] Setup backup database regular
- [ ] Configure error reporting ke monitoring service

### File Permissions

```bash
# Linux/Unix
chown -R www-data:www-data /path/to/project
chmod -R 755 /path/to/project
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Environment Security

```env
# Production .env
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generated-key>

# Use strong database password
DB_PASSWORD=<strong-password>

# Setup proper mail configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
```

## Vulnerability Prevention

### SQL Injection

✅ **Gunakan Eloquent ORM atau Query Builder**

```php
// AMAN ✓
User::where('email', $email)->first();

// TIDAK AMAN ✗
DB::select("SELECT * FROM users WHERE email = '$email'");
```

### XSS (Cross-Site Scripting)

✅ **Laravel otomatis escape output**

```php
// Blade templates otomatis escape
{{ $user->name }}

// Untuk raw HTML (hati-hati!)
{!! $trustedHtml !!}
```

### CSRF (Cross-Site Request Forgery)

✅ **CSRF protection enabled** untuk web routes
✅ **API routes** exempt dari CSRF (menggunakan token auth)

### Mass Assignment

✅ **Gunakan $fillable atau $guarded**

```php
class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];
}
```

### File Upload

✅ **Validate file type dan size**

```php
$request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
]);
```

## Monitoring & Logging

### Error Logging

```php
// app/Exceptions/Handler.php
// Semua error di-log secara otomatis
```

### Activity Logging (Recommended)

Install package untuk audit log:
```bash
composer require spatie/laravel-activitylog
```

### Security Monitoring

Recommended tools:
- **Laravel Telescope** - Development debugging
- **Sentry** - Error tracking
- **New Relic** - Performance monitoring

## Backup & Recovery

### Database Backup

```bash
# Backup database
php artisan db:backup

# Or manual
mysqldump -u username -p database_name > backup.sql
```

### Automated Backup (Recommended)

Install package:
```bash
composer require spatie/laravel-backup
```

## Incident Response

### Jika Terjadi Security Breach

1. **Immediate Action**
   - Matikan aplikasi (maintenance mode)
   - Revoke semua access tokens
   - Change database passwords
   - Review access logs

2. **Investigation**
   - Check error logs
   - Review access patterns
   - Identify affected data
   - Document timeline

3. **Recovery**
   - Patch vulnerability
   - Restore from clean backup
   - Reset all user passwords
   - Notify affected users

4. **Prevention**
   - Update security measures
   - Review code
   - Add monitoring
   - Train team

## Security Updates

Selalu update dependencies:

```bash
composer update
php artisan vendor:publish --tag=laravel-assets
```

Check for security vulnerabilities:
```bash
composer audit
```

## Contact

Untuk melaporkan security vulnerability:
- Email: security@photostudio.com
- Gunakan responsible disclosure

**JANGAN** post security issues di public repository!
