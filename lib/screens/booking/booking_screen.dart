import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';
import 'package:table_calendar/table_calendar.dart';

import '../../providers/booking_provider.dart';
import '../../providers/service_provider.dart';
import '../../providers/auth_provider.dart';
import '../../models/service_model.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/common/loading_widget.dart';
import '../../widgets/common/error_widget.dart';

class BookingScreen extends StatefulWidget {
  const BookingScreen({super.key});

  @override
  State<BookingScreen> createState() => _BookingScreenState();
}

class _BookingScreenState extends State<BookingScreen> {
  Service? selectedService;
  DateTime? selectedDate;
  String? selectedTimeSlot;
  final TextEditingController notesController = TextEditingController();
  
  final List<String> timeSlots = [
    '08:00 - 10:00',
    '10:00 - 12:00',
    '12:00 - 14:00',
    '14:00 - 16:00',
    '16:00 - 18:00',
    '18:00 - 20:00',
  ];

  @override
  void initState() {
    super.initState();
    context.read<ServiceProvider>().fetchServices();
  }

  @override
  void dispose() {
    notesController.dispose();
    super.dispose();
  }

  Future<void> _handleBooking() async {
    if (selectedService == null || selectedDate == null || selectedTimeSlot == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Silakan lengkapi semua data booking'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    final bookingProvider = context.read<BookingProvider>();
    final success = await bookingProvider.createBooking(
      serviceId: selectedService!.id,
      scheduledDate: selectedDate!,
      timeSlot: selectedTimeSlot!,
      notes: notesController.text.trim(),
    );

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Booking berhasil dibuat!'),
          backgroundColor: Colors.green,
        ),
      );
      context.go('/');
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(bookingProvider.error ?? 'Booking gagal'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Booking Layanan',
        showBackButton: true,
      ),
      body: Consumer<ServiceProvider>(
        builder: (context, serviceProvider, _) {
          if (serviceProvider.isLoading) {
            return const LoadingWidget();
          }

          if (serviceProvider.error != null) {
            return AppErrorWidget(
              message: serviceProvider.error!,
              onRetry: () => serviceProvider.fetchServices(),
            );
          }

          return SingleChildScrollView(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Service Selection
                Text(
                  'Pilih Layanan',
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 12),
                if (serviceProvider.activeServices.isEmpty)
                  const Card(
                    child: Padding(
                      padding: EdgeInsets.all(16.0),
                      child: Text('Belum ada layanan tersedia'),
                    ),
                  )
                else
                  ...serviceProvider.activeServices.map((service) {
                    return Card(
                      margin: const EdgeInsets.only(bottom: 8),
                      child: RadioListTile<Service>(
                        title: Text(service.name),
                        subtitle: Text('${service.formattedPrice} • ${service.formattedDuration}'),
                        value: service,
                        groupValue: selectedService,
                        onChanged: (value) {
                          setState(() {
                            selectedService = value;
                          });
                        },
                      ),
                    );
                  }).toList(),

                const SizedBox(height: 24),

                // Date Selection
                Text(
                  'Pilih Tanggal',
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 12),
                Card(
                  child: TableCalendar<DateTime>(
                    firstDay: DateTime.now(),
                    lastDay: DateTime.now().add(const Duration(days: 90)),
                    focusedDay: selectedDate ?? DateTime.now(),
                    selectedDayPredicate: (day) {
                      return isSameDay(selectedDate, day);
                    },
                    onDaySelected: (selectedDay, focusedDay) {
                      setState(() {
                        selectedDate = selectedDay;
                        selectedTimeSlot = null; // Reset time slot when date changes
                      });
                    },
                    calendarFormat: CalendarFormat.month,
                    startingDayOfWeek: StartingDayOfWeek.monday,
                    headerStyle: const HeaderStyle(
                      formatButtonVisible: false,
                      titleCentered: true,
                    ),
                    calendarStyle: const CalendarStyle(
                      outsideDaysVisible: false,
                    ),
                  ),
                ),

                const SizedBox(height: 24),

                // Time Slot Selection
                if (selectedDate != null) ...[
                  Text(
                    'Pilih Jam',
                    style: Theme.of(context).textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const SizedBox(height: 12),
                  Wrap(
                    spacing: 8,
                    runSpacing: 8,
                    children: timeSlots.map((timeSlot) {
                      final isSelected = selectedTimeSlot == timeSlot;
                      return FilterChip(
                        label: Text(timeSlot),
                        selected: isSelected,
                        onSelected: (selected) {
                          setState(() {
                            selectedTimeSlot = selected ? timeSlot : null;
                          });
                        },
                        selectedColor: Theme.of(context).primaryColor.withOpacity(0.2),
                        checkmarkColor: Theme.of(context).primaryColor,
                      );
                    }).toList(),
                  ),
                  const SizedBox(height: 24),
                ],

                // Notes
                Text(
                  'Catatan (Opsional)',
                  style: Theme.of(context).textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: notesController,
                  maxLines: 3,
                  decoration: const InputDecoration(
                    hintText: 'Masukkan catatan khusus untuk sesi foto...',
                    border: OutlineInputBorder(),
                  ),
                ),

                const SizedBox(height: 24),

                // Booking Summary
                if (selectedService != null && selectedDate != null && selectedTimeSlot != null)
                  Card(
                    color: Theme.of(context).primaryColor.withOpacity(0.1),
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Ringkasan Booking',
                            style: Theme.of(context).textTheme.titleMedium?.copyWith(
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 12),
                          _buildSummaryRow('Layanan', selectedService!.name),
                          _buildSummaryRow('Tanggal', _formatDate(selectedDate!)),
                          _buildSummaryRow('Jam', selectedTimeSlot!),
                          _buildSummaryRow('Harga', selectedService!.formattedPrice),
                          if (notesController.text.isNotEmpty)
                            _buildSummaryRow('Catatan', notesController.text),
                        ],
                      ),
                    ),
                  ),

                const SizedBox(height: 24),

                // Book Button
                Consumer<BookingProvider>(
                  builder: (context, bookingProvider, _) {
                    if (bookingProvider.isLoading) {
                      return const LoadingWidget();
                    }

                    return SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _handleBooking,
                        child: const Text('Konfirmasi Booking'),
                      ),
                    );
                  },
                ),

                const SizedBox(height: 20),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildSummaryRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 80,
            child: Text(
              '$label:',
              style: const TextStyle(fontWeight: FontWeight.w500),
            ),
          ),
          Expanded(
            child: Text(value),
          ),
        ],
      ),
    );
  }

  String _formatDate(DateTime date) {
    final months = [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    return '${date.day} ${months[date.month - 1]} ${date.year}';
  }
}
