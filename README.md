# Prime Foto Studio - Sistem Informasi Pemesanan dan Manajemen Layanan

Sistem informasi pemesanan dan manajemen layanan untuk Prime Foto Studio yang dibangun menggunakan Flutter dan Dart. Aplikasi ini memudahkan pelanggan dalam melakukan booking jadwal foto, membantu pencatatan data pemesanan secara terpusat, serta menampilkan informasi umum usaha, paket layanan, dan portofolio hasil foto.

## 🚀 Fitur Utama

### Untuk Pelanggan
- **Beranda**: Informasi umum studio, preview layanan, dan portfolio
- **Layanan**: Daftar lengkap paket fotografi dengan detail harga dan fitur
- **Booking**: Sistem pemesanan dengan kalender dan pilihan jam
- **Portfolio**: Galeri hasil foto dengan filter kategori
- **Profil**: Manajemen data pribadi dan riwayat booking
- **Autentikasi**: Login dan registrasi akun pelanggan

### Untuk Admin
- **Dashboard**: Overview statistik dan data penting
- **Kelola Booking**: Manajemen semua pemesanan dengan filter status
- **Kelola Layanan**: CRUD layanan fotografi
- **Kelola Portfolio**: Upload dan manajemen galeri foto
- **Laporan**: Statistik pendapatan dan aktivitas

## 🛠️ Teknologi yang Digunakan

- **Flutter**: Framework UI cross-platform
- **Dart**: Bahasa pemrograman
- **Provider**: State management
- **Go Router**: Navigation
- **HTTP**: API communication
- **Shared Preferences**: Local storage
- **Table Calendar**: Calendar widget
- **Google Fonts**: Typography

## 📱 Screenshots

### Beranda
- Hero section dengan informasi studio
- Preview layanan populer
- Galeri portfolio
- Informasi kontak

### Booking
- Kalender interaktif untuk pilih tanggal
- Pilihan jam tersedia
- Form data pelanggan
- Konfirmasi booking

### Admin Panel
- Dashboard dengan statistik
- Kelola booking dengan status
- Manajemen layanan
- Upload portfolio

## 🚀 Instalasi dan Setup

### Prerequisites
- Flutter SDK (>=3.0.0)
- Dart SDK
- Android Studio / VS Code
- Git

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/yourusername/prime-foto-studio.git
   cd prime-foto-studio
   ```

2. **Install Dependencies**
   ```bash
   flutter pub get
   ```

3. **Setup Environment**
   - Buat file `.env` di root project
   - Isi dengan konfigurasi API backend:
   ```env
   API_BASE_URL=https://api.primefotostudio.com
   API_VERSION=/api/v1
   ```

4. **Run Aplikasi**
   ```bash
   flutter run
   ```

## 📁 Struktur Project

```
lib/
├── config/
│   ├── app_router.dart      # Routing configuration
│   └── app_theme.dart       # Theme configuration
├── models/
│   ├── user_model.dart      # User data model
│   ├── service_model.dart   # Service data model
│   ├── booking_model.dart   # Booking data model
│   └── portfolio_model.dart # Portfolio data model
├── providers/
│   ├── auth_provider.dart      # Authentication state
│   ├── booking_provider.dart   # Booking management
│   ├── service_provider.dart   # Service management
│   └── portfolio_provider.dart # Portfolio management
├── screens/
│   ├── home/
│   │   └── home_screen.dart
│   ├── auth/
│   │   ├── login_screen.dart
│   │   └── register_screen.dart
│   ├── services/
│   │   └── services_screen.dart
│   ├── booking/
│   │   ├── booking_screen.dart
│   │   └── booking_detail_screen.dart
│   ├── portfolio/
│   │   └── portfolio_screen.dart
│   ├── profile/
│   │   └── profile_screen.dart
│   └── admin/
│       ├── admin_dashboard_screen.dart
│       ├── admin_bookings_screen.dart
│       ├── admin_services_screen.dart
│       └── admin_portfolio_screen.dart
├── widgets/
│   ├── common/              # Reusable widgets
│   ├── home/               # Home screen widgets
│   ├── services/           # Service related widgets
│   ├── booking/            # Booking related widgets
│   ├── portfolio/          # Portfolio related widgets
│   ├── profile/            # Profile related widgets
│   └── admin/              # Admin panel widgets
├── services/
│   └── api_service.dart    # API communication
└── main.dart               # App entry point
```

## 🔧 Konfigurasi

### API Backend
Aplikasi ini memerlukan backend API untuk berfungsi penuh. Pastikan backend menyediakan endpoint berikut:

- `POST /api/v1/auth/login` - Login user
- `POST /api/v1/auth/register` - Registrasi user
- `GET /api/v1/services` - Daftar layanan
- `GET /api/v1/bookings` - Daftar booking
- `POST /api/v1/bookings` - Buat booking baru
- `GET /api/v1/portfolios` - Daftar portfolio
- Dan endpoint lainnya sesuai kebutuhan

### Database
Backend harus memiliki tabel:
- `users` - Data pengguna
- `services` - Data layanan
- `bookings` - Data pemesanan
- `portfolios` - Data portfolio

## 🎨 Customization

### Theme
Edit file `lib/config/app_theme.dart` untuk mengubah warna, font, dan styling aplikasi.

### API Configuration
Ubah URL API di `lib/services/api_service.dart` sesuai dengan backend Anda.

## 📱 Build untuk Production

### Android
```bash
flutter build apk --release
```

### iOS
```bash
flutter build ios --release
```

### Web
```bash
flutter build web --release
```

## 🤝 Kontribusi

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Kontak

- **Email**: info@primefotostudio.com
- **Phone**: +62 812-3456-7890
- **Website**: https://primefotostudio.com

## 🙏 Acknowledgments

- Flutter team untuk framework yang luar biasa
- Provider package untuk state management
- Komunitas Flutter Indonesia
- Semua kontributor yang telah membantu pengembangan

---

**Prime Foto Studio** - Momen Terbaik Anda, Karya Terbaik Kami 📸
