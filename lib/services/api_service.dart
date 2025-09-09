import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'https://api.primefotostudio.com'; // Ganti dengan URL backend Anda
  static const String apiVersion = '/api/v1';
  
  String get baseApiUrl => '$baseUrl$apiVersion';

  // Headers
  Map<String, String> get _headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  Map<String, String> _getAuthHeaders(String? token) => {
    ..._headers,
    if (token != null) 'Authorization': 'Bearer $token',
  };

  // Auth Endpoints
  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseApiUrl/auth/login'),
        headers: _headers,
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        await saveToken(data['data']['token']);
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Login gagal',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> register(String name, String email, String phone, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseApiUrl/auth/register'),
        headers: _headers,
        body: jsonEncode({
          'name': name,
          'email': email,
          'phone': phone,
          'password': password,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 201) {
        await saveToken(data['data']['token']);
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Registrasi gagal',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> updateProfile(String userId, String name, String phone, String? profileImage) async {
    try {
      final token = await getToken();
      final response = await http.put(
        Uri.parse('$baseApiUrl/users/$userId'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'name': name,
          'phone': phone,
          'profile_image': profileImage,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Update profil gagal',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  // Booking Endpoints
  Future<Map<String, dynamic>> getBookings() async {
    try {
      final token = await getToken();
      final response = await http.get(
        Uri.parse('$baseApiUrl/bookings'),
        headers: _getAuthHeaders(token),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengambil data booking',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> createBooking({
    required String serviceId,
    required DateTime scheduledDate,
    required String timeSlot,
    String notes = '',
  }) async {
    try {
      final token = await getToken();
      final response = await http.post(
        Uri.parse('$baseApiUrl/bookings'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'service_id': serviceId,
          'scheduled_date': scheduledDate.toIso8601String(),
          'time_slot': timeSlot,
          'notes': notes,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 201) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal membuat booking',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> updateBookingStatus(String bookingId, String status) async {
    try {
      final token = await getToken();
      final response = await http.put(
        Uri.parse('$baseApiUrl/bookings/$bookingId/status'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'status': status,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal update status booking',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> rescheduleBooking(String bookingId, DateTime newDate, String newTimeSlot) async {
    try {
      final token = await getToken();
      final response = await http.put(
        Uri.parse('$baseApiUrl/bookings/$bookingId/reschedule'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'scheduled_date': newDate.toIso8601String(),
          'time_slot': newTimeSlot,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal reschedule booking',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  // Service Endpoints
  Future<Map<String, dynamic>> getServices() async {
    try {
      final response = await http.get(
        Uri.parse('$baseApiUrl/services'),
        headers: _headers,
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengambil data layanan',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> createService({
    required String name,
    required String description,
    required double price,
    required int duration,
    required List<String> features,
    String? imageUrl,
  }) async {
    try {
      final token = await getToken();
      final response = await http.post(
        Uri.parse('$baseApiUrl/services'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'name': name,
          'description': description,
          'price': price,
          'duration': duration,
          'features': features,
          'image_url': imageUrl,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 201) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal membuat layanan',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> updateService({
    required String serviceId,
    required String name,
    required String description,
    required double price,
    required int duration,
    required List<String> features,
    String? imageUrl,
    bool? isActive,
  }) async {
    try {
      final token = await getToken();
      final response = await http.put(
        Uri.parse('$baseApiUrl/services/$serviceId'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'name': name,
          'description': description,
          'price': price,
          'duration': duration,
          'features': features,
          'image_url': imageUrl,
          'is_active': isActive,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal update layanan',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> deleteService(String serviceId) async {
    try {
      final token = await getToken();
      final response = await http.delete(
        Uri.parse('$baseApiUrl/services/$serviceId'),
        headers: _getAuthHeaders(token),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal hapus layanan',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  // Portfolio Endpoints
  Future<Map<String, dynamic>> getPortfolios() async {
    try {
      final response = await http.get(
        Uri.parse('$baseApiUrl/portfolios'),
        headers: _headers,
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal mengambil data portfolio',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> createPortfolio({
    required String title,
    required String description,
    required List<String> imageUrls,
    required String category,
  }) async {
    try {
      final token = await getToken();
      final response = await http.post(
        Uri.parse('$baseApiUrl/portfolios'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'title': title,
          'description': description,
          'image_urls': imageUrls,
          'category': category,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 201) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal membuat portfolio',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> updatePortfolio({
    required String portfolioId,
    required String title,
    required String description,
    required List<String> imageUrls,
    required String category,
  }) async {
    try {
      final token = await getToken();
      final response = await http.put(
        Uri.parse('$baseApiUrl/portfolios/$portfolioId'),
        headers: _getAuthHeaders(token),
        body: jsonEncode({
          'title': title,
          'description': description,
          'image_urls': imageUrls,
          'category': category,
        }),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal update portfolio',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  Future<Map<String, dynamic>> deletePortfolio(String portfolioId) async {
    try {
      final token = await getToken();
      final response = await http.delete(
        Uri.parse('$baseApiUrl/portfolios/$portfolioId'),
        headers: _getAuthHeaders(token),
      );

      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200) {
        return data;
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Gagal hapus portfolio',
        };
      }
    } catch (e) {
      return {
        'success': false,
        'message': 'Terjadi kesalahan: $e',
      };
    }
  }

  // Token Management
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  Future<void> saveUserToStorage(Map<String, dynamic> userData) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('user_data', jsonEncode(userData));
  }

  Future<Map<String, dynamic>?> getStoredUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userData = prefs.getString('user_data');
    if (userData != null) {
      return jsonDecode(userData);
    }
    return null;
  }

  Future<void> clearUserFromStorage() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('user_data');
    await prefs.remove('auth_token');
  }
}
