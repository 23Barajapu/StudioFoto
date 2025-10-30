class Package {
  final int id;
  final String name;
  final String description;
  final double price;
  final int durationHours;
  final int photoCount;
  final int editedPhotoCount;
  final String? imageUrl;
  final bool isActive;

  Package({
    required this.id,
    required this.name,
    required this.description,
    required this.price,
    required this.durationHours,
    required this.photoCount,
    required this.editedPhotoCount,
    this.imageUrl,
    required this.isActive,
  });

  factory Package.fromJson(Map<String, dynamic> json) {
    return Package(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      price: double.parse(json['price'].toString()),
      durationHours: json['duration_hours'],
      photoCount: json['photo_count'],
      editedPhotoCount: json['edited_photo_count'],
      imageUrl: json['image_url'],
      isActive: json['is_active'],
    );
  }
}
