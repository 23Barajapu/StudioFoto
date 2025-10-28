# Backend Integration Setup - Flutter

## ✅ Kode Sudah Diupdate!

File berikut sudah diupdate untuk connect ke backend Laravel:
- ✅ `lib/services/auth_service.dart` - Service untuk API authentication
- ✅ `lib/screens/login_screen.dart` - Login screen dengan backend integration

---

## 🚀 Cara Menggunakan

### 1. Install Dependencies

Tambahkan ke `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.2
```

Lalu jalankan:
```bash
flutter pub get
```

### 2. Setup Backend URL

**PENTING:** Buka file `lib/services/auth_service.dart` dan ganti IP address:

```dart
// Ganti dengan IP komputer Anda (JANGAN gunakan localhost!)
static const String baseUrl = 'http://192.168.1.100:8000/api';
```

**Cara cek IP komputer Anda:**
- Windows: Buka CMD, ketik `ipconfig`, cari IPv4 Address
- Mac: Buka Terminal, ketik `ifconfig`, cari inet address
- Contoh: `192.168.1.100`

### 3. Pastikan Backend Berjalan

Di folder backend:
```bash
cd c:\laragon\www\MuaraV2\photo-studio-backend
php artisan serve
```

Backend akan berjalan di: `http://localhost:8000`

### 4. Test Login

Gunakan credentials ini untuk testing:

**Admin:**
- Email: `admin@photostudio.com`
- Password: `password123`

**Customer:**
- Email: `budi@example.com`
- Password: `password123`

---

## 📝 Yang Sudah Dibuat

### 1. AuthService (`lib/services/auth_service.dart`)

Service untuk handle semua authentication:

```dart
final authService = AuthService();

// Login
await authService.login('email@example.com', 'password');

// Register
await authService.register(
  name: 'John Doe',
  email: 'john@example.com',
  password: 'password123',
  passwordConfirmation: 'password123',
);

// Logout
await authService.logout();

// Get Profile
await authService.getUserProfile();

// Check if logged in
bool isLoggedIn = authService.isLoggedIn;

// Get current user
Map<String, dynamic>? user = authService.user;
```

### 2. Login Screen (`lib/screens/login_screen.dart`)

Login screen sudah terintegrasi dengan:
- ✅ Form validation
- ✅ API call ke backend
- ✅ Loading state
- ✅ Error handling
- ✅ Success message
- ✅ Navigate ke home setelah login

---

## 🔧 Troubleshooting

### 1. Error: Connection refused

**Problem:** Tidak bisa connect ke backend

**Solution:**
1. Pastikan backend berjalan (`php artisan serve`)
2. Gunakan IP komputer, bukan `localhost`
3. Pastikan phone dan komputer di network yang sama (WiFi yang sama)

### 2. Error: FormatException

**Problem:** Response bukan JSON

**Solution:**
1. Check backend berjalan dengan benar
2. Test di browser: `http://YOUR_IP:8000/api/health`
3. Lihat console log untuk response detail

### 3. Email/Password salah

**Problem:** Login gagal

**Solution:**
- Gunakan credentials yang benar (lihat di atas)
- Pastikan database sudah di-seed:
  ```bash
  php artisan db:seed
  ```

---

## 📱 Next Steps - Fitur Lain

Setelah login berhasil, Anda bisa implement fitur lainnya:

### Get Packages

```dart
// Buat file: lib/services/package_service.dart
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'auth_service.dart';

class PackageService {
  static const String baseUrl = AuthService.baseUrl;

  Future<List<dynamic>> getPackages() async {
    final response = await http.get(
      Uri.parse('$baseUrl/packages'),
      headers: {
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data'];
    }
    return [];
  }
}
```

### Create Booking

```dart
// Buat file: lib/services/booking_service.dart
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'auth_service.dart';

class BookingService {
  final AuthService _authService;

  BookingService(this._authService);

  static const String baseUrl = AuthService.baseUrl;

  Future<Map<String, dynamic>> createBooking({
    required int packageId,
    required int scheduleId,
    String? notes,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/bookings'),
      headers: _authService.getAuthHeaders(),
      body: jsonEncode({
        'package_id': packageId,
        'schedule_id': scheduleId,
        'customer_notes': notes,
      }),
    );

    return jsonDecode(response.body);
  }

  Future<List<dynamic>> getMyBookings() async {
    final response = await http.get(
      Uri.parse('$baseUrl/bookings'),
      headers: _authService.getAuthHeaders(),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data']['data'];
    }
    return [];
  }
}
```

---

## 📚 API Endpoints Available

| Endpoint | Method | Auth | Deskripsi |
|----------|--------|------|-----------|
| `/api/register` | POST | ❌ | Register user baru |
| `/api/login` | POST | ❌ | Login |
| `/api/logout` | POST | ✅ | Logout |
| `/api/user` | GET | ✅ | Get profile |
| `/api/packages` | GET | ❌ | List paket |
| `/api/schedules` | GET | ❌ | Jadwal tersedia |
| `/api/bookings` | POST | ✅ | Buat booking |
| `/api/bookings` | GET | ✅ | My bookings |
| `/api/payments` | POST | ✅ | Buat payment |

✅ = Require Authorization header

**Dokumentasi lengkap:** Lihat `photo-studio-backend/MOBILE_INTEGRATION_GUIDE.md`

---

## ✅ Checklist

- [ ] Install dependencies (`http`, `shared_preferences`)
- [ ] Update IP address di `auth_service.dart`
- [ ] Backend berjalan (`php artisan serve`)
- [ ] Test login dengan credentials yang benar
- [ ] Login berhasil dan navigate ke home screen

---

**Backend sudah READY! Database sama dengan web dashboard!** 🚀

Test credentials:
- Admin: `admin@photostudio.com` / `password123`
- Customer: `budi@example.com` / `password123`
