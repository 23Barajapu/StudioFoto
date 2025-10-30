import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:mobile/models/package.dart';

class PackageService {
  static const String _baseUrl = 'http://localhost:8000/api/v1';

  Future<List<Package>> getPackages() async {
    final response = await http.get(Uri.parse('$_baseUrl/packages'));

    if (response.statusCode == 200) {
      final List<dynamic> data = json.decode(response.body)['data'];
      return data.map((json) => Package.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load packages');
    }
  }
}
