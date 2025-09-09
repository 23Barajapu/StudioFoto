import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../providers/booking_provider.dart';
import '../../providers/service_provider.dart';
import '../../providers/portfolio_provider.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/admin/admin_stats_widget.dart';
import '../../widgets/admin/recent_bookings_widget.dart';
import '../../widgets/admin/quick_actions_widget.dart';

class AdminDashboardScreen extends StatefulWidget {
  const AdminDashboardScreen({super.key});

  @override
  State<AdminDashboardScreen> createState() => _AdminDashboardScreenState();
}

class _AdminDashboardScreenState extends State<AdminDashboardScreen> {
  @override
  void initState() {
    super.initState();
    _loadData();
  }

  void _loadData() {
    context.read<BookingProvider>().fetchBookings();
    context.read<ServiceProvider>().fetchServices();
    context.read<PortfolioProvider>().fetchPortfolios();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Admin Dashboard',
        showBackButton: true,
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          _loadData();
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Welcome Message
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [
                      Theme.of(context).primaryColor,
                      Theme.of(context).primaryColor.withOpacity(0.8),
                    ],
                  ),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Selamat Datang!',
                      style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Kelola Prime Foto Studio dengan mudah',
                      style: Theme.of(context).textTheme.bodyLarge?.copyWith(
                        color: Colors.white.withOpacity(0.9),
                      ),
                    ),
                  ],
                ),
              ),
              
              const SizedBox(height: 24),
              
              // Stats Overview
              const AdminStatsWidget(),
              
              const SizedBox(height: 24),
              
              // Quick Actions
              const QuickActionsWidget(),
              
              const SizedBox(height: 24),
              
              // Recent Bookings
              const RecentBookingsWidget(),
              
              const SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }
}
