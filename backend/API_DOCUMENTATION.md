# Photo Studio API Documentation

Base URL: `http://localhost:8000/api`

## Authentication

API menggunakan Laravel Sanctum untuk autentikasi berbasis token.

### Headers
Untuk endpoint yang memerlukan autentikasi:
```
Authorization: Bearer {your_token}
Content-Type: application/json
Accept: application/json
```

---

## 1. Authentication Endpoints

### 1.1 Register
**POST** `/register`

Register pengguna baru sebagai customer.

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

**Response (201):**
```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "customer"
    },
    "access_token": "1|xxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

### 1.2 Login
**POST** `/login`

Login untuk mendapatkan token akses.

**Request Body:**
```json
{
  "email": "admin@photostudio.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Administrator",
      "email": "admin@photostudio.com",
      "role": "admin"
    },
    "access_token": "1|xxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

### 1.3 Logout
**POST** `/logout`

Logout dan hapus token.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

### 1.4 Get User Profile
**GET** `/user`

Mendapatkan informasi user yang sedang login.

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
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

### 1.5 Update Profile
**PUT** `/user/profile`

Update profil user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "John Doe Updated",
  "phone": "081234567890",
  "address": "Jl. Baru No. 456"
}
```

### 1.6 Change Password
**PUT** `/user/change-password`

Ubah password user.

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "current_password": "oldpassword",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

---

## 2. Package Endpoints

### 2.1 Get All Packages
**GET** `/packages`

Mendapatkan daftar semua paket.

**Query Parameters:**
- `active` (boolean): Filter hanya paket aktif
- `search` (string): Search by name/description

**Response (200):**
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
      "features": ["2 jam sesi foto", "50 foto mentah"],
      "image": "packages/basic.jpg",
      "is_active": true,
      "formatted_price": "Rp 500.000"
    }
  ]
}
```

### 2.2 Get Package Detail
**GET** `/packages/{id}`

Mendapatkan detail paket beserta galeri.

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Basic Package",
    "price": 500000,
    "galleries": []
  }
}
```

### 2.3 Create Package (Admin)
**POST** `/admin/packages`

**Headers:** `Authorization: Bearer {admin_token}`

**Request Body:** (multipart/form-data)
```json
{
  "name": "New Package",
  "description": "Description here",
  "price": 1000000,
  "duration_hours": 3,
  "photo_count": 100,
  "edited_photo_count": 25,
  "include_makeup": true,
  "include_outfit": false,
  "features": ["Feature 1", "Feature 2"],
  "image": "(file upload)",
  "is_active": true,
  "sort_order": 1
}
```

### 2.4 Update Package (Admin)
**PUT** `/admin/packages/{id}`

**Headers:** `Authorization: Bearer {admin_token}`

### 2.5 Delete Package (Admin)
**DELETE** `/admin/packages/{id}`

**Headers:** `Authorization: Bearer {admin_token}`

---

## 3. Schedule Endpoints

### 3.1 Get Available Schedules
**GET** `/schedules`

**Query Parameters:**
- `available` (boolean): Filter only available schedules
- `start_date` (date): Start date filter
- `end_date` (date): End date filter
- `date` (date): Specific date

**Response (200):**
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

### 3.2 Check Availability
**GET** `/schedules/check-availability`

**Query Parameters:**
- `date` (required): Date to check (YYYY-MM-DD)

**Response (200):**
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

### 3.3 Create Schedule (Admin)
**POST** `/admin/schedules`

**Headers:** `Authorization: Bearer {admin_token}`

**Request Body:**
```json
{
  "date": "2024-02-01",
  "start_time": "09:00",
  "end_time": "12:00",
  "notes": "Optional notes"
}
```

### 3.4 Generate Schedules (Admin)
**POST** `/admin/schedules/generate`

Membuat jadwal untuk rentang tanggal.

**Headers:** `Authorization: Bearer {admin_token}`

**Request Body:**
```json
{
  "start_date": "2024-02-01",
  "end_date": "2024-02-28",
  "time_slots": [
    {
      "start_time": "09:00",
      "end_time": "12:00"
    },
    {
      "start_time": "13:00",
      "end_time": "16:00"
    }
  ]
}
```

---

## 4. Booking Endpoints

### 4.1 Get Bookings
**GET** `/bookings`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status` (string): Filter by status
- `start_date` (date): Start date
- `end_date` (date): End date
- `search` (string): Search by booking code

**Response (200):**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "booking_code": "BKG-123456",
        "user": {},
        "package": {},
        "schedule": {},
        "booking_date": "2024-02-01",
        "booking_time": "09:00:00",
        "total_price": 500000,
        "status": "pending",
        "formatted_price": "Rp 500.000",
        "status_label": "Menunggu Konfirmasi"
      }
    ],
    "current_page": 1,
    "total": 10
  }
}
```

### 4.2 Create Booking
**POST** `/bookings`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "package_id": 1,
  "schedule_id": 5,
  "customer_notes": "Optional notes"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Booking berhasil dibuat",
  "data": {
    "id": 1,
    "booking_code": "BKG-123456",
    "status": "pending"
  }
}
```

### 4.3 Get Booking Detail
**GET** `/bookings/{id}`

**Headers:** `Authorization: Bearer {token}`

### 4.4 Update Booking
**PUT** `/bookings/{id}`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "status": "confirmed",
  "admin_notes": "Confirmed by admin",
  "customer_notes": "Updated customer notes"
}
```

### 4.5 Cancel Booking
**POST** `/bookings/{id}/cancel`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "cancellation_reason": "Reason for cancellation"
}
```

### 4.6 Get Upcoming Bookings
**GET** `/bookings/upcoming`

**Headers:** `Authorization: Bearer {token}`

---

## 5. Payment Endpoints

### 5.1 Create Payment
**POST** `/payments`

**Headers:** `Authorization: Bearer {token}`

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
- `cash`

**Response (201):**
```json
{
  "success": true,
  "message": "Pembayaran berhasil dibuat",
  "data": {
    "id": 1,
    "payment_code": "PAY-123456",
    "amount": 500000,
    "payment_method": "bank_transfer",
    "status": "pending",
    "payment_url": "http://localhost:8000/payment/1",
    "expired_at": "2024-02-02T10:00:00"
  }
}
```

### 5.2 Get Payment Detail
**GET** `/payments/{id}`

**Headers:** `Authorization: Bearer {token}`

### 5.3 Get Payment History
**GET** `/payments`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `status` (string): Filter by status

### 5.4 Update Payment Status (Admin)
**PUT** `/admin/payments/{id}/status`

**Headers:** `Authorization: Bearer {admin_token}`

**Request Body:**
```json
{
  "status": "success",
  "transaction_id": "TRX-123456",
  "payment_details": {},
  "notes": "Payment confirmed"
}
```

---

## 6. Gallery Endpoints

### 6.1 Get Galleries
**GET** `/galleries`

**Query Parameters:**
- `category` (string): Filter by category
- `featured` (boolean): Only featured galleries
- `active` (boolean): Only active galleries
- `package_id` (integer): Filter by package

**Response (200):**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "title": "Romantic Prewedding Session",
        "description": "Description",
        "image_path": "galleries/sample1.jpg",
        "category": "prewedding",
        "is_featured": true,
        "category_label": "Prewedding"
      }
    ]
  }
}
```

### 6.2 Get Gallery Categories
**GET** `/galleries/categories/list`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {"value": "wedding", "label": "Pernikahan"},
    {"value": "prewedding", "label": "Prewedding"}
  ]
}
```

### 6.3 Create Gallery (Admin)
**POST** `/admin/galleries`

**Headers:** `Authorization: Bearer {admin_token}`

**Request Body:** (multipart/form-data)
```json
{
  "title": "Gallery Title",
  "description": "Description",
  "image": "(file upload)",
  "package_id": 1,
  "category": "wedding",
  "is_featured": true,
  "is_active": true,
  "sort_order": 1
}
```

---

## 7. Review Endpoints

### 7.1 Get Reviews
**GET** `/reviews`

**Query Parameters:**
- `rating` (integer): Filter by rating (1-5)
- `approved` (boolean): Filter by approval status (admin only)

### 7.2 Create Review
**POST** `/reviews`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "booking_id": 1,
  "rating": 5,
  "comment": "Excellent service!"
}
```

### 7.3 Approve Review (Admin)
**POST** `/admin/reviews/{id}/approve`

**Headers:** `Authorization: Bearer {admin_token}`

### 7.4 Reject Review (Admin)
**POST** `/admin/reviews/{id}/reject`

**Headers:** `Authorization: Bearer {admin_token}`

### 7.5 Get Average Rating
**GET** `/reviews/average-rating`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "average_rating": 4.5,
    "total_reviews": 25,
    "distribution": [
      {"rating": 5, "count": 15},
      {"rating": 4, "count": 7}
    ]
  }
}
```

---

## 8. Dashboard Endpoints

### 8.1 Customer Dashboard
**GET** `/dashboard`

**Headers:** `Authorization: Bearer {token}`

**Response (200):**
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
    "pending_payments": [],
    "can_be_reviewed": []
  }
}
```

### 8.2 Admin Dashboard
**GET** `/admin/dashboard`

**Headers:** `Authorization: Bearer {admin_token}`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "overview": {
      "total_bookings": 100,
      "total_customers": 50,
      "total_revenue": 50000000,
      "average_rating": 4.5,
      "this_month_bookings": 20,
      "this_month_revenue": 10000000,
      "pending_bookings": 5,
      "upcoming_bookings": 8
    },
    "recent_bookings": [],
    "popular_packages": [],
    "monthly_revenue": [],
    "booking_status_distribution": []
  }
}
```

### 8.3 Revenue Report (Admin)
**GET** `/admin/revenue-report`

**Headers:** `Authorization: Bearer {admin_token}`

**Query Parameters:**
- `start_date` (date): Start date
- `end_date` (date): End date

---

## Error Responses

### Validation Error (422)
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

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated. Silakan login terlebih dahulu."
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Akses ditolak. Hanya admin yang dapat mengakses."
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Resource tidak ditemukan"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Terjadi kesalahan pada server"
}
```

---

## Testing dengan Postman

1. Import environment variables:
   - `base_url`: `http://localhost:8000/api`
   - `token`: (will be set after login)

2. Login sebagai admin:
   - Email: `admin@photostudio.com`
   - Password: `password123`

3. Login sebagai customer:
   - Email: `budi@example.com`
   - Password: `password123`

4. Gunakan token dari response login untuk mengakses protected endpoints.
