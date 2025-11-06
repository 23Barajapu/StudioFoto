import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile/models/package.dart';
import 'package:mobile/models/category.dart';
import 'package:shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'http://localhost:8000/api'; // Ganti dengan URL backend Anda
  static const String apiKey = 'your-api-key'; // Jika diperlukan

  // Get auth token from shared preferences
  static Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  // Get headers with authorization
  static Future<Map<String, String>> _getHeaders() async {
    final token = await _getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    };
  }

  // Fetch all categories
  static Future<List<Category>> getCategories() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/categories'),
        headers: await _getHeaders(),
      );

      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body)['data'];
        return data.map((json) => Category.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load categories: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching categories: $e');
      rethrow;
    }
  }

  // Fetch all packages
  static Future<List<Package>> getPackages({int? categoryId}) async {
    try {
      Uri url = Uri.parse('$baseUrl/packages');
      if (categoryId != null) {
        url = Uri.parse('$baseUrl/categories/$categoryId/packages');
      }

      final response = await http.get(
        url,
        headers: await _getHeaders(),
      );

      if (response.statusCode == 200) {
        final List<dynamic> data = json.decode(response.body)['data'];
        return data.map((json) => Package.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load packages: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching packages: $e');
      rethrow;
    }
  }
}
