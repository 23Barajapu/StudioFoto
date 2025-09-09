import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../providers/auth_provider.dart';
import '../../providers/portfolio_provider.dart';
import '../../providers/service_provider.dart';
import '../../widgets/common/app_bar_widget.dart';
import '../../widgets/home/hero_section_widget.dart';
import '../../widgets/home/services_preview_widget.dart';
import '../../widgets/home/portfolio_preview_widget.dart';
import '../../widgets/home/about_section_widget.dart';
import '../../widgets/home/contact_section_widget.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    _loadData();
  }

  void _loadData() {
    // Load portfolios and services
    context.read<PortfolioProvider>().fetchPortfolios();
    context.read<ServiceProvider>().fetchServices();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AppBarWidget(
        title: 'Prime Foto Studio',
        showBackButton: false,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Hero Section
            const HeroSectionWidget(),
            
            const SizedBox(height: 40),
            
            // Services Preview
            const ServicesPreviewWidget(),
            
            const SizedBox(height: 40),
            
            // Portfolio Preview
            const PortfolioPreviewWidget(),
            
            const SizedBox(height: 40),
            
            // About Section
            const AboutSectionWidget(),
            
            const SizedBox(height: 40),
            
            // Contact Section
            const ContactSectionWidget(),
            
            const SizedBox(height: 20),
          ],
        ),
      ),
      floatingActionButton: Consumer<AuthProvider>(
        builder: (context, authProvider, _) {
          if (authProvider.isLoggedIn) {
            return FloatingActionButton.extended(
              onPressed: () => context.go('/booking'),
              icon: const Icon(Icons.camera_alt),
              label: const Text('Booking Sekarang'),
              backgroundColor: Theme.of(context).primaryColor,
              foregroundColor: Colors.white,
            );
          } else {
            return FloatingActionButton.extended(
              onPressed: () => context.go('/login'),
              icon: const Icon(Icons.login),
              label: const Text('Login'),
              backgroundColor: Theme.of(context).primaryColor,
              foregroundColor: Colors.white,
            );
          }
        },
      ),
    );
  }
}
