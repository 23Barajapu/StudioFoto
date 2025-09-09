import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';

import '../providers/auth_provider.dart';
import '../screens/home/home_screen.dart';
import '../screens/auth/login_screen.dart';
import '../screens/auth/register_screen.dart';
import '../screens/services/services_screen.dart';
import '../screens/booking/booking_screen.dart';
import '../screens/booking/booking_detail_screen.dart';
import '../screens/portfolio/portfolio_screen.dart';
import '../screens/profile/profile_screen.dart';
import '../screens/admin/admin_dashboard_screen.dart';
import '../screens/admin/admin_bookings_screen.dart';
import '../screens/admin/admin_services_screen.dart';
import '../screens/admin/admin_portfolio_screen.dart';

class AppRouter {
  static final GoRouter router = GoRouter(
    initialLocation: '/',
    redirect: (context, state) {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final isLoggedIn = authProvider.isLoggedIn;
      final isAdmin = authProvider.isAdmin;
      
      // Jika user belum login dan mencoba akses halaman yang memerlukan auth
      if (!isLoggedIn && _requiresAuth(state.location)) {
        return '/login';
      }
      
      // Jika user login sebagai admin dan mengakses halaman admin
      if (isLoggedIn && isAdmin && state.location.startsWith('/admin')) {
        return null; // Allow access
      }
      
      // Jika user biasa mencoba akses halaman admin
      if (isLoggedIn && !isAdmin && state.location.startsWith('/admin')) {
        return '/';
      }
      
      return null;
    },
    routes: [
      // Public Routes
      GoRoute(
        path: '/',
        builder: (context, state) => const HomeScreen(),
      ),
      GoRoute(
        path: '/services',
        builder: (context, state) => const ServicesScreen(),
      ),
      GoRoute(
        path: '/portfolio',
        builder: (context, state) => const PortfolioScreen(),
      ),
      
      // Auth Routes
      GoRoute(
        path: '/login',
        builder: (context, state) => const LoginScreen(),
      ),
      GoRoute(
        path: '/register',
        builder: (context, state) => const RegisterScreen(),
      ),
      
      // User Routes
      GoRoute(
        path: '/booking',
        builder: (context, state) => const BookingScreen(),
      ),
      GoRoute(
        path: '/booking/:id',
        builder: (context, state) {
          final bookingId = state.pathParameters['id']!;
          return BookingDetailScreen(bookingId: bookingId);
        },
      ),
      GoRoute(
        path: '/profile',
        builder: (context, state) => const ProfileScreen(),
      ),
      
      // Admin Routes
      GoRoute(
        path: '/admin',
        builder: (context, state) => const AdminDashboardScreen(),
      ),
      GoRoute(
        path: '/admin/bookings',
        builder: (context, state) => const AdminBookingsScreen(),
      ),
      GoRoute(
        path: '/admin/services',
        builder: (context, state) => const AdminServicesScreen(),
      ),
      GoRoute(
        path: '/admin/portfolio',
        builder: (context, state) => const AdminPortfolioScreen(),
      ),
    ],
  );
  
  static bool _requiresAuth(String location) {
    const protectedRoutes = [
      '/booking',
      '/profile',
      '/admin',
    ];
    
    return protectedRoutes.any((route) => location.startsWith(route));
  }
}
