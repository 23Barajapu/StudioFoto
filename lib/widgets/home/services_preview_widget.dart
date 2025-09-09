import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:go_router/go_router.dart';

import '../../providers/service_provider.dart';
import '../../widgets/common/loading_widget.dart';
import '../../widgets/common/error_widget.dart';

class ServicesPreviewWidget extends StatelessWidget {
  const ServicesPreviewWidget({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer<ServiceProvider>(
      builder: (context, serviceProvider, _) {
        if (serviceProvider.isLoading) {
          return const LoadingWidget();
        }

        if (serviceProvider.error != null) {
          return AppErrorWidget(message: serviceProvider.error!);
        }

        final services = serviceProvider.activeServices.take(3).toList();

        return Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Layanan Kami',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  TextButton(
                    onPressed: () => context.go('/services'),
                    child: const Text('Lihat Semua'),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              if (services.isEmpty)
                const Center(
                  child: Text('Belum ada layanan tersedia'),
                )
              else
                SizedBox(
                  height: 200,
                  child: ListView.builder(
                    scrollDirection: Axis.horizontal,
                    itemCount: services.length,
                    itemBuilder: (context, index) {
                      final service = services[index];
                      return Container(
                        width: 280,
                        margin: const EdgeInsets.only(right: 16),
                        child: Card(
                          child: Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Container(
                                  height: 80,
                                  width: double.infinity,
                                  decoration: BoxDecoration(
                                    color: Theme.of(context).primaryColor.withOpacity(0.1),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: service.imageUrl != null
                                      ? ClipRRect(
                                          borderRadius: BorderRadius.circular(8),
                                          child: Image.network(
                                            service.imageUrl!,
                                            fit: BoxFit.cover,
                                            errorBuilder: (context, error, stackTrace) {
                                              return const Icon(
                                                Icons.camera_alt,
                                                size: 40,
                                                color: Colors.grey,
                                              );
                                            },
                                          ),
                                        )
                                      : const Icon(
                                          Icons.camera_alt,
                                          size: 40,
                                          color: Colors.grey,
                                        ),
                                ),
                                const SizedBox(height: 12),
                                Text(
                                  service.name,
                                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                    fontWeight: FontWeight.bold,
                                  ),
                                  maxLines: 1,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  service.description,
                                  style: Theme.of(context).textTheme.bodySmall?.copyWith(
                                    color: Colors.grey[600],
                                  ),
                                  maxLines: 2,
                                  overflow: TextOverflow.ellipsis,
                                ),
                                const Spacer(),
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      service.formattedPrice,
                                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                                        color: Theme.of(context).primaryColor,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                    Text(
                                      service.formattedDuration,
                                      style: Theme.of(context).textTheme.bodySmall,
                                    ),
                                  ],
                                ),
                              ],
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                ),
            ],
          ),
        );
      },
    );
  }
}
