import 'package:flutter/foundation.dart';
import '../models/portfolio_model.dart';
import '../services/api_service.dart';

class PortfolioProvider with ChangeNotifier {
  List<Portfolio> _portfolios = [];
  bool _isLoading = false;
  String? _error;

  List<Portfolio> get portfolios => _portfolios;
  bool get isLoading => _isLoading;
  String? get error => _error;

  final ApiService _apiService = ApiService();

  Future<void> fetchPortfolios() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.getPortfolios();
      if (response['success']) {
        _portfolios = (response['data'] as List)
            .map((json) => Portfolio.fromJson(json))
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

  Future<bool> createPortfolio({
    required String title,
    required String description,
    required List<String> imageUrls,
    required String category,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.createPortfolio(
        title: title,
        description: description,
        imageUrls: imageUrls,
        category: category,
      );

      if (response['success']) {
        final newPortfolio = Portfolio.fromJson(response['data']);
        _portfolios.insert(0, newPortfolio);
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

  Future<bool> updatePortfolio({
    required String portfolioId,
    required String title,
    required String description,
    required List<String> imageUrls,
    required String category,
  }) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.updatePortfolio(
        portfolioId: portfolioId,
        title: title,
        description: description,
        imageUrls: imageUrls,
        category: category,
      );

      if (response['success']) {
        final index = _portfolios.indexWhere((p) => p.id == portfolioId);
        if (index != -1) {
          _portfolios[index] = Portfolio.fromJson(response['data']);
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

  Future<bool> deletePortfolio(String portfolioId) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.deletePortfolio(portfolioId);
      if (response['success']) {
        _portfolios.removeWhere((p) => p.id == portfolioId);
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

  Portfolio? getPortfolioById(String id) {
    try {
      return _portfolios.firstWhere((portfolio) => portfolio.id == id);
    } catch (e) {
      return null;
    }
  }

  List<Portfolio> getPortfoliosByCategory(String category) {
    return _portfolios.where((portfolio) => portfolio.category == category).toList();
  }

  List<String> getCategories() {
    return _portfolios.map((p) => p.category).toSet().toList();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
