import 'dart:convert';
import 'package:http/http.dart' as http;
import 'dart:html' as html;
import 'model/property.dart';
import 'package:flutter/foundation.dart';

class PropertyFilter {
  int? minBathroomCount;
  int? maxBathroomCount;
  int? minBedroomCount;
  int? maxBedroomCount;
  int? minBuildingSize;
  int? maxBuildingSize;
  int? minLotSize;
  int? maxLotSize;
  int? minPrice;
  int? maxPrice;
  String? ownership;
  String? search;

  PropertyFilter({
    this.minBathroomCount,
    this.maxBathroomCount,
    this.minBedroomCount,
    this.maxBedroomCount,
    this.minBuildingSize,
    this.maxBuildingSize,
    this.minLotSize,
    this.maxLotSize,
    this.minPrice,
    this.maxPrice,
    this.ownership,
    this.search,
  });

  Map<String, String> toQueryParameters() {
    final Map<String, String> queryParams = {};

    if (minBathroomCount != null) queryParams['min_bathroom_count'] = minBathroomCount.toString();
    if (maxBathroomCount != null) queryParams['max_bathroom_count'] = maxBathroomCount.toString();
    if (minBedroomCount != null) queryParams['min_bedroom_count'] = minBedroomCount.toString();
    if (maxBedroomCount != null) queryParams['max_bedroom_count'] = maxBedroomCount.toString();
    if (minBuildingSize != null) queryParams['min_building_size'] = minBuildingSize.toString();
    if (maxBuildingSize != null) queryParams['max_building_size'] = maxBuildingSize.toString();
    if (minLotSize != null) queryParams['min_lot_size'] = minLotSize.toString();
    if (maxLotSize != null) queryParams['max_lot_size'] = maxLotSize.toString();
    if (minPrice != null) queryParams['min_price'] = minPrice.toString();
    if (maxPrice != null) queryParams['max_price'] = maxPrice.toString();
    if (ownership != null) queryParams['ownership'] = ownership!;
    if (search != null) queryParams['search_text'] = search!;

    return queryParams;
  }

  Future<List<PropertyListing>> fetchFilteredProperties() async {
    return await fetchPropertyListingsFromBackend(this);
  }
}

Future<List<PropertyListing>> fetchPropertyListingsFromBackend(PropertyFilter filter) async {
  String apiUrl;

  if (kIsWeb) {
    const apiBase = String.fromEnvironment('WEB_BACKEND', defaultValue: '/api/proxy');
    apiUrl = '$apiBase/properties';
  } else if (kDebugMode) {
    // if it is android or ios emulator, then use following API URL
    apiUrl = const String.fromEnvironment('MOBILE_BACKEND', defaultValue: 'http://10.0.2.2:8001/properties');
  } else {
    throw Exception('Missing Backend URL'); 
  }

  final uri = Uri.parse(apiUrl).replace(queryParameters: filter.toQueryParameters());

  final response = await http.get(uri);

  if (response.statusCode == 200) {
    final List<dynamic> jsonData = jsonDecode(response.body);
    return jsonData.map((json) => PropertyListing.fromJson(json)).toList();
  } else {
    throw Exception('Failed to load property listings from backend');
  }
}