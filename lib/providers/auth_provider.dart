import 'package:flutter/foundation.dart';
import '../models/user_model.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  User? _user;
  bool _isLoading = false;
  String? _error;

  User? get user => _user;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isLoggedIn => _user != null;
  bool get isAdmin => _user?.isAdmin ?? false;

  final ApiService _apiService = ApiService();

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.login(email, password);
      if (response['success']) {
        _user = User.fromJson(response['data']['user']);
        await _saveUserToStorage(_user!);
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _error = response['message'];
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _error = 'Terjadi kesalahan: $e';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> register(String name, String email, String phone, String password) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.register(name, email, phone, password);
      if (response['success']) {
        _user = User.fromJson(response['data']['user']);
        await _saveUserToStorage(_user!);
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _error = response['message'];
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _error = 'Terjadi kesalahan: $e';
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    _user = null;
    await _clearUserFromStorage();
    notifyListeners();
  }

  Future<void> loadUserFromStorage() async {
    try {
      final userData = await _apiService.getStoredUser();
      if (userData != null) {
        _user = User.fromJson(userData);
        notifyListeners();
      }
    } catch (e) {
      debugPrint('Error loading user from storage: $e');
    }
  }

  Future<void> updateProfile(String name, String phone, String? profileImage) async {
    if (_user == null) return;

    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.updateProfile(_user!.id, name, phone, profileImage);
      if (response['success']) {
        _user = _user!.copyWith(
          name: name,
          phone: phone,
          profileImage: profileImage,
          updatedAt: DateTime.now(),
        );
        await _saveUserToStorage(_user!);
        _isLoading = false;
        notifyListeners();
      } else {
        _error = response['message'];
        _isLoading = false;
        notifyListeners();
      }
    } catch (e) {
      _error = 'Terjadi kesalahan: $e';
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> _saveUserToStorage(User user) async {
    await _apiService.saveUserToStorage(user.toJson());
  }

  Future<void> _clearUserFromStorage() async {
    await _apiService.clearUserFromStorage();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
