# Troubleshooting Login & Register - Mobile App

## ❌ Masalah: Tidak Bisa Login/Register

**Error:** `ClientException: Failed to fetch`

## ✅ Solusi Lengkap:

### 🔧 Step 1: Pastikan Backend Berjalan

Buka **CMD/Terminal BARU**, jalankan:

```bash
cd c:\laragon\www\MuaraV2\backend
php artisan serve
```

**Expected Output:**
```
Starting Laravel development server: http://127.0.0.1:8000
[Tue Oct 28 12:00:00 2025] PHP 8.x Development Server (http://127.0.0.1:8000) started
```

**❗ PENTING:** Jangan tutup terminal ini! Biarkan tetap running.

---

### 🌐 Step 2: Test Backend dari Browser Komputer

Buka browser, akses:
```
http://localhost:8000/api/health
```

**Expected Response:**
```json
{
  "success": true,
  "message": "API is running",
  "timestamp": "2025-10-28T05:00:00.000000Z"
}
```

Jika error atau tidak muncul, berarti backend belum running dengan benar.

---

### 📱 Step 3: Pilih URL yang BENAR untuk Device Anda

**⚠️ CRITICAL:** `http://localhost:8000/api` **TIDAK AKAN BEKERJA** untuk mobile app!

Buka file: `c:\laragon\www\MuaraV2\mobile\lib\services\auth_service.dart`

**Pilih salah satu berdasarkan device:**

#### **Option 1: Android Emulator (AVD)** ✅ RECOMMENDED
```dart
static const String baseUrl = 'http://10.0.2.2:8000/api';
```

**Kenapa 10.0.2.2?**
- `10.0.2.2` adalah IP khusus Android Emulator untuk akses localhost komputer host
- `localhost` di Android Emulator = device emulator itu sendiri (BUKAN komputer!)

#### **Option 2: iOS Simulator**
```dart
static const String baseUrl = 'http://127.0.0.1:8000/api';
// atau
static const String baseUrl = 'http://localhost:8000/api';
```

#### **Option 3: Real Device (HP/Tablet Fisik)**

**A. Cek IP Komputer Anda:**

Buka CMD, ketik:
```bash
ipconfig
```

Cari bagian **IPv4 Address**, contoh:
```
Wireless LAN adapter Wi-Fi:
   IPv4 Address. . . . . . . . . . . : 192.168.1.105
```

**B. Update baseUrl:**
```dart
static const String baseUrl = 'http://192.168.1.105:8000/api';
// ☝️ Ganti dengan IP ANDA!
```

**C. Start Backend dengan IP 0.0.0.0:**
```bash
# Stop php artisan serve yang lama (Ctrl+C)
# Jalankan dengan host 0.0.0.0
php artisan serve --host=0.0.0.0 --port=8000
```

**D. Pastikan HP dan Komputer di WiFi yang SAMA!**

---

### 🔄 Step 4: FULL RESTART Flutter App

**PENTING:** Karena `baseUrl` adalah `const String`, hot reload (`r`) **TIDAK CUKUP**!

**Cara 1: Shift + R (Full Restart)**
Di terminal Flutter, tekan:
```
Shift + R
```

**Cara 2: Stop & Run Ulang**
```bash
# Di terminal Flutter
Ctrl + C (stop app)

# Run ulang
flutter run
```

**Cara 3: Clean & Rebuild**
```bash
flutter clean
flutter pub get
flutter run
```

---

### 🧪 Step 5: Test Login/Register

#### **Test Login:**
- Email: `admin@photostudio.com`
- Password: `password123`

#### **Test Register:**
- Nama: Test User
- Email: test@example.com (gunakan email baru yang belum terdaftar)
- Password: password123
- Konfirmasi: password123

---

## 🔍 Troubleshooting Lanjutan:

### ❌ Error: Connection Refused

**Kemungkinan:**
1. Backend tidak running
2. IP address salah
3. Firewall blocking

**Solusi:**
```bash
# Check backend running
netstat -ano | findstr :8000

# Jika kosong, start backend:
php artisan serve
```

### ❌ Error: 404 Not Found

**Solusi:** Check route di `backend/routes/api.php`, pastikan route `/api/register` dan `/api/login` ada.

### ❌ Error: 422 Validation Failed

**Solusi:** Check data yang dikirim:
- Email harus valid format
- Password minimal 8 karakter
- Password dan konfirmasi harus sama
- Email belum terdaftar (untuk register)

### ❌ Error: SSL/Certificate Error

**Solusi:** Jangan gunakan `https://`, gunakan `http://` untuk local development.

---

## 📊 Quick Reference Table:

| Device | Base URL | Backend Command | Notes |
|--------|----------|----------------|-------|
| Android Emulator | `http://10.0.2.2:8000/api` | `php artisan serve` | ✅ Recommended |
| iOS Simulator | `http://127.0.0.1:8000/api` | `php artisan serve` | ✅ Works |
| Real Device (WiFi) | `http://192.168.x.x:8000/api` | `php artisan serve --host=0.0.0.0` | Check IP first |
| Windows Emulator | `http://localhost:8000/api` | `php artisan serve` | ✅ Works |
| Web (Chrome) | `http://localhost:8000/api` | `php artisan serve` | ✅ Works |

---

## ✅ Final Checklist:

Sebelum test, pastikan:

- [ ] **Backend running:** `php artisan serve` berjalan tanpa error
- [ ] **Test browser:** `http://localhost:8000/api/health` return JSON
- [ ] **IP address benar** di `auth_service.dart` sesuai device
- [ ] **Full restart app:** `Shift + R` atau `flutter run` ulang
- [ ] **Console clear:** Tidak ada error di terminal Flutter
- [ ] **(Jika Real Device)** HP dan komputer di WiFi yang sama

---

## 🎯 Test Cepat:

### **1. Test dari Browser (di Komputer)**

Buka Console (F12), jalankan:

```javascript
// Test Health
fetch('http://localhost:8000/api/health')
  .then(r => r.json())
  .then(console.log);

// Test Login
fetch('http://localhost:8000/api/login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    email: 'admin@photostudio.com',
    password: 'password123'
  })
})
.then(r => r.json())
.then(console.log);
```

Jika berhasil di browser, berarti backend OK. Tinggal fix IP di mobile.

### **2. Test dari Android Emulator (Browser)**

Buka Chrome di Android Emulator, akses:
```
http://10.0.2.2:8000/api/health
```

Jika berhasil, berarti backend accessible dari emulator.

---

## 📞 Masih Error?

Jika masih error setelah ikuti semua steps:

1. **Share error message lengkap** dari console Flutter
2. **Share device yang digunakan** (Android/iOS/Real Device)
3. **Share output** dari `php artisan serve`
4. **Share IP address** dari `ipconfig`

---

**Good Luck!** 🚀

Jika sudah berhasil login/register, hapus file ini atau tandai sebagai solved.
