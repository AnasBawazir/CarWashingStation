import 'dart:convert';
import 'map/map.dart';
import 'package:connectivity/connectivity.dart';
import 'package:flutter/material.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/screens/all_appointment.dart';
import 'package:Magasil/screens/full_profile.dart';
import 'package:Magasil/screens/home/home_page.dart';
import 'package:Magasil/screens/notification.dart';
import 'package:Magasil/screens/privacy_policy.dart';
import 'package:Magasil/screens/sign_in.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);

class CustomDrawer extends StatefulWidget {
  @override
  _CustomDrawerState createState() => _CustomDrawerState();
}

class _CustomDrawerState extends State<CustomDrawer> {
  int tappedIndex;
  var _userName = '';
  var _completeImage = '';
  var _isLoggedIn = false;
  var _isLoggedInagain = false;
  var showSpinner = false;

  @override
  void initState() {
    _getUserInfo();
    _getlogAgain();
    tappedIndex = 0;
    super.initState();
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

  Future<void> _getUserInfo() async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        setState(() {
          showSpinner = true;
        });
        var res = await CallApi().getWithToken('edit_profile');
        var body = json.decode(res.body);
        var theData = body['data'];
        setState(() {
          showSpinner = false;
        });
        _userName = theData['name'];
        _completeImage = theData['completeImage'];
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
                        builder: (context) => CustomDrawer(),
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

  Future<void> _getlogAgain() async {
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    var user = localStorage.getString('user');
    if (user != null) {
      setState(() {
        _isLoggedInagain = true;
      });
    }
  }

  Future<void> _getLoginInfo() async {
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    var user = localStorage.getString('user');
    if (user != null) {
      setState(() {
        _isLoggedIn = true;
      });
    }
    Navigator.pop(context);
    Navigator.push(
        context,
        MaterialPageRoute(
            builder: (context) => _isLoggedIn ? FullProfile() : SignIn()));
  }

  List items = [
    'Home',
    'Map',
    'Appointment',
    'Notification',
    'Privacy Policies',
    'FAQ',
    'Logout',
  ];
  List itemss = [
    'Home',
    'Map',
    'Appointment',
    'Notification',
    'Privacy Policies',
    'FAQ',
    'Login',
  ];

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: ModalProgressHUD(
        inAsyncCall: showSpinner,
        child: Container(
          width: MediaQuery.of(context).size.width,
          child: Drawer(
            child: Column(
              children: [
                Expanded(
                  flex: 1,
                  child: Container(
                    decoration: BoxDecoration(
                      color: Theme.of(context).primaryColor,
                      borderRadius:
                          BorderRadius.vertical(bottom: Radius.circular(50.0)),
                    ),
                    child: Stack(
                      children: [
                        Positioned(
                          left: 10.0,
                          top: 10.0,
                          child: IconButton(
                            icon: Icon(
                              Icons.chevron_left,
                              color: Colors.white,
                              size: 22.0,
                            ),
                            onPressed: () {
                              Navigator.pop(context);
                            },
                          ),
                        ),
                        Center(
                          child: Column(
                            children: [
                              SizedBox(
                                  height: MediaQuery.of(context).size.height *
                                      0.05),
                              Container(
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  color: Colors.white,
                                  border: Border.all(
                                    color: Colors.white,
                                    width: 3.0,
                                  ),
                                ),
                                child: GestureDetector(
                                  onTap: () {
                                    _getLoginInfo();
                                  },
                                  child: ClipRRect(
                                    borderRadius: BorderRadius.all(
                                      Radius.circular(50.0),
                                    ),
                                    child: Image(
                                      image: _isLoggedInagain == false
                                          ? AssetImage(
                                              'assets/icons/profile_picture.png')
                                          : NetworkImage(_completeImage),
                                      fit: BoxFit.fill,
                                      width: 100.0,
                                      height: 100.0,
                                    ),
                                  ),
                                ),
                              ),
                              SizedBox(
                                  height: MediaQuery.of(context).size.height *
                                      0.03),
                              Text(
                                _isLoggedInagain == false
                                    ? 'Anas Bawazir'
                                    : _userName,
                                style: TextStyle(
                                  fontFamily: 'FivoSansMedium',
                                  fontSize: 18,
                                  color: Colors.white,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
                Expanded(
                  flex: 2,
                  child: ListView.builder(
                    shrinkWrap: true,
                    itemCount: items.length,
                    itemBuilder: (context, index) {
                      return ListTile(
                        title: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          crossAxisAlignment: CrossAxisAlignment.center,
                          children: <Widget>[
                            Text(
                              _isLoggedInagain == true
                                  ? items[index]
                                  : itemss[index],
                              textAlign: TextAlign.center,
                              style: TextStyle(
                                color: tappedIndex == index
                                    ? Theme.of(context).primaryColor
                                    : darkBlue,
                                fontWeight: FontWeight.w400,
                              ),
                            ),
                          ],
                        ),
                        onTap: () {
                          setState(
                            () {
                              Navigator.pop(context);
                              if (items[index] == 'Home') {
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => HomePage()));
                              }
                              if (items[index] == 'Map') {
                                Navigator.push(context,
                                    MaterialPageRoute(builder: (_) => Map()));
                              }
                              if (items[index] == 'Appointment') {
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => AllAppointment()));
                              }
                              if (items[index] == 'Notification') {
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => Notifications()));
                              }
                              if (items[index] == 'Privacy Policies') {
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => PrivacyPolicy()));
                              }
                              if (items[index] == 'Logout') {
                                CallApi().logout();
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => SignIn()));
                              }
                              if (items[index] == 'Login') {
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => SignIn()));
                              }
                              tappedIndex = index;
                            },
                          );
                        },
                      );
                    },
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
