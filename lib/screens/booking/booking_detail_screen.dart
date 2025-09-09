import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../providers/booking_provider.dart';
import '../../models/booking_model.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/common/loading_widget.dart';
import '../../widgets/common/error_widget.dart';

class BookingDetailScreen extends StatefulWidget {
  final String bookingId;

  const BookingDetailScreen({
    super.key,
    required this.bookingId,
  });

  @override
  State<BookingDetailScreen> createState() => _BookingDetailScreenState();
}

class _BookingDetailScreenState extends State<BookingDetailScreen> {
  @override
  void initState() {
    super.initState();
    context.read<BookingProvider>().fetchBookings();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Detail Booking',
        showBackButton: true,
      ),
      body: Consumer<BookingProvider>(
        builder: (context, bookingProvider, _) {
          if (bookingProvider.isLoading) {
            return const LoadingWidget();
          }

          final booking = bookingProvider.getBookingById(widget.bookingId);

          if (booking == null) {
            return const AppErrorWidget(message: 'Booking tidak ditemukan');
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Booking Status Card
                Card(
                  color: _getStatusColor(booking.status).withOpacity(0.1),
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Row(
                      children: [
                        Icon(
                          _getStatusIcon(booking.status),
                          color: _getStatusColor(booking.status),
                          size: 32,
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Status Booking',
                                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                                  color: Colors.grey[600],
                                ),
                              ),
                              Text(
                                booking.statusText,
                                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                                  fontWeight: FontWeight.bold,
                                  color: _getStatusColor(booking.status),
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 20),

                // Service Information
                _buildSection(
                  context,
                  'Informasi Layanan',
                  [
                    _buildInfoRow('Layanan', booking.service?.name ?? '-'),
                    _buildInfoRow('Harga', booking.formattedPrice),
                    _buildInfoRow('Durasi', booking.service?.formattedDuration ?? '-'),
                  ],
                ),

                const SizedBox(height: 20),

                // Schedule Information
                _buildSection(
                  context,
                  'Jadwal',
                  [
                    _buildInfoRow('Tanggal', _formatDate(booking.scheduledDate)),
                    _buildInfoRow('Jam', booking.timeSlot),
                    _buildInfoRow('Durasi', booking.service?.formattedDuration ?? '-'),
                  ],
                ),

                const SizedBox(height: 20),

                // Customer Information
                _buildSection(
                  context,
                  'Informasi Pelanggan',
                  [
                    _buildInfoRow('Nama', booking.user?.name ?? '-'),
                    _buildInfoRow('Email', booking.user?.email ?? '-'),
                    _buildInfoRow('Telepon', booking.user?.phone ?? '-'),
                  ],
                ),

                if (booking.notes.isNotEmpty) ...[
                  const SizedBox(height: 20),
                  _buildSection(
                    context,
                    'Catatan',
                    [
                      _buildInfoRow('', booking.notes),
                    ],
                  ),
                ],

                const SizedBox(height: 20),

                // Booking Information
                _buildSection(
                  context,
                  'Informasi Booking',
                  [
                    _buildInfoRow('ID Booking', booking.id),
                    _buildInfoRow('Tanggal Dibuat', _formatDate(booking.createdAt)),
                    _buildInfoRow('Terakhir Diupdate', _formatDate(booking.updatedAt)),
                  ],
                ),

                const SizedBox(height: 30),

                // Actions
                if (booking.canCancel || booking.canReschedule) ...[
                  Text(
                    'Aksi',
                    style: Theme.of(context).textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 12),
                  
                  if (booking.canReschedule)
                    SizedBox(
                      width: double.infinity,
                      child: OutlinedButton.icon(
                        onPressed: () => _rescheduleBooking(context, booking),
                        icon: const Icon(Icons.schedule),
                        label: const Text('Reschedule'),
                      ),
                    ),
                  
                  if (booking.canReschedule && booking.canCancel)
                    const SizedBox(height: 12),
                  
                  if (booking.canCancel)
                    SizedBox(
                      width: double.infinity,
                      child: OutlinedButton.icon(
                        onPressed: () => _cancelBooking(context, booking),
                        icon: const Icon(Icons.cancel),
                        label: const Text('Batalkan Booking'),
                        style: OutlinedButton.styleFrom(
                          foregroundColor: Colors.red,
                          side: const BorderSide(color: Colors.red),
                        ),
                      ),
                    ),
                  
                  const SizedBox(height: 20),
                ],
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildSection(BuildContext context, String title, List<Widget> children) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          title,
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 12),
        Card(
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              children: children,
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildInfoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(
              label,
              style: const TextStyle(fontWeight: FontWeight.w500),
            ),
          ),
          const Text(': '),
          Expanded(
            child: Text(value),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(BookingStatus status) {
    switch (status) {
      case BookingStatus.pending:
        return Colors.orange;
      case BookingStatus.confirmed:
        return Colors.green;
      case BookingStatus.inProgress:
        return Colors.blue;
      case BookingStatus.completed:
        return Colors.grey;
      case BookingStatus.cancelled:
        return Colors.red;
    }
  }

  IconData _getStatusIcon(BookingStatus status) {
    switch (status) {
      case BookingStatus.pending:
        return Icons.pending_actions;
      case BookingStatus.confirmed:
        return Icons.check_circle;
      case BookingStatus.inProgress:
        return Icons.play_circle;
      case BookingStatus.completed:
        return Icons.done_all;
      case BookingStatus.cancelled:
        return Icons.cancel;
    }
  }

  String _formatDate(DateTime date) {
    final months = [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    return '${date.day} ${months[date.month - 1]} ${date.year}';
  }

  void _rescheduleBooking(BuildContext context, Booking booking) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Reschedule Booking'),
        content: const Text('Fitur reschedule akan segera tersedia. Silakan hubungi admin untuk mengubah jadwal.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Tutup'),
          ),
        ],
      ),
    );
  }

  void _cancelBooking(BuildContext context, Booking booking) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Batalkan Booking'),
        content: const Text('Apakah Anda yakin ingin membatalkan booking ini?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              context.read<BookingProvider>().cancelBooking(booking.id);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(
                  content: Text('Booking berhasil dibatalkan'),
                  backgroundColor: Colors.green,
                ),
              );
            },
            child: const Text('Ya, Batalkan'),
          ),
        ],
      ),
    );
  }
}