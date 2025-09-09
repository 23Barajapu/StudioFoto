import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../providers/service_provider.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/common/loading_widget.dart';
import '../../widgets/common/error_widget.dart';
import '../../widgets/admin/service_list_widget.dart';

class AdminServicesScreen extends StatefulWidget {
  const AdminServicesScreen({super.key});

  @override
  State<AdminServicesScreen> createState() => _AdminServicesScreenState();
}

class _AdminServicesScreenState extends State<AdminServicesScreen> {
  @override
  void initState() {
    super.initState();
    context.read<ServiceProvider>().fetchServices();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Kelola Layanan',
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

          return Column(
            children: [
              // Header
              Container(
                padding: const EdgeInsets.all(16.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Daftar Layanan',
                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    ElevatedButton.icon(
                      onPressed: () => _showAddServiceDialog(context),
                      icon: const Icon(Icons.add),
                      label: const Text('Tambah Layanan'),
                    ),
                  ],
                ),
              ),

              // Services List
              Expanded(
                child: serviceProvider.services.isEmpty
                    ? const Center(
                        child: Text('Belum ada layanan'),
                      )
                    : ServiceListWidget(services: serviceProvider.services),
              ),
            ],
          );
        },
      ),
    );
  }

  void _showAddServiceDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Tambah Layanan'),
        content: const Text('Fitur ini akan segera tersedia. Silakan gunakan API atau admin panel web untuk menambah layanan baru.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Tutup'),
          ),
        ],
      ),
    );
  }
}
