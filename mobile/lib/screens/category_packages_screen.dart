import 'package:flutter/material.dart';
import 'package:mobile/models/package.dart';
import 'package:mobile/services/api_service.dart';
import 'package:mobile/screens/booking_screen.dart';

class CategoryPackagesScreen extends StatefulWidget {
  final int categoryId;
  final String categoryName;

  const CategoryPackagesScreen({
    Key? key,
    required this.categoryId,
    required this.categoryName,
  }) : super(key: key);

  @override
  _CategoryPackagesScreenState createState() => _CategoryPackagesScreenState();
}

class _CategoryPackagesScreenState extends State<CategoryPackagesScreen> {
  List<Package> _packages = [];
  bool _isLoading = true;
  String _error = '';

  @override
  void initState() {
    super.initState();
    _loadCategoryPackages();
  }

  Future<void> _loadCategoryPackages() async {
    if (!mounted) return;
    
    try {
      setState(() {
        _isLoading = true;
        _error = '';
      });

      final packages = await ApiService.getPackages(
        categoryId: widget.categoryId > 0 ? widget.categoryId : null,
      );
      
      if (!mounted) return;
      
      setState(() {
        _packages = packages;
        _isLoading = false;
      });
    } catch (e) {
      print('Error loading category packages: $e');
      
      if (!mounted) return;
      
      setState(() {
        _error = e.toString().contains('404')
            ? 'Paket tidak ditemukan untuk kategori ini.'
            : 'Gagal memuat paket. Silakan coba lagi.';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Paket ${widget.categoryName}'),
        backgroundColor: const Color(0xFF5C6BC0),
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _error.isNotEmpty
              ? Center(child: Text(_error))
              : _packages.isEmpty
                  ? const Center(child: Text('Tidak ada paket tersedia'))
                  : ListView.builder(
                      padding: const EdgeInsets.all(16.0),
                      itemCount: _packages.length,
                      itemBuilder: (context, index) {
                        final package = _packages[index];
                        return Card(
                          margin: const EdgeInsets.only(bottom: 16.0),
                          child: ListTile(
                            title: Text(package.name),
                            subtitle: Text(
                              'Rp ${package.price?.toStringAsFixed(0).replaceAll(RegExp(r'\B(?=(\d{3})+(?!\d))'), '.') ?? '0'},-',
                              style: const TextStyle(
                                color: Colors.green,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            onTap: () {
                              // Navigate to package detail or booking screen
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => BookingScreen(
                                    packageName: package.name,
                                    packagePrice: 'Rp ${package.price?.toStringAsFixed(0).replaceAll(RegExp(r'\B(?=(\d{3})+(?!\d))'), '.') ?? '0'},-',
                                  ),
                                ),
                              );
                            },
                          ),
                        );
                      },
                    ),
    );
  }
}
