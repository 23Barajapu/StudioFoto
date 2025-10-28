# Panduan Dashboard Laravel - Prime Studio

## 📁 Struktur Project

Backend sekarang menggunakan **Laravel Blade Templates** dengan struktur yang proper:

```
photo-studio-backend/
├── app/
│   └── Http/
│       ├── Controllers/
│       │   ├── Api/              # API Controllers (untuk mobile/frontend)
│       │   └── Web/              # Web Controllers (untuk dashboard)
│       │       ├── AuthController.php
│       │       ├── DashboardController.php
│       │       ├── BookingController.php
│       │       ├── PaymentController.php
│       │       └── PackageController.php
│       └── Middleware/
│           ├── AdminMiddleware.php
│           └── CheckUserStatus.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php         # Base layout
│       │   └── dashboard.blade.php   # Dashboard layout dengan sidebar
│       ├── partials/
│       │   ├── sidebar.blade.php     # Sidebar navigation
│       │   └── header.blade.php      # Dashboard header
│       ├── auth/
│       │   └── login.blade.php       # Login page
│       ├── dashboard/
│       │   └── index.blade.php       # Dashboard utama
│       ├── bookings/
│       │   └── index.blade.php       # Daftar bookings
│       ├── payments/
│       │   └── index.blade.php       # Daftar payments
│       └── packages/
│           └── index.blade.php       # Daftar packages
└── routes/
    ├── web.php    # Routes untuk web interface
    └── api.php    # Routes untuk API
```

## 🚀 Cara Menggunakan

### 1. Setup Database (Jika Belum)

```bash
cd c:\laragon\www\MuaraV2\photo-studio-backend

# Generate key
php artisan key:generate

# Buat database
mysql -u root -e "CREATE DATABASE photo_studio"

# Migrate & seed
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Start server
php artisan serve
```

### 2. Akses Dashboard

**URL:** `http://localhost:8000`

Akan auto-redirect ke login page: `http://localhost:8000/login`

### 3. Login

**Admin:**
- Email: `admin@photostudio.com`
- Password: `password123`

**Customer:**
- Email: `budi@example.com`
- Password: `password123`

### 4. Navigasi Dashboard

Setelah login, Anda akan masuk ke dashboard dengan sidebar menu:

- **Dashboard** - Overview stats & recent bookings
- **Booking** - Manage semua bookings
- **Kelola Pembayaran** - Manage payments
- **Daftar Paket** - CRUD packages
- Dan menu lainnya...

## 📊 Fitur Dashboard

### 1. **Dashboard Utama** (`/dashboard`)
- 3 Stats cards (Pendapatan, Total Bookings, Pending Payments)
- Tabel booking terbaru
- Real-time data dari database

### 2. **Booking Management** (`/bookings`)
- List semua bookings dengan pagination
- Filter by status
- Search by booking code atau customer name
- View detail booking
- Update status (Admin only)

### 3. **Payment Management** (`/payments`)
- List semua payments dengan pagination
- Filter by status
- Search by payment code
- View detail payment
- Update status pembayaran (Admin only)

### 4. **Package Management** (`/packages`)
- List semua paket dalam grid view
- Create new package (Admin only)
- Edit package (Admin only)
- Delete package (Admin only)
- View detail dengan statistik bookings

## 🔐 Authentication & Authorization

### Middleware yang Digunakan

1. **`auth`** - Require user login
2. **`check.user.status`** - Check if user active
3. **`admin`** - Check if user is admin
4. **`guest`** - Hanya untuk yang belum login

### Protected Routes

```php
// Semua route di /dashboard, /bookings, /payments, /packages
Route::middleware(['auth', 'check.user.status'])->group(function () {
    // Routes here
});

// Admin only routes
Route::middleware('admin')->group(function () {
    // Update booking/payment status
    // CRUD packages
});
```

## 🎨 Blade Components

### Layouts

**`layouts/app.blade.php`**
- Base layout dengan Tailwind CSS
- Font Awesome icons
- CSRF token setup
- Sections: `title`, `content`, `styles`, `scripts`

**`layouts/dashboard.blade.php`**
- Extends `app.blade.php`
- Include sidebar & header
- Section: `dashboard-content`, `page-title`

### Partials

**`partials/sidebar.blade.php`**
- Navigation menu dengan active state
- User profile di bottom
- Logout button

**`partials/header.blade.php`**
- Page title dynamic
- Notification bell

## 💻 Cara Mengembangkan

### Membuat Halaman Baru

1. **Buat Controller**
```bash
php artisan make:controller Web/YourController
```

2. **Buat View**
```bash
# Buat file di resources/views/your-page/index.blade.php
```

3. **Tambah Route**
```php
// routes/web.php
Route::get('/your-page', [YourController::class, 'index'])->name('your.page');
```

### Menambah Menu Sidebar

Edit file `resources/views/partials/sidebar.blade.php`:

```blade
<a href="{{ route('your.route') }}" 
   class="flex items-center space-x-3 p-3 {{ request()->routeIs('your.route') ? 'sidebar-active' : 'hover:bg-white/10 rounded-lg transition' }}">
    <i class="fas fa-your-icon"></i>
    <span>Menu Name</span>
</a>
```

### Menggunakan Flash Messages

Di Controller:
```php
return redirect()->route('some.route')
    ->with('success', 'Action completed successfully!');

return back()->with('error', 'Something went wrong!');
```

Di View (tambahkan di dashboard content):
```blade
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif
```

## 🎯 API vs Web Routes

### API Routes (`/api/*`)
- Untuk mobile app atau external frontend
- Return JSON response
- Token-based auth (Laravel Sanctum)
- Controllers di `App\Http\Controllers\Api`

### Web Routes (`/*`)
- Untuk dashboard web internal
- Return Blade views
- Session-based auth
- Controllers di `App\Http\Controllers\Web`

## 📝 Best Practices

### 1. Controllers
- Pisahkan logic Web & API
- Web controllers return views
- API controllers return JSON
- Gunakan Form Requests untuk validation

### 2. Views
- Gunakan `@extends`, `@section`, `@yield`
- Pisahkan ke partials untuk reusability
- Gunakan Blade directives (`@if`, `@foreach`, `@auth`)

### 3. Routes
- Group by middleware
- Named routes untuk flexibility
- Resource routes untuk CRUD

### 4. Security
- CSRF protection otomatis untuk POST/PUT/DELETE
- Validate semua input
- Check authorization di controller
- Sanitize output dengan `{{ }}` (auto-escape)

## 🐛 Troubleshooting

### Views tidak muncul
```bash
php artisan view:clear
php artisan config:clear
```

### Session tidak work
- Check `SESSION_DRIVER` di `.env` (default: file)
- Check permissions folder `storage/framework/sessions`

### Redirect loop saat login
- Clear browser cache & cookies
- Check middleware order di routes

### CSRF token mismatch
```bash
php artisan cache:clear
php artisan config:clear
```

## 🔄 Perbedaan dengan HTML Static

| Aspek | HTML Static (Sebelum) | Laravel Blade (Sekarang) |
|-------|----------------------|-------------------------|
| **Data** | Hardcoded / API fetch | Langsung dari database |
| **Auth** | localStorage token | Laravel session |
| **Routing** | Manual | Laravel routing |
| **Templating** | Copy-paste HTML | Blade components reusable |
| **Security** | Manual | Built-in CSRF, XSS protection |
| **Maintenance** | Sulit | Mudah dengan components |

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/10.x/blade)
- [Laravel Authentication](https://laravel.com/docs/10.x/authentication)
- [Tailwind CSS](https://tailwindcss.com/docs)

## ✅ Checklist Fitur

- ✅ Login/Logout
- ✅ Dashboard dengan stats
- ✅ Booking list & filter
- ✅ Payment list & filter
- ✅ Package list (grid view)
- ✅ Responsive sidebar
- ✅ User profile
- ✅ Role-based access control
- 🔄 Booking detail page
- 🔄 Payment detail page
- 🔄 Package CRUD forms
- 🔄 Schedule management
- 🔄 Gallery management
- 🔄 Reports & analytics

---

**Dashboard Laravel siap digunakan!** 🎉

Start server: `php artisan serve`  
Akses: `http://localhost:8000`
