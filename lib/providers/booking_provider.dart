import 'package:flutter/foundation.dart';
import '../models/booking_model.dart';
import '../services/api_service.dart';

class BookingProvider with ChangeNotifier {
  List<Booking> _bookings = [];
  bool _isLoading = false;
  String? _error;

  List<Booking> get bookings => _bookings;
  bool get isLoading => _isLoading;
  String? get error => _error;

  final ApiService _apiService = ApiService();

  Future<void> fetchBookings() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.getBookings();
      if (response['success']) {
        _bookings = (response['data'] as List)
            .map((json) => Booking.fromJson(json))
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

  Future<bool> createBooking({
    required String serviceId,
    required DateTime scheduledDate,
    required String timeSlot,
    String notes = '',
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await _apiService.createBooking(
        serviceId: serviceId,
        scheduledDate: scheduledDate,
        timeSlot: timeSlot,
        notes: notes,
      );

      if (response['success']) {
        final newBooking = Booking.fromJson(response['data']);
        _bookings.insert(0, newBooking);
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

  Future<bool> updateBookingStatus(String bookingId, BookingStatus status) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.updateBookingStatus(
        bookingId,
        status.toString().split('.').last,
      );
      if (response['success']) {
        final index = _bookings.indexWhere((b) => b.id == bookingId);
        if (index != -1) {
          _bookings[index] = Booking.fromJson(response['data']);
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

  Future<bool> cancelBooking(String bookingId) async {
    return await updateBookingStatus(bookingId, BookingStatus.cancelled);
  }

  Future<bool> rescheduleBooking(String bookingId, DateTime newDate, String newTimeSlot) async {
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _apiService.rescheduleBooking(bookingId, newDate, newTimeSlot);
      if (response['success']) {
        final index = _bookings.indexWhere((b) => b.id == bookingId);
        if (index != -1) {
          _bookings[index] = Booking.fromJson(response['data']);
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

  Booking? getBookingById(String id) {
    try {
      return _bookings.firstWhere((booking) => booking.id == id);
    } catch (e) {
      return null;
    }
  }

  List<Booking> getBookingsByStatus(BookingStatus status) {
    return _bookings.where((booking) => booking.status == status).toList();
  }

  List<Booking> getBookingsByDate(DateTime date) {
    return _bookings.where((booking) {
      return booking.scheduledDate.year == date.year &&
          booking.scheduledDate.month == date.month &&
          booking.scheduledDate.day == date.day;
    }).toList();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
