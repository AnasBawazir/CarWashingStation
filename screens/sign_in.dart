import 'dart:convert';
import 'package:connectivity/connectivity.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:flutter_svg/svg.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:onesignal_flutter/onesignal_flutter.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/screens/home/home_page.dart';
import 'package:Magasil/screens/payment.dart';
import 'forgot_password.dart';
import 'otp_screen.dart';
import 'sign_up.dart';

const darkBlue = Color(0xFF265E9E);
const containerShadow = Color(0xFF91B4D8);
const extraDarkBlue = Color(0xFF91B4D8);

class SignIn extends StatefulWidget {
  @override
  _SignInState createState() => _SignInState();
}

class _SignInState extends State<SignIn> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  FocusNode email = FocusNode();
  FocusNode password = FocusNode();
  var showSnipper = false;
  var playerIddd;
  final _formKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    getDeviceToken();
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

  getDeviceToken() async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        // Internet Present Case
        OneSignal.shared
            .setInFocusDisplayType(OSNotificationDisplayType.notification);

        await OneSignal.shared
            .promptUserForPushNotificationPermission(fallbackToSettings: true);

        var status = await OneSignal.shared.getPermissionSubscriptionState();

        var playerId = status.subscriptionStatus.userId;
        playerIddd = playerId;
        print('device token is $playerIddd');
        SharedPreferences localStorage = await SharedPreferences.getInstance();
        localStorage.setString('device_token', playerId);
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
                        builder: (context) => SignIn(),
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

  void _login(data) async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        setState(() {
          showSnipper = true;
        });
        SharedPreferences localStorage = await SharedPreferences.getInstance();
        var isFrom = localStorage.getString('isFrom');
        var navigate;

        isFrom == 'BookAppointment'
            ? navigate = Payment()
            : navigate = HomePage();
        var deviceToken = playerIddd;
        var checkDeviceToken = localStorage.getBool('deviceToken');
        if (checkDeviceToken == true) {
          data['device_token'] = deviceToken;
        }
        var res;
        var body;
        var resData;
        var userId;
        try {
          res = await CallApi().postData(data, 'login');
          body = json.decode(res.body);
          resData = body['data'];
          if (body['success'] == true) {
            if (body['data']['is_verified'] == 1) {
              _emailController.text = '';
              _passwordController.text = '';
              SharedPreferences localStorage =
                  await SharedPreferences.getInstance();
              localStorage.setString('token', resData['token']);
              localStorage.setString('user', json.encode(resData));
              var abc = localStorage.getString('token');
              Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(
                    builder: (context) => navigate,
                  ));
            } else {
              showDialog(
                builder: (context) => AlertDialog(
                  title: Text('Login Error'),
                  content: Text('Please verify your account'),
                  actions: <Widget>[
                    FlatButton(
                      onPressed: () async {
                        _emailController.text = '';
                        _passwordController.text = '';
                        SharedPreferences localStorage =
                            await SharedPreferences.getInstance();
                        localStorage.setString('token', resData['token']);
                        localStorage.setString('user', json.encode(resData));
                        var abc = localStorage.getString('token');
                        userId = body['data']['id'];
                        Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => OTP(
                                userIdOfOtp: userId,
                              ),
                            ));
                      },
                      child: Text('OK'),
                    )
                  ],
                ),
                context: context,
              );
            }
          } else {
            print(body['message']);
            showDialog(
              builder: (context) => AlertDialog(
                title: Text('Login Error'),
                content: Text(body['message'].toString()),
                actions: <Widget>[
                  FlatButton(
                    onPressed: () {
//                Navigator.popAndPushNamed(context, Login.route);
                      Navigator.pop(context);
                    },
                    child: Text('Try Again'),
                  )
                ],
              ),
              context: context,
            );
          }
        } catch (e) {
          showDialog(
            builder: (context) => AlertDialog(
              title: Text('Login Error'),
              content: Text(e.toString()),
              actions: <Widget>[
                FlatButton(
                  onPressed: () {
                    Navigator.pop(context);
                  },
                  child: Text('Try Again'),
                )
              ],
            ),
            context: context,
          );
        }
        setState(() {
          showSnipper = false;
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
                  Navigator.pushReplacement(
                      context,
                      MaterialPageRoute(
                        builder: (context) => SignIn(),
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

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Scaffold(
        body: ModalProgressHUD(
          inAsyncCall: showSnipper,
          child: SingleChildScrollView(
            child: GestureDetector(
              onTap: () {
                FocusScopeNode currentFocus = FocusScope.of(context);
                if (!currentFocus.hasPrimaryFocus) {
                  currentFocus.unfocus();
                }
              },
              child: Container(
                height: MediaQuery.of(context).size.height,
                width: MediaQuery.of(context).size.width,
                color: Theme.of(context).primaryColor,
                child: Column(
                  children: [
                    Container(
                      height: MediaQuery.of(context).size.height / 2.5,
                      child: Image(
                        alignment: Alignment.center,
                        height: 90.0,
                        width: 252.0,
                        image: AssetImage('assets/icons/Magasilicon.png'),
                      ),
                    ),
                    Expanded(
                      child: Container(
                        height: double.infinity,
                        width: MediaQuery.of(context).size.width,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.only(
                              topRight: Radius.circular(45.0)),
                          color: Colors.white,
                        ),
                        child: Padding(
                          padding: const EdgeInsets.all(8.0),
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: <Widget>[
                              Text(
                                'Signin',
                                style: TextStyle(
                                  color: darkBlue,
                                  fontSize: 20.0,
                                  fontFamily: 'Nadillas',
                                ),
                              ),
                              Container(
                                margin: EdgeInsets.symmetric(horizontal: 10.0),
                                height:
                                    MediaQuery.of(context).size.height / 3.5,
                                child: Column(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceEvenly,
                                  children: [
                                    Container(
                                      decoration: BoxDecoration(
                                          color: Colors.white,
                                          borderRadius: BorderRadius.all(
                                              Radius.circular(35.0)),
                                          boxShadow: [
                                            BoxShadow(
                                              color: containerShadow,
                                              blurRadius: 2,
                                              offset: Offset(0, 0),
                                              spreadRadius: 1,
                                            )
                                          ]),
                                      child: TextFormField(
                                        controller: _emailController,
                                        focusNode: email,
                                        onFieldSubmitted: (a) {
                                          email.unfocus();
                                          FocusScope.of(context)
                                              .requestFocus(password);
                                        },
                                        validator: (value) {
                                          Pattern pattern =
                                              r'^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$';
                                          RegExp regex = new RegExp(pattern);
                                          // Null check
                                          if (value.isEmpty) {
                                            return 'please enter your email';
                                          }
                                          // Valid email formatting check
                                          else if (!regex.hasMatch(value)) {
                                            return 'Enter valid email address';
                                          }
                                          // success condition
                                          return null;
                                        },
                                        enableSuggestions: false,
                                        keyboardType:
                                            TextInputType.visiblePassword,
                                        decoration: InputDecoration(
                                          contentPadding: EdgeInsets.all(15),
                                          border: InputBorder.none,
                                          suffixIcon: SvgPicture.asset(
                                            'assets/icons/usericon.svg',
                                            fit: BoxFit.scaleDown,
                                          ),
                                          hintText: 'E-mail Address',
                                          hintStyle: TextStyle(
                                            color: darkBlue,
                                            fontSize: 16,
                                            fontFamily: 'FivoSansMedium',
                                          ),
                                        ),
                                        style: TextStyle(
                                          color: darkBlue,
                                          fontSize: 16,
                                          fontFamily: 'FivoSansMedium',
                                        ),
                                      ),
                                    ),
                                    Container(
                                      decoration: BoxDecoration(
                                          color: Colors.white,
                                          borderRadius: BorderRadius.all(
                                              Radius.circular(35.0)),
                                          boxShadow: [
                                            BoxShadow(
                                              color: containerShadow,
                                              blurRadius: 2,
                                              offset: Offset(0, 0),
                                              spreadRadius: 1,
                                            )
                                          ]),
                                      child: TextFormField(
                                        controller: _passwordController,
                                        validator: (value) {
                                          if (value.isEmpty) {
                                            return "Please Enter Password";
                                          } else if (value.length < 6) {
                                            return "Password must be atleast 6 characters long";
                                          } else {
                                            return null;
                                          }
                                        },
                                        focusNode: password,
                                        onFieldSubmitted: (a) {
                                          password.unfocus();
                                        },
                                        obscureText: true,
                                        decoration: InputDecoration(
                                          contentPadding: EdgeInsets.all(15),
                                          border: InputBorder.none,
                                          suffixIcon: SvgPicture.asset(
                                            'assets/icons/lockicon.svg',
                                            fit: BoxFit.scaleDown,
                                          ),
                                          hintText: 'Password',
                                          hintStyle: TextStyle(
                                            color: darkBlue,
                                            fontSize: 16,
                                            fontFamily: 'FivoSansMedium',
                                          ),
                                        ),
                                        style: TextStyle(
                                          color: darkBlue,
                                          fontSize: 16,
                                          fontFamily: 'FivoSansMedium',
                                        ),
                                      ),
                                    ),
                                    Align(
                                      alignment: Alignment.centerRight,
                                      child: FlatButton(
                                        onPressed: () {
                                          Navigator.push(
                                              context,
                                              MaterialPageRoute(
                                                builder: (context) =>
                                                    ForgotPassword(),
                                              ));
                                        },
                                        child: Text(
                                          'Forgot Password?',
                                          style: TextStyle(
                                            color: extraDarkBlue,
                                            fontFamily: 'FivoSansRegular',
                                            fontSize: 15,
                                          ),
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                              Column(
                                children: [
                                  Container(
                                    margin: EdgeInsets.all(10.0),
                                    width: MediaQuery.of(context).size.width,
                                    height: 50.0,
                                    decoration: BoxDecoration(
                                      borderRadius: BorderRadius.all(
                                          Radius.circular(35.0)),
                                    ),
                                    child: RaisedButton(
                                      onPressed: () {
                                        if (_formKey.currentState.validate()) {
                                          final body = {
                                            "email": _emailController.text,
                                            "password":
                                                _passwordController.text,
                                            "provider": "LOCAL",
                                          };
                                          _login(body);
                                        }
                                      },
                                      elevation: 2.0,
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.all(
                                          Radius.circular(35.0),
                                        ),
                                      ),
                                      color: Theme.of(context).primaryColor,
                                      child: Text(
                                        'Signin',
                                        style: TextStyle(
                                          color: Colors.white,
                                          fontSize: 18,
                                          fontFamily: 'FivoSansRegular',
                                        ),
                                      ),
                                    ),
                                  ),
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: [
                                      FittedBox(
                                        child: Text(
                                          'If you are new user?',
                                          style: TextStyle(
                                              color: darkBlue,
                                              fontSize: 18,
                                              fontFamily: 'FivoSansRegular'),
                                        ),
                                      ),
                                      Container(
                                        height: 35,
                                        width: 80,
                                        child: IconButton(
                                          icon: FittedBox(
                                            fit: BoxFit.contain,
                                            child: Text(
                                              'Sign up',
                                              style: TextStyle(
                                                color: Color(0xFF004695),
                                                fontSize: 25,
                                                fontFamily: 'FivoSansMedium',
                                              ),
                                            ),
                                          ),
                                          onPressed: () {
                                            Navigator.push(
                                                context,
                                                MaterialPageRoute(
                                                    builder: (context) =>
                                                        SignUp()));
                                          },
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
