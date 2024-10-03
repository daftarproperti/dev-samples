import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:propto_flutter/src/property_filter.dart';
import './model/property.dart';
import 'package:intl/intl.dart';

const String applicationName = 'Explorer App';

class ExplorerApp extends StatelessWidget {
  const ExplorerApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: applicationName,
      theme: ThemeData(
        primarySwatch: Colors.lightBlue,
        brightness: Brightness.light,
        textTheme: const TextTheme(
          headlineMedium: TextStyle(fontSize: 20.0, fontWeight: FontWeight.bold),
          bodyLarge: TextStyle(fontSize: 16.0),
          bodyMedium: TextStyle(fontSize: 14.0),
          labelLarge: TextStyle(color: Colors.white),
        ),
        inputDecorationTheme: InputDecorationTheme(
          filled: true,
          fillColor: Colors.grey[200],
          contentPadding: const EdgeInsets.symmetric(vertical: 8.0, horizontal: 12.0),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(8.0),
            borderSide: BorderSide(color: Colors.grey.shade400),
          ),
        ),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            textStyle: const TextStyle(fontSize: 16.0),
            foregroundColor: Colors.blueAccent,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8.0),
            ),
          ),
        ),
        dropdownMenuTheme: const DropdownMenuThemeData(
          textStyle: TextStyle(fontSize: 16.0),
        ),
        appBarTheme: const AppBarTheme(
          backgroundColor: Colors.lightBlue,
          elevation: 0,
          centerTitle: true,
          titleTextStyle: TextStyle(
            fontSize: 18.0,
            fontWeight: FontWeight.bold,
            color: Colors.white,
          ),
        ),
      ),
      home: const PropertyListScreen(),
    );
  }
}

class PropertyListScreen extends StatefulWidget {
  const PropertyListScreen({super.key});

  @override
  _PropertyListScreenState createState() => _PropertyListScreenState();
}

class _PropertyListScreenState extends State<PropertyListScreen> {
  final TextEditingController _searchController = TextEditingController();
  bool _showFilters = false;
  String _selectedOwnership = 'Any';

  List<PropertyListing> properties = [];
  bool _isLoading = false;

  int? _minBathroomCount;
  int? _maxBathroomCount;
  int? _minBedroomCount;
  int? _maxBedroomCount;
  int? _minPrice;
  int? _maxPrice;
  int? _minBuildingSize;
  int? _maxBuildingSize;
  int? _minLotSize;
  int? _maxLotSize;

  @override
  void initState() {
    super.initState();
    _fetchProperties();
  }

  Future<void> _fetchProperties({PropertyFilter? filter}) async {
    setState(() {
      _isLoading = true;
    });

    try {
      final PropertyFilter propFilter = filter ?? PropertyFilter();
      final List<PropertyListing> propertiesRes = await propFilter.fetchFilteredProperties();

      setState(() {
        properties = propertiesRes;
      });
    } catch (e) {
      print('Failed to load properties: $e');
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  void applyFilters() {
    final PropertyFilter filter = PropertyFilter(
      minBathroomCount: _minBathroomCount,
      maxBathroomCount: _maxBathroomCount,
      minBedroomCount: _minBedroomCount,
      maxBedroomCount: _maxBedroomCount,
      minPrice: _minPrice,
      maxPrice: _maxPrice,
      minBuildingSize: _minBuildingSize,
      maxBuildingSize: _maxBuildingSize,
      minLotSize: _minLotSize,
      maxLotSize: _maxLotSize,
      ownership: _selectedOwnership != 'Any' ? _selectedOwnership : null,
      search: _searchController.text.isNotEmpty ? _searchController.text : null,
    );

    _fetchProperties(filter: filter);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(applicationName),
      ),
      body: Padding(
        padding: const EdgeInsets.all(8.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            TextField(
              controller: _searchController,
              decoration: const InputDecoration(
                hintText: 'Search by title, desc, or address...',
                prefixIcon: Icon(Icons.search),
              ),
              onChanged: (value) {
                applyFilters();
              },
            ),
            const SizedBox(height: 8.0),

            Expanded(
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Expanded(
                    flex: 1,
                    child: SingleChildScrollView(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          GestureDetector(
                            onTap: () {
                              setState(() {
                                _showFilters = !_showFilters;
                              });
                            },
                            child: Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  'Filters',
                                  style: Theme.of(context).textTheme.headlineMedium,
                                ),
                                Icon(
                                  _showFilters ? Icons.expand_less : Icons.expand_more,
                                  color: Theme.of(context).iconTheme.color,
                                ),
                              ],
                            ),
                          ),
                          if (_showFilters)
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                FilterGroup(
                                  title: 'Price Range',
                                  onMinChanged: (value) => _minPrice = int.tryParse(value),
                                  onMaxChanged: (value) => _maxPrice = int.tryParse(value),
                                ),
                                FilterGroup(
                                  title: 'Bedroom Count',
                                  onMinChanged: (value) => _minBedroomCount = int.tryParse(value),
                                  onMaxChanged: (value) => _maxBedroomCount = int.tryParse(value),
                                ),
                                FilterGroup(
                                  title: 'Bathroom Count',
                                  onMinChanged: (value) => _minBathroomCount = int.tryParse(value),
                                  onMaxChanged: (value) => _maxBathroomCount = int.tryParse(value),
                                ),
                                FilterGroup(
                                  title: 'Building Size',
                                  onMinChanged: (value) => _minBuildingSize = int.tryParse(value),
                                  onMaxChanged: (value) => _maxLotSize = int.tryParse(value),
                                ),
                                FilterGroup(
                                  title: 'Lot Size',
                                  onMinChanged: (value) => _minLotSize = int.tryParse(value),
                                  onMaxChanged: (value) => _maxLotSize = int.tryParse(value),
                                ),
                                const SizedBox(height: 10),
                                Text(
                                  'Ownership: ',
                                  style: Theme.of(context).textTheme.bodyLarge,
                                ),
                                DropdownButton<String>(
                                  value: _selectedOwnership,
                                  items: ['Any', 'SHM', 'HGB']
                                      .map((String value) => DropdownMenuItem<String>(
                                            value: value,
                                            child: Text(value, style: Theme.of(context).textTheme.bodyMedium),
                                          ))
                                      .toList(),
                                  onChanged: (String? newValue) {
                                    setState(() {
                                      _selectedOwnership = newValue!.toLowerCase();
                                    });
                                  },
                                ),
                                const SizedBox(height: 10),
                                ElevatedButton(
                                  onPressed: () {
                                    applyFilters();
                                  },
                                  child: const Text('Apply Filters'),
                                ),
                                const Divider(),
                              ],
                            ),
                        ],
                      ),
                    ),
                  ),

                  Expanded(
                    flex: 3,
                    child: _isLoading
                        ? const Center(child: CircularProgressIndicator())
                        : properties.isEmpty
                            ? const Center(
                                child: Text(
                                  'No properties available.',
                                  style: TextStyle(fontSize: 16),
                                ),
                              )
                            : Padding(
                                padding: const EdgeInsets.all(8.0),
                                child: PropertyListView(properties: properties),
                              ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class FilterGroup extends StatelessWidget {
  final String title;
  final Function(String)? onMinChanged;
  final Function(String)? onMaxChanged;

  const FilterGroup({
    super.key,
    required this.title,
    required this.onMinChanged,
    required this.onMaxChanged,
  });

  @override
  Widget build(BuildContext context) {
    return ExpansionTile(
      title: Container(
        width: double.infinity,
        constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.8),
        child: Text(
          title,
          style: Theme.of(context).textTheme.bodyLarge,
          overflow: TextOverflow.clip,
          maxLines: 1,
        ),
      ),
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                width: double.infinity,
                constraints: const BoxConstraints(maxWidth: 200),
                child: Text(
                  'Min $title:',
                  style: Theme.of(context).textTheme.bodyMedium,
                  softWrap: false,
                  overflow: TextOverflow.ellipsis,
                  maxLines: 1,
                ),
              ),
              TextField(
                keyboardType: TextInputType.number,
                inputFormatters: <TextInputFormatter>[
                  FilteringTextInputFormatter.digitsOnly,
                ],
                onChanged: onMinChanged,
              ),
              const SizedBox(height: 10),
              Container(
                width: double.infinity,
                constraints: const BoxConstraints(maxWidth: 200),
                child: Text(
                  'Max $title:',
                  style: Theme.of(context).textTheme.bodyMedium,
                  softWrap: false,
                  overflow: TextOverflow.ellipsis,
                  maxLines: 1,
                ),
              ),
              TextField(
                keyboardType: TextInputType.number,
                inputFormatters: <TextInputFormatter>[
                  FilteringTextInputFormatter.digitsOnly,
                ],
                onChanged: onMaxChanged,
              ),
              const SizedBox(height: 20),
            ],
          ),
        ),
      ],
    );
  }
}


class PropertyCard extends StatefulWidget {
  final PropertyListing property;

  const PropertyCard({super.key, required this.property});

  @override
  _PropertyCardState createState() => _PropertyCardState();
}

class _PropertyCardState extends State<PropertyCard> {
  bool _isExpanded = false;

  final NumberFormat _currencyFormatter = NumberFormat.currency(
    locale: 'en_US',
    symbol: 'IDR ',
    decimalDigits: 0,
  );

  void _toggleExpand() {
    setState(() {
      _isExpanded = !_isExpanded;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Card(
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8.0),
      ),
      elevation: 3.0,
      margin: const EdgeInsets.symmetric(vertical: 8.0),
      child: InkWell(
        onTap: _toggleExpand,
        child: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              ListTile(
                title: Text(widget.property.title, style: Theme.of(context).textTheme.bodyLarge),
                subtitle: Text('${widget.property.address}\n${widget.property.cityName}',
                    style: Theme.of(context).textTheme.bodyMedium),
                trailing: Text(
                  _currencyFormatter.format(widget.property.price),
                  style: Theme.of(context).textTheme.bodyLarge!.copyWith(color: Theme.of(context).primaryColor),
                ),
              ),
              if (_isExpanded)
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Description:',
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                      Text(
                        widget.property.description ?? 'No description available',
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                      const SizedBox(height: 8.0),
                      Text(
                        'Details:',
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                      Text(
                        'Bathrooms: ${widget.property.bathroomCount}\n'
                        'Bedrooms: ${widget.property.bedroomCount}\n'
                        'Building Size: ${widget.property.buildingSize} m²\n'
                        'Lot Size: ${widget.property.lotSize} m²\n'
                        'Ownership: ${widget.property.ownership}',
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                      const SizedBox(height: 8.0),
                      Text(
                        'Updated At: ${widget.property.updatedAt?.toLocal()}',
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                      const SizedBox(height: 8.0),
                      Text(
                        'Coordinates:',
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                      Text(
                        'Latitude: ${widget.property.latitude}\nLongitude: ${widget.property.longitude}',
                        style: Theme.of(context).textTheme.bodyMedium,
                      ),
                    ],
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }
}

class PropertyListView extends StatelessWidget {
  final List<PropertyListing> properties;

  const PropertyListView({super.key, required this.properties});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: properties.length,
      itemBuilder: (context, index) {
        final property = properties[index];
        return PropertyCard(property: property);
      },
    );
  }
}
