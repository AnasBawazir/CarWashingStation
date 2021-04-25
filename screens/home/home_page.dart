import 'dart:async';
import 'dart:convert';
import 'package:Magasil/screens/Mcarwash_full_page.dart';
import 'package:Magasil/screens/carwash_full_page.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:geolocator/geolocator.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/models/coworkerM.dart';
import 'package:Magasil/models/employee_profile_skills.dart';
import 'package:Magasil/models/home_category.dart';
import 'package:Magasil/models/home_offer.dart';
import 'package:Magasil/screens/custom_drawer.dart';
import 'package:smooth_star_rating/smooth_star_rating.dart';
import '../employee_profile.dart';
import '../services.dart';
import '../specialist_full_page.dart';
import 'package:connectivity/connectivity.dart';
import 'package:Magasil/models/carwash.dart';

const containerBackground = Color(0xFFeeeeee);
const darkBlue = Color(0xFF265E9E);
const ratingStar = Color(0xFFFECD03);

class HomePage extends StatefulWidget {
  final String Homepage = '/HomePage';

  const HomePage({Key key}) : super(key: key);

  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  var showSpinner = false;
  String _userImage = "";
  String _userName = '';
  var _isLoggedIn = false;
  bool checkConnectivity;
  DateTime currentBackPressTime;
  double _currentLatitude;
  double _currentLongitude;
  double _shopLatitude;
  double _shopLongitude;

  @override
  void initState() {
    super.initState();
    _getImage();
    getLatlong();
    //category
    _getDataCategories();
    _getDataSpecialist();
    //specialist
    _getDataSpecial();
    //offer
    _getDataOffer();
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

  Future<void> _getImage() async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        // Internet Present Case
        SharedPreferences localStorage = await SharedPreferences.getInstance();
        var user = localStorage.getString('user');
        if (user != null) {
          setState(() {
            _isLoggedIn = true;
          });
        } else {
          _isLoggedIn = false;
        }
        setState(() {
          showSpinner = true;
        });
        var res = await CallApi().getWithToken('user');
        var body = json.decode(res.body);
        var theData = body;
        if (theData != null) {
          _userName = theData['name'];
          _userImage = theData['completeImage'];
          // _theImage = _userImagePath + _userImage;
        }
        setState(() {
          showSpinner = false;
        });
      }
      // No-Internet Case
      else {
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
                        builder: (context) => HomePage(),
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

  Future<void> _getData() async {
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    var user = localStorage.getString('user');
    if (user != null) {
      _isLoggedIn = true;
    } else {
      _isLoggedIn = false;
    }
    _getImage();
    // setState(() async {
    // });
  }

  Future<void> getLatlong() async {
    setState(() {
      showSpinner = true;
    });
    var res = await CallApi().getWithToken('setting');
    var body = json.decode(res.body);
    var theData = body['data'];
    var apiLat = theData['latitude'];
    var apiLong = theData['longitude'];
    _shopLatitude = double.parse(apiLat.toString());
    _shopLongitude = double.parse(apiLong.toString());
    Position position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high);
    var lat = position.latitude;
    var long = position.longitude;
    _currentLatitude = double.parse(lat.toString());
    _currentLongitude = double.parse(long.toString());
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    localStorage.setDouble('currentLatitude', _currentLatitude);
    localStorage.setDouble('currentLongitude', _currentLongitude);
    localStorage.setDouble('shopLatitude', _shopLatitude);
    localStorage.setDouble('shopLongitude', _shopLongitude);
    setState(() {
      showSpinner = false;
    });
  }

  Future<bool> onWillPop() {
    DateTime now = DateTime.now();
    if (currentBackPressTime == null ||
        now.difference(currentBackPressTime) > Duration(seconds: 2)) {
      currentBackPressTime = now;
      Fluttertoast.showToast(msg: 'Press again to exit');
      return Future.value(false);
    }
    return Future.value(true);
  }

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      child: Scaffold(
        appBar: AppBar(
          elevation: 0,
          leading: Row(
            children: [
              SizedBox(
                width: 13.0,
              ),
              Container(
                margin: EdgeInsets.all(5.5),
                height: 30.0,
                width: 30.0,
                decoration: BoxDecoration(
                  color: Colors.white,
                  border: Border.all(color: Colors.white, width: 1.5),
                  shape: BoxShape.circle,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.white,
                      blurRadius: 1,
                      spreadRadius: 1.0,
                    ),
                  ],
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(20.0),
                  child: Image(
                    image: _isLoggedIn == false
                        ? AssetImage(
                            'assets/icons/profile_picture.png',
                          )
                        : NetworkImage(
                            (_userImage),
                          ),
                    height: 30.0,
                    width: 30.0,
                    fit: BoxFit.fill,
                  ),
                ),
              ),
            ],
          ),
          backgroundColor: Theme.of(context).primaryColor,
          title: Text(
            _isLoggedIn == false ? 'Anas Bawazir' : _userName,
            style: TextStyle(
              color: Colors.white,
              fontFamily: 'FivoSansMedium',
              fontSize: 18.0,
            ),
          ),
          actionsIconTheme: IconThemeData(color: Colors.white),
          actions: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 15.0),
              child: IconButton(
                onPressed: () {
                  Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => CustomDrawer(),
                      ));
                },
                icon: Icon(
                  FontAwesomeIcons.bars,
                  size: 22,
                  color: Colors.white,
                ),
              ),
            ),
          ],
        ),
        body: RefreshIndicator(
          color: Theme.of(context).primaryColor,
          onRefresh: _getData,
          child: SafeArea(
            top: true,
            bottom: true,
            left: false,
            right: false,
            child: ModalProgressHUD(
              inAsyncCall: showSpinner,
              child: Stack(
                children: [
                  ListView(
                    scrollDirection: Axis.vertical,
                    children: [
                      //welcome
                      GestureDetector(
                        onTap: () {
                          FocusScopeNode currentFocus = FocusScope.of(context);

                          if (!currentFocus.hasPrimaryFocus) {
                            currentFocus.unfocus();
                          }
                        },
                        child: Container(
                          height: MediaQuery.of(context).size.height / 6,
                          width: MediaQuery.of(context).size.width,
                          decoration: BoxDecoration(
                            color: Theme.of(context).primaryColor,
                            borderRadius: BorderRadiusDirectional.vertical(
                              bottom: Radius.circular(40.0),
                            ),
                          ),
                          child: Padding(
                            padding: const EdgeInsets.symmetric(vertical: 10.0),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.start,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                SizedBox(height: 18.0),
                                Padding(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 15.0),
                                  child: Text(
                                    'Welcome to',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontFamily: 'FivoSansMedium',
                                      fontSize: 18,
                                    ),
                                  ),
                                ),
                                Padding(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 15.0),
                                  child: Text(
                                    'Magasil',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontFamily: 'Nadillas',
                                      fontSize: 20,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                      SizedBox(height: 20.0),
                      CarWashStations,
                      MobileCarWash,
                      Thecategory,
                      //specialist
                      Specialist,
                      //offer
                      Offer,
                      SizedBox(height: 10.0),
                      //category
                      // Category(),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
      onWillPop: onWillPop,
    );
  }

  //category no data
  var passTheData;
  var previousSpeId = 3;
  var previousCatId = 1;
  var selectedSkills;
  var passId;
  List<CoworkerM> cw = List<CoworkerM>();

  List<Categories> ct = List<Categories>();
  Categories c = Categories();

  Future<void> _getDataSpecialist() async {
    var res = await CallApi().getWithToken('all_coworker');
    var body = json.decode(res.body);
    var theData = body['data'];
    cw = [];
    for (int i = 0; i < theData.length; i++) {
      Map<String, dynamic> map = theData[i];
      cw.add(CoworkerM.fromJson(map));
    }
    // specialistName = cw[0].name;
    passId = cw.first.id;
  }

  Future<Categories> _getDataCategories() async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        setState(() {
          showSpinner = true;
        });
        var res = await CallApi().getWithToken('category');
        var body = json.decode(res.body);
        var theData = body['data'];
        for (int i = 0; i < theData.length; i++) {
          Map<String, dynamic> map = theData[i];
          ct.add(Categories.fromJson(map));
        }
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
                        builder: (context) => HomePage(),
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

  Future<void> getData(passTheData, index, categories) async {
    setState(() {
      showSpinner = true;
    });
    var res =
        await CallApi().postData(passTheData, 'category_wise_service_coworker');
    var body = json.decode(res.body);
    var theData = body['data'];
    selectedSkills = theData.length;
    if (selectedSkills != null) {
      Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => Services(
              index: index,
              categoryId: categories,
              selecetedSkill: selectedSkills,
              previuosSpeId: passId,
            ),
          ));
    }
    setState(() {
      showSpinner = false;
    });
  }

  Widget get Thecategory {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 15.0),
      height: MediaQuery.of(context).size.height / 5.25,
      width: MediaQuery.of(context).size.width,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'Category',
                style: TextStyle(
                  color: darkBlue,
                  fontFamily: 'FivoSansMedium',
                  fontSize: 18,
                ),
              ),
            ],
          ),
          Container(
            height: MediaQuery.of(context).size.height / 6.2, //115.0
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: ct.length,
              itemBuilder: (context, index) {
                Categories categories = ct[index];
                return GestureDetector(
                  onTap: () {
                    previousCatId = categories.id;
                    previousSpeId = passId;
                    passTheData = {
                      "coworker_id": '$previousSpeId',
                      "category_id": '$previousCatId'
                    };
                    getData(passTheData, index, categories.id);
                  },
                  child: Column(
                    children: [
                      Container(
                        margin: EdgeInsets.all(8.0),
                        height: MediaQuery.of(context).size.height / 11,
                        width: MediaQuery.of(context).size.width / 6,
                        decoration: BoxDecoration(
                          shape: BoxShape.rectangle,
                          borderRadius: BorderRadius.all(Radius.circular(15.0)),
                          color: Colors.white,
                        ),
                        child: Image(
                          fit: BoxFit.scaleDown,
                          height: 28,
                          width: 27,
                          image: NetworkImage('${categories.image}'),
                        ),
                      ),
                      Container(
                        margin: EdgeInsets.symmetric(horizontal: 10.0),
                        color: Colors.white,
                        child: Text(
                          categories.category_name,
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: darkBlue,
                            fontSize: 16,
                            fontFamily: 'FivoSansRegular',
                          ),
                        ),
                      )
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  //specialist no data
  Future<void> _getDataSpecial() async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        setState(() {
          showSpinner = true;
        });
        var res = await CallApi().getWithToken('all_coworker');
        var body = json.decode(res.body);
        var theData = body['data'];
        for (int i = 0; i < theData.length; i++) {
          Map<String, dynamic> map = theData[i];
          sp.add(CoworkerM.fromJson(map));
        }
        var servicename = {"service_name": ""};
        for (int j = 0; j < theData.length; j++) {
          if (theData[j]['service'].length > 0) {
            Map<String, dynamic> map = theData[j]['service'][0];
            sk.add(Skill.fromJson(map));
          } else {
            Map<String, dynamic> map = servicename;
            sk.add(Skill.fromJson(map));
          }
        }
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
                        builder: (context) => HomePage(),
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

  var passIdSpecialist;
  List<CoworkerM> sp = new List<CoworkerM>();
  CoworkerM s = new CoworkerM();
  List<Skill> sk = List<Skill>();

  Widget get Specialist {
    return Container(
      height: MediaQuery.of(context).size.height / 3.1,
      width: MediaQuery.of(context).size.width,
      // color: Colors.red,
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 15.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Specialist',
                  style: TextStyle(
                    color: darkBlue,
                    fontFamily: 'FivoSansMedium',
                    fontSize: 18,
                  ),
                ),
                InkWell(
                  child: Padding(
                    padding: const EdgeInsets.all(8.0),
                    child: Text(
                      'View all',
                      style: TextStyle(
                        color: darkBlue,
                        fontFamily: 'FivoSansMedium',
                        fontSize: 14,
                      ),
                    ),
                  ),
                  onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => SpecialistFull(),
                        ));
                  },
                ),
              ],
            ),
          ),
          Container(
            margin: EdgeInsets.symmetric(horizontal: 5.5),
            height: MediaQuery.of(context).size.height / 3.7, //185.0
            width: MediaQuery.of(context).size.width,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: sp.length,
              itemBuilder: (context, index) {
                CoworkerM specialists = sp[index];
                Skill skill = sk[index];
                return GestureDetector(
                  onTap: () {
                    check().then((intenet) {
                      if (intenet != null && intenet) {
                        // Internet Present Case
                        passIdSpecialist = sp[index].id;
                        Navigator.push(
                            context,
                            MaterialPageRoute(
                                builder: (context) => EmployeeProfile(
                                      specialistId: passIdSpecialist,
                                    )));
                      }
                      // No-Internet Case
                      else {
                        showDialog(
                          builder: (context) => AlertDialog(
                            title: Text('Internet connection'),
                            content: Text('Check your internet connection'),
                            actions: <Widget>[
                              FlatButton(
                                onPressed: () async {
                                  Navigator.pop(context);
                                  Navigator.pushReplacement(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => HomePage(),
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
                  },
                  child: Container(
                    width: 112,
                    margin: EdgeInsets.all(10.0),
                    // color: Colors.yellow,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(10.0),
                          child: Image(
                            height: 100.0,
                            width: 100.0,
                            fit: BoxFit.cover,
                            image: NetworkImage('${specialists.image}'),
                          ),
                        ),
                        SizedBox(height: 5.0),
                        Text(
                          specialists.name,
                          style: TextStyle(
                            color: darkBlue,
                            fontFamily: 'FivoSansMedium',
                            fontSize: 16,
                          ),
                          textAlign: TextAlign.left,
                          overflow: TextOverflow.ellipsis,
                          maxLines: 1,
                        ),
                        SizedBox(height: 5.0),
                        Align(
                          alignment: Alignment.topLeft,
                          child: SmoothStarRating(
                            borderColor: ratingStar,
                            color: ratingStar,
                            size: 15,
                            defaultIconData: Icons.star_border,
                            rating: specialists.rating,
                            spacing: 1.0,
                            allowHalfRating: true,
                            isReadOnly: true,
                          ),
                        ),
                        SizedBox(height: 5.0),
                        Text(
                          skill.name,
                          style: TextStyle(
                            color: darkBlue,
                            fontSize: 12,
                            fontFamily: 'FivoSansMedium',
                          ),
                          textAlign: TextAlign.left,
                          overflow: TextOverflow.ellipsis,
                          maxLines: 1,
                        )
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  //offerdata

  Future<void> _getDataOffer() async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        // Internet Present Case
        setState(() {
          showSpinner = true;
        });
        var res = await CallApi().getWithToken('offer');
        var body = json.decode(res.body);
        var theData = body['data'];
        for (int i = 0; i < theData.length; i++) {
          Map<String, dynamic> map = theData[i];
          of.add(Offers.fromJson(map));
        }
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
                        builder: (context) => HomePage(),
                      ));
                },
                child: Text('OK'),
              )
            ],
          ),
          context: context,
        );
      }
      // No-Internet Case
    });
  }

  List<Offers> of = List<Offers>();
  Offers o = Offers();

  Widget get Offer {
    return Container(
      height: MediaQuery.of(context).size.height / 4.7,
      width: MediaQuery.of(context).size.width,
      // color: Colors.red,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 15.0),
            child: Text(
              'Offer',
              style: TextStyle(
                color: darkBlue,
                fontFamily: 'FivoSansMedium',
                fontSize: 18,
              ),
            ),
          ),
          Container(
            height: MediaQuery.of(context).size.height / 5.7, //133.0
            // color: Colors.red,
            width: MediaQuery.of(context).size.width,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: of.length,
              itemBuilder: (context, index) {
                Offers offer = of[index];
                return Container(
                  // color: Colors.red,
                  height: 113,
                  width: 253,
                  // color: Colors.yellow,
                  margin: EdgeInsets.all(15.0),
                  child: Stack(
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(10.0),
                        child: Image(
                          height: 113,
                          width: 253,
                          fit: BoxFit.fill,
                          image: NetworkImage(offer.image),
                        ),
                      ),
                      Positioned(
                        top: 15.0,
                        right: 15.0,
                        child: Container(
                          padding: EdgeInsets.all(5.0),
                          // color: Colors.yellow,
                          height: 68.0,
                          width: 100.0,
                          child: Text(
                            offer.description,
                            textAlign: TextAlign.center,
                            style: TextStyle(
                              color: darkBlue,
                              fontFamily: 'FivoSansMedium',
                              fontSize: 14.0,
                            ),
                            maxLines: 4,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ),
                    ],
                  ),
                );
              },
            ),
          )
        ],
      ),
    );
  }

  CarWash carwashs;
  List<String> list = [
    "happy",
    "cleanest",
    "most clean",
    'anas',
    'reslaan',
    'ui'
  ];

  Widget get CarWashStations {
    return Container(
      height: MediaQuery.of(context).size.height / 4,
      width: MediaQuery.of(context).size.width,
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 15.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Car Wash Stations',
                  style: TextStyle(
                    color: darkBlue,
                    fontFamily: 'FivoSansMedium',
                    fontSize: 18,
                  ),
                ),
                InkWell(
                  child: Padding(
                    padding: const EdgeInsets.all(8.0),
                    child: Text(
                      'View all',
                      style: TextStyle(
                        color: darkBlue,
                        fontFamily: 'FivoSansMedium',
                        fontSize: 14,
                      ),
                    ),
                  ),
                  onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => CarWashFull(),
                            ));
                  },
                ),
              ],
            ),
          ),
          Container(
            margin: EdgeInsets.symmetric(horizontal: 5.5),
            height: MediaQuery.of(context).size.height / 5, //185.0
            width: MediaQuery.of(context).size.width,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: list.length,
              itemBuilder: (context, index) {
                var numPic=index+1;
                return GestureDetector(
                  onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => EmployeeProfile(
                                //specialistId: carwashs.list[index],
                                )));
                  },
                  child: Container(
                    width: 112,
                    margin: EdgeInsets.all(10.0),
                    // color: Colors.yellow,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(10.0),
                          child: Image.asset(
                            'assets/images/portfolio$numPic.png',
                            fit: BoxFit.fill,
                            height: 100.0,
                            width: 100.0,
                          ),
                        ),
                        SizedBox(height: 5.0),
                        Text(
                          list[index],
                          style: TextStyle(
                            color: darkBlue,
                            fontFamily: 'FivoSansMedium',
                            fontSize: 16,
                          ),
                          textAlign: TextAlign.left,
                          overflow: TextOverflow.ellipsis,
                          maxLines: 1,
                        ),
                        SizedBox(height: 5.0),
                        Align(
                          alignment: Alignment.topLeft,
                          child: SmoothStarRating(
                            borderColor: ratingStar,
                            color: ratingStar,
                            size: 15,
                            defaultIconData: Icons.star_border,
                            //rating: carwashs.rate,
                            spacing: 1.0,
                            allowHalfRating: true,
                            isReadOnly: true,
                          ),
                        ),
                        SizedBox(height: 5.0),
                        Text(
                          'good',
                          style: TextStyle(
                            color: darkBlue,
                            fontSize: 12,
                            fontFamily: 'FivoSansMedium',
                          ),
                          textAlign: TextAlign.left,
                          overflow: TextOverflow.ellipsis,
                          maxLines: 1,
                        )
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
  CarWash Mcarwashs;
  List<String> Mlist = [
    "summer",
    "ramadan",
    "crystal clear",
    'soft',
    'more',
    'ui'
  ];

  Widget get MobileCarWash {
    return Container(
      height: MediaQuery.of(context).size.height / 4,
      width: MediaQuery.of(context).size.width,
      child: Column(
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 15.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Mobile Car Wash',
                  style: TextStyle(
                    color: darkBlue,
                    fontFamily: 'FivoSansMedium',
                    fontSize: 18,
                  ),
                ),
                InkWell(
                  child: Padding(
                    padding: const EdgeInsets.all(8.0),
                    child: Text(
                      'View all',
                      style: TextStyle(
                        color: darkBlue,
                        fontFamily: 'FivoSansMedium',
                        fontSize: 14,
                      ),
                    ),
                  ),
                  onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => MCarWashFull(),
                        ));
                  },
                ),
              ],
            ),
          ),
          Container(
            margin: EdgeInsets.symmetric(horizontal: 5.5),
            height: MediaQuery.of(context).size.height / 5, //185.0
            width: MediaQuery.of(context).size.width,
            child: ListView.builder(
              scrollDirection: Axis.horizontal,
              itemCount: Mlist.length,
              itemBuilder: (context, index) {
                var numPic=index+5;
                return GestureDetector(
                  onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (context) => EmployeeProfile(
                              //specialistId: carwashs.list[index],
                            )));
                  },
                  child: Container(
                    width: 112,
                    margin: EdgeInsets.all(10.0),
                    // color: Colors.yellow,
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(10.0),
                          child: Image.asset(
                            'assets/images/portfolio$numPic.png',
                            fit: BoxFit.fill,
                            height: 100.0,
                            width: 100.0,
                          ),
                        ),
                        SizedBox(height: 5.0),
                        Text(
                          Mlist[index],
                          style: TextStyle(
                            color: darkBlue,
                            fontFamily: 'FivoSansMedium',
                            fontSize: 16,
                          ),
                          textAlign: TextAlign.left,
                          overflow: TextOverflow.ellipsis,
                          maxLines: 1,
                        ),
                        SizedBox(height: 5.0),
                        Align(
                          alignment: Alignment.topLeft,
                          child: SmoothStarRating(
                            borderColor: ratingStar,
                            color: ratingStar,
                            size: 15,
                            defaultIconData: Icons.star_border,
                            //rating: carwashs.rate,
                            spacing: 1.0,
                            allowHalfRating: true,
                            isReadOnly: true,
                          ),
                        ),
                        SizedBox(height: 5.0),
                        Text(
                          'SUPER',
                          style: TextStyle(
                            color: darkBlue,
                            fontSize: 12,
                            fontFamily: 'FivoSansMedium',
                          ),
                          textAlign: TextAlign.left,
                          overflow: TextOverflow.ellipsis,
                          maxLines: 1,
                        )
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}
