import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../providers/booking_provider.dart';
import '../../models/booking_model.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/common/loading_widget.dart';
import '../../widgets/common/error_widget.dart';
import '../../widgets/admin/booking_list_widget.dart';
import '../../widgets/admin/booking_filter_widget.dart';

class AdminBookingsScreen extends StatefulWidget {
  const AdminBookingsScreen({super.key});

  @override
  State<AdminBookingsScreen> createState() => _AdminBookingsScreenState();
}

class _AdminBookingsScreenState extends State<AdminBookingsScreen> {
  BookingStatus? selectedStatus;

  @override
  void initState() {
    super.initState();
    context.read<BookingProvider>().fetchBookings();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Kelola Booking',
        showBackButton: true,
      ),
      body: Consumer<BookingProvider>(
        builder: (context, bookingProvider, _) {
          if (bookingProvider.isLoading) {
            return const LoadingWidget();
          }

          if (bookingProvider.error != null) {
            return ErrorWidget(
              message: bookingProvider.error!,
              onRetry: () => bookingProvider.fetchBookings(),
            );
          }

          final bookings = selectedStatus == null
              ? bookingProvider.bookings
              : bookingProvider.getBookingsByStatus(selectedStatus!);

          return Column(
            children: [
              // Filter
              BookingFilterWidget(
                selectedStatus: selectedStatus,
                onStatusChanged: (status) {
                  setState(() {
                    selectedStatus = status;
                  });
                },
              ),

              // Bookings List
              Expanded(
                child: bookings.isEmpty
                    ? const Center(
                        child: Text('Tidak ada booking'),
                      )
                    : BookingListWidget(bookings: bookings),
              ),
            ],
          );
        },
      ),
    );
  }
}
