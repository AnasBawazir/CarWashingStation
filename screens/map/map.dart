import 'dart:convert';
import 'dart:typed_data';
import 'package:connectivity/connectivity.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:geolocator/geolocator.dart';
import 'package:here_sdk/core.dart';
import 'package:here_sdk/mapview.dart';
import 'package:here_sdk/routing.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:Magasil/api/api.dart';

class Map extends StatefulWidget {
  // final double currentLatitude, currentLongitude, shopLatitude, shopLongitude;

  // const Map(
  //     {Key key,
  //     this.currentLatitude,
  //     this.currentLongitude,
  //     this.shopLatitude,
  //     this.shopLongitude})
  //     : super(key: key);
  @override
  _MapState createState() => _MapState();
}

class _MapState extends State<Map> {
  bool showSpinner = false;
  HereMapController _controller;
  MapPolyline _mapPolyline;
  double _currentLatitude;
  double _currentLongitude;
  double _shopLatitude;
  double _shopLongitude;

  @override
  void initState() {
    super.initState();
    getLocationData();
  }

  @override
  void dispose() {
    _controller?.finalize();
    super.dispose();
  }

  Future<void> getLocationData() async {
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    _currentLatitude = localStorage.getDouble('currentLatitude');
    _currentLongitude = localStorage.getDouble('currentLongitude');
    _shopLatitude = localStorage.getDouble('shopLatitude');
    _shopLongitude = localStorage.getDouble('shopLongitude');
    print(
        'this is the value $_currentLatitude ,$_currentLongitude ,$_shopLatitude ,$_shopLongitude');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Map',
          style: TextStyle(
            color: Colors.white,
            fontSize: 18,
            fontFamily: 'FivoSansMedium',
          ),
        ),
        centerTitle: true,
      ),
      body: ModalProgressHUD(
        inAsyncCall: showSpinner,
        child: Stack(
          children: [
            HereMap(
              onMapCreated: onMapCreated,
            ),
            // Positioned(
            //   bottom: 20.0,
            //   left: MediaQuery.of(context).size.width / 2.5,
            //   child: RaisedButton(
            //     color: Theme.of(context).primaryColor,
            //     onPressed: () {
            //       if (_mapPolyline != null) {
            //         _controller.mapScene.removeMapPolyline(_mapPolyline);
            //         _mapPolyline = null;
            //       }
            //     },
            //     child: Text(
            //       'clear map',
            //       style: TextStyle(
            //         color: Colors.white,
            //         fontSize: 14,
            //         fontFamily: 'FivoSansMedium',
            //       ),
            //     ),
            //   ),
            // )
          ],
        ),
      ),
    );
  }

  Future<bool> check() async {
    var connectivityResult = await (Connectivity().checkConnectivity());
    if (connectivityResult == ConnectivityResult.mobile) {
      return true;
    } else if (connectivityResult == ConnectivityResult.wifi) {
      return true;
    }
    return false;
  }

  Future<void> drawRedDot(
    HereMapController hereMapController,
    int drawOrder,
    GeoCoordinates geoCoordinates,
  ) async {
    ByteData fileData = await rootBundle.load('assets/icons/circle.png');
    Uint8List pixelData = fileData.buffer.asUint8List();
    MapImage mapImage =
        MapImage.withPixelDataAndImageFormat(pixelData, ImageFormat.png);
    MapMarker mapMarker = MapMarker(geoCoordinates, mapImage);
    mapMarker.drawOrder = drawOrder;
    hereMapController.mapScene.addMapMarker(mapMarker);
  }

  Future<void> drawPin(
    HereMapController hereMapController,
    int drawOrder,
    GeoCoordinates geoCoordinates,
  ) async {
    ByteData fileData = await rootBundle.load('assets/icons/poi.png');
    Uint8List pixelData = fileData.buffer.asUint8List();
    MapImage mapImage =
        MapImage.withPixelDataAndImageFormat(pixelData, ImageFormat.png);
    Anchor2D anchor2d = Anchor2D.withHorizontalAndVertical(0.5, 1);
    MapMarker mapMarker =
        MapMarker.withAnchor(geoCoordinates, mapImage, anchor2d);
    mapMarker.drawOrder = drawOrder;
    hereMapController.mapScene.addMapMarker(mapMarker);
  }

  Future<void> drawRoute(GeoCoordinates start, GeoCoordinates end,
      HereMapController hereMapController) async {
    //create a routing engine
    RoutingEngine routingEngine = RoutingEngine();

    //make a way point
    Waypoint startWayPoint = Waypoint.withDefaults(start);
    Waypoint endWayPoint = Waypoint.withDefaults(end);
    List<Waypoint> wayPoints = [startWayPoint, endWayPoint];

    //calculate the route
    routingEngine.calculateCarRoute(wayPoints, CarOptions.withDefaults(),
        (routingError, routes) {
      if (routingError == null) {
        var route = routes.first;

        //create a polyline
        GeoPolyline routeGeoPolyline = GeoPolyline(route.polyline);

        //create a visual representation for the polyline
        double depth = 20;
        _mapPolyline = MapPolyline(routeGeoPolyline, depth, Colors.blue);

        //install de controller to draw on the map
        hereMapController.mapScene.addMapPolyline(_mapPolyline);
      }
    });
  }

  void onMapCreated(HereMapController hereMapController) {
    check().then((intenet) {
      if (intenet != null && intenet) {
        setState(() {
          showSpinner = true;
        });

        _controller = hereMapController;
        hereMapController.mapScene.loadSceneForMapScheme(MapScheme.normalDay,
            (error) {
          if (error != null) {
            print('Error:' + error.toString());
            return;
          }
        });

        drawRedDot(hereMapController, 0,
            GeoCoordinates(_currentLatitude, _currentLongitude));
        drawPin(hereMapController, 1,
            GeoCoordinates(_currentLatitude, _currentLongitude));
        drawRoute(GeoCoordinates(_currentLatitude, _currentLongitude),
            GeoCoordinates(_shopLatitude, _shopLongitude), hereMapController);
        drawRedDot(hereMapController, 2,
            GeoCoordinates(_shopLatitude, _shopLongitude));
        drawPin(hereMapController, 3,
            GeoCoordinates(_shopLatitude, _shopLongitude));
        double distanceToEarthInMeters = 8000;
        hereMapController.camera.lookAtPointWithDistance(
            GeoCoordinates(_currentLatitude, _currentLongitude),
            distanceToEarthInMeters);

        setState(() {
          showSpinner = false;
        });
      } else {
        showDialog(
          builder: (context) => AlertDialog(
            title: Text('Internet connection'),
            content: Text('Check your internet connection'),
            actions: <Widget>[
              FlatButton(
                onPressed: () async {
                  Navigator.pop(context);
                  Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => Map(),
                      ));
                },
                child: Text('OK'),
              )
            ],
          ),
          context: context,
        );
      }
    });
  }
}
