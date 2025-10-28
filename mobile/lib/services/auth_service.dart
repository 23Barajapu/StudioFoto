import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  // ⚠️ PENTING! PILIH SESUAI PLATFORM YANG ANDA GUNAKAN:
  // 
  // 🌐 Flutter Web (Chrome/Browser) → Gunakan: http://localhost:8000/api
  // 📱 Android Emulator → Gunakan: http://10.0.2.2:8000/api
  // 🍎 iOS Simulator → Gunakan: http://127.0.0.1:8000/api atau http://localhost:8000/api
  // 📲 Real Device (HP Fisik) → Gunakan: http://IP_KOMPUTER:8000/api
  //    Cara cek IP: Buka CMD, ketik: ipconfig, cari IPv4 Address
  
  // 🎯 AKTIF SEKARANG: Flutter Web (Chrome)
  static const String baseUrl = 'http://localhost:8000/api';
  
  // Jika pakai Android Emulator, uncomment line di bawah:
  // static const String baseUrl = 'http://10.0.2.2:8000/api';
  
  // Jika pakai iOS Simulator, uncomment line di bawah:
  // static const String baseUrl = 'http://127.0.0.1:8000/api';
  
  // Jika pakai Real Device, uncomment dan ganti IP:
  // static const String baseUrl = 'http://192.168.1.xxx:8000/api';
  
  // Untuk testing, gunakan IP ini dulu
  // Nanti ganti dengan IP komputer Anda
  
  String? _token;
  Map<String, dynamic>? _user;

  // Getter
  String? get token => _token;
  Map<String, dynamic>? get user => _user;
  bool get isLoggedIn => _token != null;

  // Login
  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['success'] == true) {
        _token = data['data']['access_token'];
        _user = data['data']['user'];

        // Save to SharedPreferences
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', _token!);
        await prefs.setString('user', jsonEncode(_user));

        return {
          'success': true,
          'message': data['message'] ?? 'Login berhasil',
          'user': _user,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Login gagal',
        };
      }
    } catch (e) {
      print('Login error: $e');
      return {
        'success': false,
        'message': 'Terjadi kesalahan. Pastikan backend berjalan di: $baseUrl',
      };
    }
  }

  // Register
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
    String? address,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/register'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': passwordConfirmation,
          'phone': phone,
          'address': address,
        }),
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 201 && data['success'] == true) {
        _token = data['data']['access_token'];
        _user = data['data']['user'];

        // Save to SharedPreferences
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('auth_token', _token!);
        await prefs.setString('user', jsonEncode(_user));

        return {
          'success': true,
          'message': data['message'] ?? 'Registrasi berhasil',
          'user': _user,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Registrasi gagal',
          'errors': data['errors'],
        };
      }
    } catch (e) {
      print('Register error: $e');
      return {
        'success': false,
        'message': 'Terjadi kesalahan. Pastikan backend berjalan.',
      };
    }
  }

  // Logout
  Future<void> logout() async {
    if (_token != null) {
      try {
        await http.post(
          Uri.parse('$baseUrl/logout'),
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer $_token',
          },
        );
      } catch (e) {
        print('Logout error: $e');
      }
    }

    // Clear local data
    _token = null;
    _user = null;

    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    await prefs.remove('user');
  }

  // Load saved token
  Future<bool> loadSavedToken() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      _token = prefs.getString('auth_token');
      final userString = prefs.getString('user');

      if (_token != null && userString != null) {
        _user = jsonDecode(userString);
        return true;
      }
      return false;
    } catch (e) {
      print('Load token error: $e');
      return false;
    }
  }

  // Get user profile
  Future<Map<String, dynamic>> getUserProfile() async {
    if (_token == null) {
      return {
        'success': false,
        'message': 'Token tidak ditemukan',
      };
    }

    try {
      final response = await http.get(
        Uri.parse('$baseUrl/user'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer $_token',
        },
      );

      final data = jsonDecode(response.body);

      if (response.statusCode == 200 && data['success'] == true) {
        _user = data['data'];

        // Update SharedPreferences
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('user', jsonEncode(_user));

        return {
          'success': true,
          'user': _user,
        };
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengambil profile',
        };
      }
    } catch (e) {
      print('Get profile error: $e');
      return {
        'success': false,
        'message': 'Terjadi kesalahan',
      };
    }
  }

  // Helper untuk mendapatkan headers dengan auth
  Map<String, String> getAuthHeaders() {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $_token',
    };
  }
}
