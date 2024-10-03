import 'dart:convert';
import 'package:http/http.dart' as http;

class FetchIPService {
  static const String _ipFetchURL = 'https://ipapi.co/json/';

  Future<Map<String, dynamic>> getLocationFromIP() async {
    try{
      final res = await http.get(Uri.parse(_ipFetchURL));
      if (res.statusCode != 200) {
        throw Exception('Failed to fetch location from IP');
      }

      final data = jsonDecode(res.body);
        return {
          'latitude': data['latitude'],
          'longitude': data['longitude'],
          'ip': data['ip'],
          'city': data['city'],
          'region': data['region'],
          'country': data['country_name'],
        };
    } catch (e) {
      throw Exception('Error fetching location from IP: $e');
    }
  }
}