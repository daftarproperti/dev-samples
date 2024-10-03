class PropertyListing {
  final String id;
  final String address;
  final int bathroomCount;
  final int bedroomCount;
  final int buildingSize;
  final int carCount;
  final int cityId;
  final String cityName;
  final double? latitude;
  final double? longitude;
  final String description;
  final String facing;
  final int floorCount;
  final bool isVerified;
  final bool listingForRent;
  final bool listingForSale;
  final int lotSize;
  final String ownership;
  final List<String> pictureUrls;
  final double price;
  final String title;
  final DateTime? updatedAt;

  PropertyListing({
    required this.id,
    required this.address,
    required this.bathroomCount,
    required this.bedroomCount,
    required this.buildingSize,
    required this.carCount,
    required this.cityId,
    required this.cityName,
    this.latitude,
    this.longitude,
    required this.description,
    required this.facing,
    required this.floorCount,
    required this.isVerified,
    required this.listingForRent,
    required this.listingForSale,
    required this.lotSize,
    required this.ownership,
    required this.pictureUrls,
    required this.price,
    required this.title,
    required this.updatedAt,
  });

  factory PropertyListing.fromJson(Map<String, dynamic> json) {
    return PropertyListing(
      id: json['id'],
      title: json['title'],
      address: json['address'],
      description: json['description'] ?? '',
      cityId: json['city_id'],
      cityName: json['city_name'],
      latitude: json['latitude'] ?? json['coordinate']?['latitude'],
      longitude: json['longitude'] ?? json['coordinate']?['longitude'],
      bathroomCount: json['bathroom_count'] ?? 0,
      bedroomCount: json['bedroom_count'] ?? 0,
      buildingSize: json['building_size'] ?? 0,
      lotSize: json['lot_size'] ?? 0,
      carCount: json['car_count'] ?? 0,
      floorCount: json['floor_count'] ?? 0,
      facing: json['facing'] ?? '',
      isVerified: json['is_verified'] ?? false,
      listingForRent: json['listing_for_rent'] ?? false,
      listingForSale: json['listing_for_sale'] ?? false,
      ownership: json['ownership'] ?? '',
      pictureUrls: List<String>.from(json['picture_urls'] ?? []),
      price: json['price'] ?? 0.0,
      updatedAt: json['updated_at'] != null
          ? DateTime.tryParse(json['updated_at'])
          : null,
    );
  }
}