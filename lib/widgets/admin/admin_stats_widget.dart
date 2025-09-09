import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../providers/booking_provider.dart';
import '../../models/booking_model.dart';
import '../../providers/service_provider.dart';
import '../../providers/portfolio_provider.dart';

class AdminStatsWidget extends StatelessWidget {
  const AdminStatsWidget({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer3<BookingProvider, ServiceProvider, PortfolioProvider>(
      builder: (context, bookingProvider, serviceProvider, portfolioProvider, _) {
        final totalBookings = bookingProvider.bookings.length;
        final pendingBookings = bookingProvider.getBookingsByStatus(BookingStatus.pending).length;
        final completedBookings = bookingProvider.getBookingsByStatus(BookingStatus.completed).length;
        final totalServices = serviceProvider.services.length;
        final totalPortfolios = portfolioProvider.portfolios.length;

        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Statistik Overview',
              style: Theme.of(context).textTheme.titleLarge?.copyWith(
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 16),
            GridView.count(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisCount: 2,
              crossAxisSpacing: 16,
              mainAxisSpacing: 16,
              childAspectRatio: 1.5,
              children: [
                _buildStatCard(
                  context,
                  'Total Booking',
                  totalBookings.toString(),
                  Icons.calendar_today,
                  Colors.blue,
                ),
                _buildStatCard(
                  context,
                  'Menunggu Konfirmasi',
                  pendingBookings.toString(),
                  Icons.pending_actions,
                  Colors.orange,
                ),
                _buildStatCard(
                  context,
                  'Selesai',
                  completedBookings.toString(),
                  Icons.check_circle,
                  Colors.green,
                ),
                _buildStatCard(
                  context,
                  'Layanan',
                  totalServices.toString(),
                  Icons.camera_alt,
                  Colors.purple,
                ),
                _buildStatCard(
                  context,
                  'Portfolio',
                  totalPortfolios.toString(),
                  Icons.photo_library,
                  Colors.pink,
                ),
                _buildStatCard(
                  context,
                  'Pendapatan',
                  'Rp 15.000.000',
                  Icons.attach_money,
                  Colors.green,
                ),
              ],
            ),
          ],
        );
      },
    );
  }

  Widget _buildStatCard(
    BuildContext context,
    String title,
    String value,
    IconData icon,
    Color color,
  ) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Icon(
                icon,
                color: color,
                size: 24,
              ),
              Container(
                padding: const EdgeInsets.all(4),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Icon(
                  Icons.trending_up,
                  color: color,
                  size: 16,
                ),
              ),
            ],
          ),
          const Spacer(),
          Text(
            value,
            style: Theme.of(context).textTheme.headlineMedium?.copyWith(
              fontWeight: FontWeight.bold,
              color: color,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            title,
            style: Theme.of(context).textTheme.bodySmall?.copyWith(
              color: Colors.grey[600],
            ),
          ),
        ],
      ),
    );
  }
}
