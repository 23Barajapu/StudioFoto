# Mobile Screens Guide - Prime Studio

## 📱 Screens yang Sudah Dibuat

### 1. ✅ Welcome Screen (`welcome_screen.dart`)
**Design:** Selamat Datang di Prime Studio!

**Fitur:**
- Logo studio dengan gradient background
- Welcome message
- 2 tombol:
  - **Buat Akun** (blue) → Navigate ke Register
  - **Login** (outline blue) → Navigate ke Login

**Navigation:**
```dart
Navigator.push(
  context,
  MaterialPageRoute(builder: (context) => const WelcomeScreen()),
);
```

---

### 2. ✅ Login Screen (`login_screen.dart`)
**Design:** Login page dengan email & password

**Fitur:**
- ✅ Email field dengan validation
- ✅ Password field dengan show/hide toggle
- ✅ Lupa Password link
- ✅ Login button dengan loading state
- ✅ Google Sign-in button
- ✅ **Connected ke Backend API**
- ✅ Form validation
- ✅ Error handling
- ✅ Success message
- ✅ Auto navigate ke Home setelah login

**API Integration:**
```dart
final result = await _authService.login(email, password);
// Token disimpan otomatis ke SharedPreferences
```

**Test Credentials:**
- Admin: `admin@photostudio.com` / `password123`
- Customer: `budi@example.com` / `password123`

---

### 3. ✅ Register Screen (`register_screen.dart`)
**Design:** Register page dengan 4 fields

**Fitur:**
- ✅ Nama Lengkap field
- ✅ Email field dengan validation
- ✅ Password field dengan validation (min 8 karakter)
- ✅ Konfirmasi Password field dengan validation
- ✅ Show/hide password toggle
- ✅ Registrasi button dengan loading state
- ✅ Link ke Login page
- ✅ **Connected ke Backend API**
- ✅ Form validation
- ✅ Error handling dengan detail message
- ✅ Auto login setelah registrasi berhasil

**API Integration:**
```dart
final result = await _authService.register(
  name: name,
  email: email,
  password: password,
  passwordConfirmation: passwordConfirmation,
);
// Otomatis login dan navigate ke Home
```

**Validation Rules:**
- Nama: Required
- Email: Required, valid format
- Password: Required, minimal 8 karakter
- Konfirmasi Password: Required, harus sama dengan password

---

## 🎨 Design Consistency

Semua screens menggunakan:
- **Primary Color:** `Color(0xFF5C6BC0)` (Blue/Indigo)
- **Background:** White
- **Text Color:** Black untuk title, `Color(0xFF666666)` untuk subtitle
- **Input Background:** `Color(0xFFF5F5F5)` (Light grey)
- **Border Radius:** 12px untuk input fields, 28px untuk buttons
- **Font Weight:** Bold (28px) untuk title, W600 untuk buttons

---

## 🔄 Navigation Flow

```
Welcome Screen
    │
    ├─> Register Screen ──> (Success) ──> Home Screen
    │                    └─> (Link) ──> Login Screen
    │
    └─> Login Screen ──> (Success) ──> Home Screen
                     └─> (Link) ──> Register Screen
```

---

## 🔐 Authentication State

### Login & Register Flow

1. **User mengisi form**
2. **Validation** (client-side)
3. **API call** ke backend Laravel
4. **Response handling:**
   - Success: Save token → Navigate to Home
   - Error: Show error message

### Token Management

Token disimpan otomatis di `SharedPreferences`:
```dart
// AuthService automatically handles this
await prefs.setString('auth_token', token);
await prefs.setString('user', jsonEncode(user));
```

Check login status:
```dart
bool isLoggedIn = _authService.isLoggedIn;
```

---

## 🧪 Testing Guide

### 1. Test Welcome Screen
- [ ] Tombol "Buat Akun" navigate ke Register
- [ ] Tombol "Login" navigate ke Login
- [ ] Design sesuai dengan mockup

### 2. Test Login Screen
- [ ] Email validation (required, format email)
- [ ] Password validation (required, min 6 char)
- [ ] Show/hide password works
- [ ] Login dengan credentials benar → Success
- [ ] Login dengan credentials salah → Error message
- [ ] Loading state saat API call
- [ ] Navigate ke Home setelah success

**Test Scenarios:**
```dart
// Valid
Email: admin@photostudio.com
Password: password123
Expected: Success, navigate to Home

// Invalid Email
Email: invalid-email
Password: password123
Expected: Validation error "Email tidak valid"

// Wrong Password
Email: admin@photostudio.com
Password: wrongpassword
Expected: Error "Email atau password salah"

// Empty Fields
Email: (empty)
Password: (empty)
Expected: Validation errors
```

### 3. Test Register Screen
- [ ] All fields validation
- [ ] Password match validation
- [ ] Show/hide password works
- [ ] Register dengan data valid → Success
- [ ] Register dengan email sudah terdaftar → Error
- [ ] Loading state saat API call
- [ ] Navigate ke Home setelah success (auto login)

**Test Scenarios:**
```dart
// Valid Registration
Nama: Test User
Email: testuser@example.com
Password: password123
Konfirmasi: password123
Expected: Success, auto login, navigate to Home

// Password Not Match
Nama: Test User
Email: testuser@example.com
Password: password123
Konfirmasi: password456
Expected: Validation error "Password tidak cocok"

// Email Already Exists
Nama: Test User
Email: admin@photostudio.com (already exists)
Password: password123
Konfirmasi: password123
Expected: Error "Email sudah terdaftar"

// Password Too Short
Password: 12345
Expected: Validation error "Password minimal 8 karakter"
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Connection Refused
**Problem:** Login/Register gagal dengan error connection

**Solution:**
1. Pastikan backend berjalan: `php artisan serve`
2. Update IP di `auth_service.dart`:
   ```dart
   static const String baseUrl = 'http://YOUR_IP:8000/api';
   ```
3. Phone dan komputer di WiFi yang sama

### Issue 2: Token Tidak Tersimpan
**Problem:** Setelah login, tetap diminta login lagi

**Solution:**
Check SharedPreferences permissions di AndroidManifest.xml

### Issue 3: Validation Tidak Muncul
**Problem:** Form bisa submit meskipun ada error

**Solution:**
Pastikan menggunakan `Form` widget dengan `GlobalKey<FormState>`:
```dart
if (!_formKey.currentState!.validate()) {
  return;
}
```

---

## 📝 Next Steps - Screens to Build

Setelah Login & Register berhasil, build screens berikut:

### 4. Home Screen
- Dashboard customer
- List packages
- Upcoming bookings
- Quick actions

### 5. Package List Screen
- Grid/List view packages
- Filter & search
- Detail package view

### 6. Package Detail Screen
- Package information
- Features list
- Gallery photos
- Book now button

### 7. Schedule Screen
- Calendar view
- Available time slots
- Select date & time

### 8. Booking Screen
- Booking form
- Selected package & schedule
- Customer notes
- Confirm booking

### 9. My Bookings Screen
- List user's bookings
- Filter by status
- Booking details
- Cancel booking

### 10. Payment Screen
- Payment methods
- Amount details
- Pay now button
- Payment status

### 11. Profile Screen
- User info
- Edit profile
- Change password
- Logout

---

## 🎯 Backend API Endpoints Available

All ready to use from mobile:

| Screen | API Endpoint | Method |
|--------|-------------|---------|
| Login | `/api/login` | POST |
| Register | `/api/register` | POST |
| Home (Packages) | `/api/packages` | GET |
| Package Detail | `/api/packages/{id}` | GET |
| Schedules | `/api/schedules?available=true` | GET |
| Create Booking | `/api/bookings` | POST |
| My Bookings | `/api/bookings` | GET |
| Create Payment | `/api/payments` | POST |
| User Profile | `/api/user` | GET |
| Logout | `/api/logout` | POST |

---

## 📚 Resources

- **Backend Integration:** `BACKEND_SETUP.md`
- **API Documentation:** `../photo-studio-backend/MOBILE_INTEGRATION_GUIDE.md`
- **AuthService:** `lib/services/auth_service.dart`

---

**Welcome, Login, dan Register screens sudah READY dan Connected ke Backend!** ✅

**Next:** Build Home Screen dan Package List Screen untuk menampilkan data dari API.
