import 'package:flutter/foundation.dart';
import '../models/service_model.dart';
import '../services/api_service.dart';

class ServiceProvider with ChangeNotifier {
  List<Service> _services = [];
  bool _isLoading = false;
  String? _error;

  List<Service> get services => _services;
  List<Service> get activeServices => _services.where((s) => s.isActive).toList();
  bool get isLoading => _isLoading;
  String? get error => _error;

  final ApiService _apiService = ApiService();

  Future<void> fetchServices() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.getServices();
      if (response['success']) {
        _services = (response['data'] as List)
            .map((json) => Service.fromJson(json))
            .toList();
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

  Future<bool> createService({
    required String name,
    required String description,
    required double price,
    required int duration,
    required List<String> features,
    String? imageUrl,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.createService(
        name: name,
        description: description,
        price: price,
        duration: duration,
        features: features,
        imageUrl: imageUrl,
      );

      if (response['success']) {
        final newService = Service.fromJson(response['data']);
        _services.add(newService);
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

  Future<bool> updateService({
    required String serviceId,
    required String name,
    required String description,
    required double price,
    required int duration,
    required List<String> features,
    String? imageUrl,
    bool? isActive,
  }) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.updateService(
        serviceId: serviceId,
        name: name,
        description: description,
        price: price,
        duration: duration,
        features: features,
        imageUrl: imageUrl,
        isActive: isActive,
      );

      if (response['success']) {
        final index = _services.indexWhere((s) => s.id == serviceId);
        if (index != -1) {
          _services[index] = Service.fromJson(response['data']);
          _isLoading = false;
          notifyListeners();
          return true;
        }
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
    return false;
  }

  Future<bool> deleteService(String serviceId) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.deleteService(serviceId);
      if (response['success']) {
        _services.removeWhere((s) => s.id == serviceId);
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

  Service? getServiceById(String id) {
    try {
      return _services.firstWhere((service) => service.id == id);
    } catch (e) {
      return null;
    }
  }

  List<Service> getServicesByPriceRange(double minPrice, double maxPrice) {
    return _services.where((service) {
      return service.price >= minPrice && service.price <= maxPrice;
    }).toList();
  }

  List<Service> getServicesByDuration(int maxDuration) {
    return _services.where((service) {
      return service.duration <= maxDuration;
    }).toList();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
