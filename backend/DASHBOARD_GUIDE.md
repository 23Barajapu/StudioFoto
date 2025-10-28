# Panduan Dashboard Prime Studio

## 📱 Fitur Dashboard

Dashboard telah dibuat dengan desain modern dan responsive yang sesuai dengan mockup yang Anda berikan.

### ✨ Fitur Utama

1. **Sidebar Navigation**
   - Dashboard
   - Booking
   - Kelola Pembayaran
   - Notifikasi
   - Riwayat Pemesanan
   - Daftar Paket
   - Laporan Pendapatan

2. **Dashboard Cards**
   - **Pendapatan** - Menampilkan total revenue dengan indikator kenaikan
   - **Riwayat Pemesanan** - Total bookings
   - **Kelola Pembayaran** - Pending payments yang perlu perhatian

3. **Tabel Booking**
   - ID Booking
   - Nama Customer
   - Tanggal & Waktu
   - Paket yang dipilih
   - Harga
   - Jumlah Orang
   - Status Pembayaran (Lunas/DP/Belum Lunas)

## 🚀 Cara Menggunakan

### 1. Setup Backend (Jika belum)

```bash
# Masuk ke direktori
cd c:\laragon\www\MuaraV2\photo-studio-backend

# Generate key (jika belum)
php artisan key:generate

# Buat database
mysql -u root -e "CREATE DATABASE photo_studio"

# Jalankan migration & seeder
php artisan migrate --seed

# Jalankan server
php artisan serve
```

### 2. Akses Dashboard

#### **Via Laragon (Recommended)**
1. Start Laragon
2. Buka browser: `http://muarav2.test` atau `http://localhost/MuaraV2/photo-studio-backend/public`

#### **Via php artisan serve**
1. Jalankan: `php artisan serve`
2. Buka browser: `http://localhost:8000`

### 3. Login

**URL Login:** `http://localhost:8000/login.html`

**Akun Admin:**
- Email: `admin@photostudio.com`
- Password: `password123`

**Akun Customer:**
- Email: `budi@example.com`
- Password: `password123`

### 4. Dashboard

Setelah login berhasil, Anda akan diarahkan ke: `http://localhost:8000/dashboard.html`

## 📊 Data yang Ditampilkan

Dashboard secara otomatis mengambil data dari API backend:

- **Total Pendapatan** - Dari semua pembayaran sukses
- **Total Booking** - Jumlah semua booking
- **Pending Payments** - Booking yang belum dibayar
- **Recent Bookings** - 5 booking terbaru dengan detail lengkap

Data akan **auto-refresh setiap 30 detik**.

## 🎨 Fitur UI/UX

- ✅ **Responsive Design** - Bekerja di desktop, tablet, dan mobile
- ✅ **Modern Interface** - Gradient purple theme seperti mockup
- ✅ **Icons** - Font Awesome icons untuk visual yang menarik
- ✅ **Hover Effects** - Smooth transitions dan hover states
- ✅ **Status Badges** - Color-coded untuk status pembayaran
- ✅ **Real-time Data** - Langsung dari API backend
- ✅ **Loading States** - Spinner saat memuat data
- ✅ **Error Handling** - Pesan error yang user-friendly

## 🔧 Kustomisasi

### Mengubah Warna Theme

Edit di file `dashboard.html` bagian CSS:

```css
.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    /* Ubah warna sesuai keinginan */
}
```

### Mengubah API Base URL

Edit di file `dashboard.html` dan `login.html`:

```javascript
const API_BASE_URL = 'http://localhost:8000/api';
// Ubah ke URL production jika deploy
```

### Menambah Menu Sidebar

Edit di file `dashboard.html` bagian sidebar:

```html
<a href="#" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-lg transition">
    <i class="fas fa-your-icon"></i>
    <span>Menu Baru</span>
</a>
```

## 📱 Halaman Tambahan

### Login Page
- **URL:** `/login.html`
- **Fitur:**
  - Form login dengan validasi
  - Toggle show/hide password
  - Remember me checkbox
  - Forgot password link
  - Info akun demo
  - Auto redirect jika sudah login

### Dashboard Page
- **URL:** `/dashboard.html`
- **Fitur:**
  - Protected route (butuh login)
  - Stats cards dengan live data
  - Bookings table
  - User profile di sidebar
  - Logout button
  - Notification bell dengan badge

## 🔐 Security

- ✅ Token disimpan di localStorage
- ✅ Protected routes (cek token)
- ✅ Auto redirect ke login jika token invalid
- ✅ Bearer token untuk semua API request
- ✅ CORS enabled untuk API

## 🐛 Troubleshooting

### Dashboard tidak menampilkan data

1. **Pastikan backend berjalan:**
   ```bash
   php artisan serve
   ```

2. **Check di browser console (F12):**
   - Lihat error di Console tab
   - Check network request di Network tab

3. **Pastikan database sudah di-seed:**
   ```bash
   php artisan db:seed
   ```

### Error CORS

Pastikan file `config/cors.php` sudah ada dan configured correctly.

### Token expired / Invalid

Clear localStorage dan login ulang:
```javascript
localStorage.clear()
```

## 📞 Next Steps

Halaman yang bisa ditambahkan:

1. **Booking Management** - CRUD booking lengkap
2. **Payment Management** - Update status pembayaran
3. **Package Management** - CRUD paket layanan
4. **Schedule Management** - Kelola jadwal
5. **Gallery Management** - Upload foto portfolio
6. **Review Management** - Approve/reject review
7. **User Management** - Manage users
8. **Reports** - Revenue charts & analytics

## 💡 Tips

1. **Development:** Gunakan `php artisan serve` untuk testing cepat
2. **Production:** Setup proper web server (Apache/Nginx)
3. **Database:** Backup data secara regular
4. **Security:** Ganti password default sebelum production
5. **Performance:** Enable caching untuk production

---

**Dashboard siap digunakan!** 🎉

Buka: `http://localhost:8000/login.html`
