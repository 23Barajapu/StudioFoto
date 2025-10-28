# API Testing Guide - Prime Studio

Backend sudah running di: `http://localhost:8000`

## 🧪 Test Semua API Endpoints

### ✅ 1. Health Check
```bash
curl http://localhost:8000/api/health
```

### ✅ 2. Register (POST)
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"name\":\"Test User\",\"email\":\"test@example.com\",\"password\":\"password123\",\"password_confirmation\":\"password123\"}"
```

**Test dari Browser Console:**
```javascript
fetch('http://localhost:8000/api/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    name: 'Test User',
    email: 'test@example.com',
    password: 'password123',
    password_confirmation: 'password123'
  })
})
.then(r => r.json())
.then(console.log);
```

### ✅ 3. Login (POST)
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"email\":\"admin@photostudio.com\",\"password\":\"password123\"}"
```

**Test dari Browser:**
```javascript
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
.then(data => {
  console.log(data);
  // Save token
  localStorage.setItem('token', data.data.access_token);
});
```

### ✅ 4. Get User Profile (GET) - Need Auth
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Test dari Browser:**
```javascript
const token = localStorage.getItem('token');
fetch('http://localhost:8000/api/user', {
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  }
})
.then(r => r.json())
.then(console.log);
```

### ✅ 5. Get Packages (GET)
```bash
curl http://localhost:8000/api/packages
```

**Test dari Browser:**
```javascript
fetch('http://localhost:8000/api/packages')
  .then(r => r.json())
  .then(console.log);
```

### ✅ 6. Get Package Detail (GET)
```bash
curl http://localhost:8000/api/packages/1
```

### ✅ 7. Get Schedules (GET)
```bash
curl http://localhost:8000/api/schedules
```

**Available schedules only:**
```bash
curl "http://localhost:8000/api/schedules?available=true"
```

### ✅ 8. Get Galleries (GET)
```bash
curl http://localhost:8000/api/galleries
```

### ✅ 9. Create Booking (POST) - Need Auth
```bash
curl -X POST http://localhost:8000/api/bookings \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d "{\"package_id\":1,\"schedule_id\":1,\"customer_notes\":\"Test booking\"}"
```

**Test dari Browser:**
```javascript
const token = localStorage.getItem('token');
fetch('http://localhost:8000/api/bookings', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({
    package_id: 1,
    schedule_id: 1,
    customer_notes: 'Test booking'
  })
})
.then(r => r.json())
.then(console.log);
```

### ✅ 10. Get My Bookings (GET) - Need Auth
```bash
curl -X GET http://localhost:8000/api/bookings \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### ✅ 11. Create Payment (POST) - Need Auth
```bash
curl -X POST http://localhost:8000/api/payments \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d "{\"booking_id\":1,\"payment_method\":\"bank_transfer\",\"amount\":500000}"
```

### ✅ 12. Get Dashboard Stats (GET) - Need Auth
```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### ✅ 13. Logout (POST) - Need Auth
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🌐 Test Langsung di Browser

Buka **Chrome DevTools** (F12), masuk ke **Console**, lalu jalankan:

### 1. Test Health
```javascript
fetch('http://localhost:8000/api/health')
  .then(r => r.json())
  .then(console.log);
```

### 2. Test Register
```javascript
fetch('http://localhost:8000/api/register', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    name: 'Test User',
    email: 'testuser' + Date.now() + '@example.com',
    password: 'password123',
    password_confirmation: 'password123'
  })
})
.then(r => r.json())
.then(data => {
  console.log('Register Response:', data);
  if(data.success) {
    localStorage.setItem('token', data.data.access_token);
    console.log('Token saved!');
  }
});
```

### 3. Test Login
```javascript
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
.then(data => {
  console.log('Login Response:', data);
  if(data.success) {
    localStorage.setItem('token', data.data.access_token);
    console.log('Token saved!');
  }
});
```

### 4. Test Get Packages
```javascript
fetch('http://localhost:8000/api/packages')
  .then(r => r.json())
  .then(data => {
    console.log('Packages:', data);
  });
```

### 5. Test Get Profile (Need login first)
```javascript
const token = localStorage.getItem('token');
fetch('http://localhost:8000/api/user', {
  headers: {
    'Accept': 'application/json',
    'Authorization': `Bearer ${token}`
  }
})
.then(r => r.json())
.then(data => {
  console.log('User Profile:', data);
});
```

---

## 📱 Test dari Mobile App

Pastikan:
1. Backend running: `php artisan serve`
2. IP sudah benar di `auth_service.dart`:
   - Android Emulator: `http://10.0.2.2:8000/api`
   - iOS Simulator: `http://localhost:8000/api`
   - Real Device: `http://IP_KOMPUTER:8000/api`

3. Hot restart app: Tekan `R` di terminal Flutter

4. Test Register/Login dari app

---

## 🔑 Default Test Credentials

**Admin:**
- Email: `admin@photostudio.com`
- Password: `password123`

**Customer:**
- Email: `budi@example.com`
- Password: `password123`

---

## ✅ API Endpoints Status

| Endpoint | Method | Auth | Status |
|----------|--------|------|--------|
| `/api/health` | GET | ❌ | ✅ Ready |
| `/api/register` | POST | ❌ | ✅ Ready |
| `/api/login` | POST | ❌ | ✅ Ready |
| `/api/logout` | POST | ✅ | ✅ Ready |
| `/api/user` | GET | ✅ | ✅ Ready |
| `/api/packages` | GET | ❌ | ✅ Ready |
| `/api/packages/{id}` | GET | ❌ | ✅ Ready |
| `/api/schedules` | GET | ❌ | ✅ Ready |
| `/api/galleries` | GET | ❌ | ✅ Ready |
| `/api/bookings` | POST | ✅ | ✅ Ready |
| `/api/bookings` | GET | ✅ | ✅ Ready |
| `/api/payments` | POST | ✅ | ✅ Ready |
| `/api/dashboard` | GET | ✅ | ✅ Ready |

✅ = Require Auth Token
❌ = Public Endpoint

---

## 🐛 Troubleshooting

### Error: Connection Refused
**Solution:** Pastikan backend running:
```bash
cd c:\laragon\www\MuaraV2\backend
php artisan serve
```

### Error: 404 Not Found
**Solution:** Check route di `routes/api.php`

### Error: 401 Unauthorized
**Solution:** Login dulu dan gunakan token yang valid

### Error: 422 Validation Failed
**Solution:** Check request body dan pastikan semua required field terisi

---

**Semua API sudah READY untuk digunakan!** ✅

Test dengan browser console atau dari mobile app langsung.
