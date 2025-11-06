import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile/models/package.dart';
import 'package:mobile/models/category.dart';
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  // Use the full URL to your Laravel application
  static const String baseUrl = 'http://127.0.0.1:8000';
  static const String apiKey = ''; // Add your API key if required
  
  // For debugging
  static void _logRequest(String method, String url, [Map<String, dynamic>? body]) {
    print('\n--- API Request ---');
    print('$method $url');
    if (body != null) print('Body: $body');
    print('------------------\n');
  }

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
      // Try the web route first
      final url = '$baseUrl/categories';
      _logRequest('GET', url);
      
      final response = await http.get(
        Uri.parse(url),
        headers: await _getHeaders(),
      );
      
      // If web route fails, try the API route
      if (response.statusCode == 404) {
        final apiUrl = '$baseUrl/api/galleries/categories/list';
        _logRequest('GET', apiUrl);
        
        final apiResponse = await http.get(
          Uri.parse(apiUrl),
          headers: await _getHeaders(),
        );
        return _parseCategoriesResponse(apiResponse);
      }
      
      return _parseCategoriesResponse(response);

      print('Categories response status: ${response.statusCode}');
      print('Categories response body: ${response.body}');

      if (response.statusCode == 200) {
        // Since this is a web route, it might return HTML instead of JSON
        // We need to handle the response accordingly
        final responseData = json.decode(response.body);
        
        // If the response is a Map with a 'data' key, use that
        // Otherwise, try to parse the response directly as a list
        final List<dynamic> data = responseData is Map && responseData.containsKey('data')
            ? responseData['data'] is List 
                ? responseData['data'] 
                : [responseData['data']]
            : (responseData is List ? responseData : []);
            
        if (data.isEmpty) {
          print('No categories found in response');
          return [];
        }
        
        return data.map<Category>((json) {
          try {
            return Category.fromJson(json);
          } catch (e) {
            print('Error parsing category: $e\nJSON: $json');
            rethrow;
          }
        }).toList();
      } else {
        throw Exception('Failed to load categories: ${response.statusCode}');
      }
    } catch (e) {
      print('Error fetching categories: $e');
      rethrow;
    }
  }

  // Helper method to parse categories response
  static List<Category> _parseCategoriesResponse(http.Response response) {
    print('\n--- Response (${response.statusCode}) ---');
    print('URL: ${response.request?.url}');
    print('Status: ${response.statusCode}');
    print('Headers: ${response.headers}');
    print('Body: ${response.body}');
    
    if (response.statusCode == 200) {
      try {
        final responseData = json.decode(response.body);
        
        // Handle different response formats
        if (responseData is Map<String, dynamic> && responseData.containsKey('data')) {
          final data = responseData['data'];
          if (data is List) {
            return data.map<Category>((json) => Category.fromJson(json as Map<String, dynamic>)).toList();
          } else if (data is Map<String, dynamic>) {
            return [Category.fromJson(data)];
          }
        } else if (responseData is List) {
          return responseData.map<Category>((json) => Category.fromJson(json as Map<String, dynamic>)).toList();
        }
        
        print('Unexpected response format: $responseData');
        return [];
      } catch (e) {
        print('Error parsing categories: $e');
        rethrow;
      }
    } else {
      throw Exception('Failed to load categories: ${response.statusCode}');
    }
  }

  // Fetch packages by category
  static Future<List<Package>> getPackages({int? categoryId}) async {
    try {
      // Try different endpoints based on what's available
      final url = categoryId != null && categoryId > 0
          ? Uri.parse('$baseUrl/categories/$categoryId/packages')  // Web route
          : Uri.parse('$baseUrl/api/galleries');  // Fallback to API galleries
          
      _logRequest('GET', url.toString());
      
      print('Fetching packages from: $url');
      final response = await http.get(
        url,
        headers: await _getHeaders(),
      );

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        
        // Handle both direct array and data object responses
        final List<dynamic> data = responseData is List 
            ? responseData 
            : (responseData['data'] ?? []);
            
        if (data.isEmpty) {
          print('No packages found in response');
          return [];
        }
        
        return data.map<Package>((json) {
          try {
            return Package.fromJson(json);
          } catch (e) {
            print('Error parsing package: $e\nJSON: $json');
            rethrow;
          }
        }).toList();
      } else {
        final error = 'Failed to load packages: ${response.statusCode}\n${response.body}';
        print(error);
        throw Exception(error);
      }
    } catch (e) {
      print('Error in getPackages: $e');
      rethrow;
    }
  }
}
