import 'user_model.dart';
import 'service_model.dart';

enum BookingStatus {
  pending,
  confirmed,
  inProgress,
  completed,
  cancelled,
}

class Booking {
  final String id;
  final String userId;
  final String serviceId;
  final User? user;
  final Service? service;
  final DateTime scheduledDate;
  final String timeSlot;
  final String notes;
  final BookingStatus status;
  final double totalPrice;
  final DateTime createdAt;
  final DateTime updatedAt;

  Booking({
    required this.id,
    required this.userId,
    required this.serviceId,
    this.user,
    this.service,
    required this.scheduledDate,
    required this.timeSlot,
    this.notes = '',
    this.status = BookingStatus.pending,
    required this.totalPrice,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      id: json['id'],
      userId: json['user_id'],
      serviceId: json['service_id'],
      user: json['user'] != null ? User.fromJson(json['user']) : null,
      service: json['service'] != null ? Service.fromJson(json['service']) : null,
      scheduledDate: DateTime.parse(json['scheduled_date']),
      timeSlot: json['time_slot'],
      notes: json['notes'] ?? '',
      status: BookingStatus.values.firstWhere(
        (e) => e.toString().split('.').last == json['status'],
        orElse: () => BookingStatus.pending,
      ),
      totalPrice: (json['total_price'] as num).toDouble(),
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'service_id': serviceId,
      'user': user?.toJson(),
      'service': service?.toJson(),
      'scheduled_date': scheduledDate.toIso8601String(),
      'time_slot': timeSlot,
      'notes': notes,
      'status': status.toString().split('.').last,
      'total_price': totalPrice,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }

  String get formattedPrice => 'Rp ${totalPrice.toStringAsFixed(0).replaceAllMapped(
    RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
    (Match m) => '${m[1]}.',
  )}';

  String get statusText {
    switch (status) {
      case BookingStatus.pending:
        return 'Menunggu Konfirmasi';
      case BookingStatus.confirmed:
        return 'Dikonfirmasi';
      case BookingStatus.inProgress:
        return 'Sedang Berlangsung';
      case BookingStatus.completed:
        return 'Selesai';
      case BookingStatus.cancelled:
        return 'Dibatalkan';
    }
  }

  String get statusColor {
    switch (status) {
      case BookingStatus.pending:
        return '#F59E0B';
      case BookingStatus.confirmed:
        return '#10B981';
      case BookingStatus.inProgress:
        return '#3B82F6';
      case BookingStatus.completed:
        return '#6B7280';
      case BookingStatus.cancelled:
        return '#EF4444';
    }
  }

  bool get canCancel => status == BookingStatus.pending || status == BookingStatus.confirmed;
  bool get canReschedule => status == BookingStatus.pending || status == BookingStatus.confirmed;
}
