import 'package:flutter/material.dart';

class AboutSectionWidget extends StatelessWidget {
  const AboutSectionWidget({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.all(16.0),
      padding: const EdgeInsets.all(24.0),
      decoration: BoxDecoration(
        color: Theme.of(context).primaryColor.withOpacity(0.05),
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Tentang Prime Foto Studio',
            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 16),
          Text(
            'Prime Foto Studio adalah studio fotografi profesional yang telah berpengalaman lebih dari 10 tahun dalam menangkap momen-momen berharga Anda. Kami mengkhususkan diri dalam berbagai jenis fotografi termasuk prewedding, wedding, portrait, dan event photography.',
            style: Theme.of(context).textTheme.bodyMedium,
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Expanded(
                child: _buildFeatureItem(
                  context,
                  Icons.camera_alt,
                  'Profesional',
                  'Tim fotografer berpengalaman',
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: _buildFeatureItem(
                  context,
                  Icons.photo_library,
                  'Kualitas Tinggi',
                  'Hasil foto berkualitas premium',
                ),
              ),
            ],
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              Expanded(
                child: _buildFeatureItem(
                  context,
                  Icons.schedule,
                  'Tepat Waktu',
                  'Penyelesaian sesuai jadwal',
                ),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: _buildFeatureItem(
                  context,
                  Icons.support_agent,
                  'Pelayanan Terbaik',
                  'Customer service 24/7',
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildFeatureItem(
    BuildContext context,
    IconData icon,
    String title,
    String description,
  ) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Icon(
          icon,
          color: Theme.of(context).primaryColor,
          size: 32,
        ),
        const SizedBox(height: 8),
        Text(
          title,
          style: Theme.of(context).textTheme.titleSmall?.copyWith(
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          description,
          style: Theme.of(context).textTheme.bodySmall?.copyWith(
            color: Colors.grey[600],
          ),
        ),
      ],
    );
  }
}
