# Quick Start - Mobile Integration

## ✅ Backend Sudah SIAP untuk Mobile!

Database sama untuk **Web Dashboard** dan **Mobile App**.

---

## 🚀 3 Langkah Sederhana

### 1. Pastikan Backend Berjalan

```bash
cd c:\laragon\www\MuaraV2\photo-studio-backend
php artisan serve
```

**API Base URL:** `http://localhost:8000/api`  
**Web Dashboard:** `http://localhost:8000`

### 2. Test API dengan Postman

1. Import file: `postman_collection.json`
2. Test endpoint `/api/login`
3. Copy token yang didapat
4. Test endpoint lain dengan token

### 3. Integrasikan ke Mobile App

Lihat contoh lengkap di: **`MOBILE_INTEGRATION_GUIDE.md`**

---

## 📱 Contoh Login untuk Mobile

### Flutter

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<void> login(String email, String password) async {
  final response = await http.post(
    Uri.parse('http://localhost:8000/api/login'),
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: jsonEncode({
      'email': email,
      'password': password,
    }),
  );

  final data = jsonDecode(response.body);
  
  if (data['success']) {
    String token = data['data']['access_token'];
    // Simpan token ke SharedPreferences
    print('Login berhasil! Token: $token');
  } else {
    print('Login gagal: ${data['message']}');
  }
}
```

### React Native

```javascript
const login = async (email, password) => {
  try {
    const response = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });

    const data = await response.json();
    
    if (data.success) {
      const token = data.data.access_token;
      // Simpan token ke AsyncStorage
      await AsyncStorage.setItem('auth_token', token);
      console.log('Login berhasil!');
    }
  } catch (error) {
    console.error('Login error:', error);
  }
};
```

### Android (Kotlin)

```kotlin
suspend fun login(email: String, password: String) {
    val client = OkHttpClient()
    val json = JSONObject().apply {
        put("email", email)
        put("password", password)
    }
    
    val body = RequestBody.create(
        "application/json".toMediaType(),
        json.toString()
    )
    
    val request = Request.Builder()
        .url("http://localhost:8000/api/login")
        .post(body)
        .addHeader("Accept", "application/json")
        .build()
    
    val response = client.newCall(request).execute()
    val responseData = JSONObject(response.body?.string() ?: "")
    
    if (responseData.getBoolean("success")) {
        val token = responseData.getJSONObject("data")
            .getString("access_token")
        // Simpan token ke SharedPreferences
        println("Login berhasil! Token: $token")
    }
}
```

---

## 🔑 Test Credentials

```
Admin:
- Email: admin@photostudio.com
- Password: password123

Customer:
- Email: budi@example.com
- Password: password123
```

---

## 📋 API Endpoints Utama

| Endpoint | Method | Auth Required | Deskripsi |
|----------|--------|---------------|-----------|
| `/api/register` | POST | ❌ | Register user baru |
| `/api/login` | POST | ❌ | Login & get token |
| `/api/logout` | POST | ✅ | Logout |
| `/api/user` | GET | ✅ | Get user profile |
| `/api/packages` | GET | ❌ | List semua paket |
| `/api/packages/{id}` | GET | ❌ | Detail paket |
| `/api/schedules` | GET | ❌ | Jadwal tersedia |
| `/api/schedules/check-availability` | GET | ❌ | Cek ketersediaan |
| `/api/bookings` | GET | ✅ | List booking user |
| `/api/bookings` | POST | ✅ | Buat booking baru |
| `/api/bookings/{id}` | GET | ✅ | Detail booking |
| `/api/bookings/{id}/cancel` | POST | ✅ | Cancel booking |
| `/api/payments` | POST | ✅ | Buat payment |
| `/api/payments/{id}` | GET | ✅ | Status payment |
| `/api/galleries` | GET | ❌ | List gallery |
| `/api/reviews` | GET | ❌ | List reviews |
| `/api/reviews` | POST | ✅ | Buat review |
| `/api/dashboard` | GET | ✅ | Customer dashboard |

✅ = Require `Authorization: Bearer {token}` header

---

## 🔐 Cara Menggunakan Token

Setelah login, simpan token dan gunakan di setiap request:

```
Headers:
  Authorization: Bearer {access_token}
  Accept: application/json
  Content-Type: application/json
```

---

## 🧪 Testing Flow

1. **Register** → Buat akun baru
2. **Login** → Dapat token
3. **Get Packages** → Lihat paket tersedia
4. **Get Schedules** → Cek jadwal
5. **Create Booking** → Buat booking
6. **Create Payment** → Bayar booking
7. **Get My Bookings** → Lihat riwayat

---

## 🌐 CORS Sudah Enabled

API sudah configured untuk menerima request dari:
- Mobile apps (Flutter, React Native)
- Web apps (React, Vue, Angular)
- External clients

**Tidak perlu konfigurasi tambahan!**

---

## 📖 Dokumentasi Lengkap

- **`MOBILE_INTEGRATION_GUIDE.md`** - Dokumentasi API lengkap + contoh code
- **`API_DOCUMENTATION.md`** - Reference API endpoints
- **`postman_collection.json`** - Postman collection untuk testing

---

## 💡 Tips

1. **Development:** Gunakan IP komputer, bukan `localhost`
   ```
   # Find your IP
   ipconfig (Windows)
   ifconfig (Mac/Linux)
   
   # Use IP in mobile
   http://192.168.1.100:8000/api
   ```

2. **Production:** Ganti base URL ke domain production
   ```
   https://api.your-domain.com
   ```

3. **Token Storage:**
   - Flutter: `flutter_secure_storage`
   - React Native: `@react-native-async-storage/encrypted-storage`
   - Android: `EncryptedSharedPreferences`

4. **Error Handling:** Always handle network errors

---

## 🆘 Troubleshooting

### Cannot connect from mobile

**Problem:** Mobile app tidak bisa connect ke `localhost`

**Solution:** Gunakan IP komputer
```dart
// Jangan
final baseUrl = 'http://localhost:8000/api';

// Gunakan ini
final baseUrl = 'http://192.168.1.100:8000/api';
```

### CORS Error

**Problem:** CORS policy blocking

**Solution:** Sudah handled di backend, pastikan:
- Headers `Accept: application/json` ada
- Backend berjalan

### 401 Unauthorized

**Problem:** Token invalid atau expired

**Solution:**
- Check token tersimpan dengan benar
- Login ulang untuk get new token
- Pastikan format: `Bearer {token}`

---

## ✅ Checklist Integrasi

- [ ] Backend berjalan (`php artisan serve`)
- [ ] Test API dengan Postman
- [ ] Implement login di mobile
- [ ] Simpan token setelah login
- [ ] Implement get packages
- [ ] Implement create booking
- [ ] Handle error responses
- [ ] Test complete flow

---

**Backend API READY! Mulai integrate sekarang!** 🚀

Support: Lihat `MOBILE_INTEGRATION_GUIDE.md` untuk contoh lengkap.
