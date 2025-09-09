import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../providers/portfolio_provider.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/common/loading_widget.dart';
import '../../widgets/common/error_widget.dart';
import '../../widgets/portfolio/portfolio_grid_widget.dart';
import '../../widgets/portfolio/portfolio_category_filter_widget.dart';

class PortfolioScreen extends StatefulWidget {
  const PortfolioScreen({super.key});

  @override
  State<PortfolioScreen> createState() => _PortfolioScreenState();
}

class _PortfolioScreenState extends State<PortfolioScreen> {
  String? selectedCategory;

  @override
  void initState() {
    super.initState();
    context.read<PortfolioProvider>().fetchPortfolios();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Portfolio',
        showBackButton: true,
      ),
      body: Consumer<PortfolioProvider>(
        builder: (context, portfolioProvider, _) {
          if (portfolioProvider.isLoading) {
            return const LoadingWidget();
          }

          if (portfolioProvider.error != null) {
            return ErrorWidget(
              message: portfolioProvider.error!,
              onRetry: () => portfolioProvider.fetchPortfolios(),
            );
          }

          final portfolios = selectedCategory == null
              ? portfolioProvider.portfolios
              : portfolioProvider.getPortfoliosByCategory(selectedCategory!);

          return Column(
            children: [
              // Category Filter
              if (portfolioProvider.getCategories().isNotEmpty)
                PortfolioCategoryFilterWidget(
                  categories: portfolioProvider.getCategories(),
                  selectedCategory: selectedCategory,
                  onCategorySelected: (category) {
                    setState(() {
                      selectedCategory = category;
                    });
                  },
                ),

              // Portfolio Grid
              Expanded(
                child: portfolios.isEmpty
                    ? const Center(
                        child: Text('Belum ada portfolio tersedia'),
                      )
                    : PortfolioGridWidget(portfolios: portfolios),
              ),
            ],
          );
        },
      ),
    );
  }
}
