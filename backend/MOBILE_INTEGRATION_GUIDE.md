# Mobile App Integration Guide - Prime Studio

## 🔌 API Endpoints untuk Mobile

Backend API sudah **SIAP DIGUNAKAN** untuk mobile app (Flutter, React Native, atau native Android/iOS).

### Base URL
```
Development: http://localhost:8000/api
Production: https://your-domain.com/api
```

---

## 🔐 Authentication Flow

### 1. Register (Sign Up)

**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "081234567890",
  "address": "Jl. Contoh No. 123"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "customer",
      "is_active": true
    },
    "access_token": "1|xxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["Email sudah terdaftar"],
    "password": ["Password minimal 8 karakter"]
  }
}
```

### 2. Login

**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
  "email": "admin@photostudio.com",
  "password": "password123"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Administrator",
      "email": "admin@photostudio.com",
      "role": "admin",
      "is_active": true
    },
    "access_token": "2|xxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Email atau password salah"
}
```

### 3. Get User Profile

**Endpoint:** `GET /api/user`

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "081234567890",
    "address": "Jl. Contoh No. 123",
    "role": "customer",
    "is_active": true
  }
}
```

### 4. Logout

**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {access_token}
Accept: application/json
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

---

## 📦 Packages (Public Access)

### Get All Packages

**Endpoint:** `GET /api/packages`

**Query Parameters:**
- `active` (optional): true/false
- `search` (optional): search by name

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Basic Package",
      "slug": "basic-package",
      "description": "Paket foto studio dasar...",
      "price": 500000,
      "duration_hours": 2,
      "photo_count": 50,
      "edited_photo_count": 10,
      "include_makeup": false,
      "include_outfit": false,
      "features": [
        "2 jam sesi foto",
        "50 foto mentah",
        "10 foto hasil edit"
      ],
      "image": "packages/basic.jpg",
      "is_active": true,
      "formatted_price": "Rp 500.000"
    }
  ]
}
```

### Get Package Detail

**Endpoint:** `GET /api/packages/{id}`

---

## 📅 Schedules

### Get Available Schedules

**Endpoint:** `GET /api/schedules`

**Query Parameters:**
- `available`: true (hanya yang available)
- `date`: YYYY-MM-DD (specific date)
- `start_date`: YYYY-MM-DD
- `end_date`: YYYY-MM-DD

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "date": "2024-02-01",
      "start_time": "09:00:00",
      "end_time": "12:00:00",
      "status": "available",
      "formatted_date_time": "01/02/2024 09:00 - 12:00"
    }
  ]
}
```

### Check Availability

**Endpoint:** `GET /api/schedules/check-availability?date=2024-02-01`

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "date": "2024-02-01",
    "available_slots": 3,
    "schedules": []
  }
}
```

---

## 📝 Bookings (Require Auth)

### Create Booking

**Endpoint:** `POST /api/bookings`

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "package_id": 1,
  "schedule_id": 5,
  "customer_notes": "Saya ingin outdoor session"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Booking berhasil dibuat",
  "data": {
    "id": 1,
    "booking_code": "BKG-123456",
    "user_id": 2,
    "package_id": 1,
    "schedule_id": 5,
    "booking_date": "2024-02-01",
    "booking_time": "09:00:00",
    "total_price": 500000,
    "status": "pending",
    "customer_notes": "Saya ingin outdoor session",
    "formatted_price": "Rp 500.000",
    "status_label": "Menunggu Konfirmasi"
  }
}
```

### Get My Bookings

**Endpoint:** `GET /api/bookings`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `status`: pending/confirmed/paid/completed/cancelled

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "booking_code": "BKG-123456",
        "package": {
          "id": 1,
          "name": "Basic Package"
        },
        "schedule": {
          "date": "2024-02-01",
          "start_time": "09:00:00"
        },
        "status": "pending",
        "total_price": 500000
      }
    ],
    "current_page": 1,
    "total": 5
  }
}
```

### Get Booking Detail

**Endpoint:** `GET /api/bookings/{id}`

### Cancel Booking

**Endpoint:** `POST /api/bookings/{id}/cancel`

**Request Body:**
```json
{
  "cancellation_reason": "Berhalangan hadir"
}
```

---

## 💳 Payments

### Create Payment

**Endpoint:** `POST /api/payments`

**Headers:**
```
Authorization: Bearer {access_token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "booking_id": 1,
  "payment_method": "bank_transfer"
}
```

**Payment Methods:**
- `bank_transfer`
- `credit_card`
- `e_wallet`
- `qris`
- `cash` (admin only)

**Success Response (201):**
```json
{
  "success": true,
  "message": "Pembayaran berhasil dibuat",
  "data": {
    "id": 1,
    "payment_code": "PAY-123456",
    "booking_id": 1,
    "amount": 500000,
    "payment_method": "bank_transfer",
    "status": "pending",
    "payment_url": "http://localhost:8000/payment/1",
    "expired_at": "2024-02-02T10:00:00",
    "formatted_amount": "Rp 500.000"
  }
}
```

### Get Payment History

**Endpoint:** `GET /api/payments`

**Headers:**
```
Authorization: Bearer {access_token}
```

---

## 🖼️ Gallery (Public)

### Get All Galleries

**Endpoint:** `GET /api/galleries`

**Query Parameters:**
- `category`: wedding/prewedding/portrait/product/event/other
- `featured`: true/false
- `package_id`: filter by package

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "title": "Romantic Prewedding Session",
        "description": "Sesi foto prewedding romantis...",
        "image_path": "galleries/sample1.jpg",
        "thumbnail_path": "galleries/sample1_thumb.jpg",
        "category": "prewedding",
        "is_featured": true,
        "category_label": "Prewedding"
      }
    ]
  }
}
```

---

## ⭐ Reviews

### Get Reviews

**Endpoint:** `GET /api/reviews`

**Query Parameters:**
- `rating`: 1-5

### Create Review

**Endpoint:** `POST /api/reviews`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Request Body:**
```json
{
  "booking_id": 1,
  "rating": 5,
  "comment": "Excellent service!"
}
```

---

## 📊 Customer Dashboard

**Endpoint:** `GET /api/dashboard`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "overview": {
      "total_bookings": 5,
      "upcoming_bookings_count": 2,
      "pending_payments_count": 1,
      "can_be_reviewed_count": 1
    },
    "upcoming_bookings": [],
    "recent_bookings": [],
    "pending_payments": []
  }
}
```

---

## 🔧 Implementation Examples

### Flutter Example

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api';
  String? token;

  // Login
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    final data = jsonDecode(response.body);
    
    if (data['success']) {
      token = data['data']['access_token'];
      // Save token to SharedPreferences
    }
    
    return data;
  }

  // Get Packages
  Future<List<dynamic>> getPackages() async {
    final response = await http.get(
      Uri.parse('$baseUrl/packages'),
      headers: {'Accept': 'application/json'},
    );

    final data = jsonDecode(response.body);
    return data['data'];
  }

  // Create Booking
  Future<Map<String, dynamic>> createBooking(int packageId, int scheduleId, String? notes) async {
    final response = await http.post(
      Uri.parse('$baseUrl/bookings'),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({
        'package_id': packageId,
        'schedule_id': scheduleId,
        'customer_notes': notes,
      }),
    );

    return jsonDecode(response.body);
  }

  // Get My Bookings
  Future<List<dynamic>> getMyBookings() async {
    final response = await http.get(
      Uri.parse('$baseUrl/bookings'),
      headers: {
        'Accept': 'application/json',
        'Authorization': 'Bearer $token',
      },
    );

    final data = jsonDecode(response.body);
    return data['data']['data'];
  }
}
```

### React Native Example

```javascript
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const API_BASE_URL = 'http://localhost:8000/api';

// Setup axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add token to requests
api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Login
export const login = async (email, password) => {
  try {
    const response = await api.post('/login', { email, password });
    
    if (response.data.success) {
      const token = response.data.data.access_token;
      await AsyncStorage.setItem('auth_token', token);
      await AsyncStorage.setItem('user', JSON.stringify(response.data.data.user));
    }
    
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};

// Get Packages
export const getPackages = async () => {
  try {
    const response = await api.get('/packages');
    return response.data.data;
  } catch (error) {
    throw error.response.data;
  }
};

// Create Booking
export const createBooking = async (packageId, scheduleId, notes) => {
  try {
    const response = await api.post('/bookings', {
      package_id: packageId,
      schedule_id: scheduleId,
      customer_notes: notes,
    });
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};

// Get My Bookings
export const getMyBookings = async () => {
  try {
    const response = await api.get('/bookings');
    return response.data.data.data;
  } catch (error) {
    throw error.response.data;
  }
};
```

### Android (Kotlin) Example

```kotlin
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

// API Interface
interface ApiService {
    @POST("login")
    suspend fun login(@Body request: LoginRequest): ApiResponse<LoginData>

    @GET("packages")
    suspend fun getPackages(): ApiResponse<List<Package>>

    @POST("bookings")
    suspend fun createBooking(
        @Header("Authorization") token: String,
        @Body request: BookingRequest
    ): ApiResponse<Booking>

    @GET("bookings")
    suspend fun getMyBookings(
        @Header("Authorization") token: String
    ): ApiResponse<BookingListResponse>
}

// Retrofit Setup
object ApiClient {
    private const val BASE_URL = "http://localhost:8000/api/"

    val retrofit: Retrofit = Retrofit.Builder()
        .baseUrl(BASE_URL)
        .addConverterFactory(GsonConverterFactory.create())
        .build()

    val apiService: ApiService = retrofit.create(ApiService::class.java)
}

// Usage
class BookingRepository {
    private val api = ApiClient.apiService
    private val sharedPrefs = // your SharedPreferences

    suspend fun login(email: String, password: String): Result<LoginData> {
        return try {
            val response = api.login(LoginRequest(email, password))
            if (response.success) {
                // Save token
                sharedPrefs.edit()
                    .putString("auth_token", response.data.access_token)
                    .apply()
                Result.success(response.data)
            } else {
                Result.failure(Exception(response.message))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    suspend fun getPackages(): Result<List<Package>> {
        return try {
            val response = api.getPackages()
            if (response.success) {
                Result.success(response.data)
            } else {
                Result.failure(Exception(response.message))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }
}
```

---

## 🔐 Security Notes

1. **Token Storage**
   - Flutter: `SharedPreferences` atau `flutter_secure_storage`
   - React Native: `AsyncStorage` atau `@react-native-async-storage/encrypted-storage`
   - Android: `SharedPreferences` dengan encryption

2. **HTTPS in Production**
   - Gunakan HTTPS untuk production
   - Update base URL ke domain production

3. **Token Expiration**
   - Token tidak expire secara otomatis
   - Implement refresh token jika diperlukan
   - Handle 401 Unauthorized dengan logout

4. **Error Handling**
   - Selalu handle error response
   - Show user-friendly messages
   - Log errors untuk debugging

---

## 🧪 Testing API

### Postman Collection

Import file: `postman_collection.json`

**Environment Variables:**
- `base_url`: `http://localhost:8000/api`
- `token`: (set after login)

### Test Flow

1. **Login** → Get token
2. **Get Packages** → Choose package
3. **Get Schedules** → Check availability
4. **Create Booking** → Book a session
5. **Create Payment** → Pay for booking
6. **Get My Bookings** → View bookings

---

## 📞 Support

**Backend API sudah READY!** Database sama untuk web & mobile.

- Web Dashboard: `http://localhost:8000`
- API Endpoint: `http://localhost:8000/api`
- API Documentation: `API_DOCUMENTATION.md`

**Test Credentials:**
- Admin: `admin@photostudio.com` / `password123`
- Customer: `budi@example.com` / `password123`

---

Happy Coding! 🚀
