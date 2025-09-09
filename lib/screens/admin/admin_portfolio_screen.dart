import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../providers/portfolio_provider.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/common/loading_widget.dart';
import '../../widgets/common/error_widget.dart';
import '../../widgets/admin/portfolio_list_widget.dart';

class AdminPortfolioScreen extends StatefulWidget {
  const AdminPortfolioScreen({super.key});

  @override
  State<AdminPortfolioScreen> createState() => _AdminPortfolioScreenState();
}

class _AdminPortfolioScreenState extends State<AdminPortfolioScreen> {
  @override
  void initState() {
    super.initState();
    context.read<PortfolioProvider>().fetchPortfolios();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Kelola Portfolio',
        showBackButton: true,
      ),
      body: Consumer<PortfolioProvider>(
        builder: (context, portfolioProvider, _) {
          if (portfolioProvider.isLoading) {
            return const LoadingWidget();
          }

          if (portfolioProvider.error != null) {
            return AppErrorWidget(
              message: portfolioProvider.error!,
              onRetry: () => portfolioProvider.fetchPortfolios(),
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
                      'Daftar Portfolio',
                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    ElevatedButton.icon(
                      onPressed: () => _showAddPortfolioDialog(context),
                      icon: const Icon(Icons.add),
                      label: const Text('Tambah Portfolio'),
                    ),
                  ],
                ),
              ),

              // Portfolio List
              Expanded(
                child: portfolioProvider.portfolios.isEmpty
                    ? const Center(
                        child: Text('Belum ada portfolio'),
                      )
                    : PortfolioListWidget(portfolios: portfolioProvider.portfolios),
              ),
            ],
          );
        },
      ),
    );
  }

  void _showAddPortfolioDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Tambah Portfolio'),
        content: const Text('Fitur ini akan segera tersedia. Silakan gunakan API atau admin panel web untuk menambah portfolio baru.'),
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
