import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/screens/sign_in.dart';

const darkBlue = Color(0xFF265E9E);
const containerShadow = Color(0xFF91B4D8);
const extraDarkBlue = Color(0xFF91B4D8);

class ForgotPassword extends StatefulWidget {
  @override
  _ForgotPasswordState createState() => _ForgotPasswordState();
}

class _ForgotPasswordState extends State<ForgotPassword> {
  final _emailController = TextEditingController();
  var showSpinner = false;
  final _formKey = GlobalKey<FormState>();
  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Scaffold(
        appBar: AppBar(
          automaticallyImplyLeading: false,
          actions: [
            IconButton(
              icon: Icon(
                Icons.chevron_left,
                size: 25,
              ),
              onPressed: () => Navigator.pop(context),
            )
          ],
          elevation: 0,
        ),
        body: SingleChildScrollView(
          child: Container(
            height: MediaQuery.of(context).size.height,
            child: ModalProgressHUD(
              inAsyncCall: showSpinner,
              child: GestureDetector(
                onTap: () {
                  FocusScopeNode currentFocus = FocusScope.of(context);
                  if (!currentFocus.hasPrimaryFocus) {
                    currentFocus.unfocus();
                  }
                },
                child: Container(
                  height: MediaQuery.of(context).size.height / 1.2,
                  width: MediaQuery.of(context).size.width,
                  color: Theme.of(context).primaryColor,
                  child: Column(
                    children: [
                      Container(
                        height: MediaQuery.of(context).size.height / 3.7,
                        child: Image(
                          alignment: Alignment.topCenter,
                          height: 90.0,
                          width: 252.0,
                          image: AssetImage('assets/icons/magasilicon.png'),
                        ),
                      ),
                      Expanded(
                        child: Container(
                          height: MediaQuery.of(context).size.height / 3,
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
                                Column(
                                  children: [
                                    Text(
                                      'Forgot Password',
                                      style: TextStyle(
                                        color: darkBlue,
                                        fontSize: 20.0,
                                        fontFamily: 'Nadillas',
                                      ),
                                    ),
                                    Padding(
                                      padding: const EdgeInsets.all(20.0),
                                      child: Text(
                                        'Please enter your email and we will send an OTP number',
                                        textAlign: TextAlign.center,
                                        style: TextStyle(
                                          color: extraDarkBlue,
                                          fontSize: 16.0,
                                          fontFamily: 'FivoSansRegular',
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                                Container(
                                  margin: EdgeInsets.all(10.0),
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
                                    keyboardType: TextInputType.visiblePassword,
                                    decoration: InputDecoration(
                                      contentPadding: EdgeInsets.all(15),
                                      border: InputBorder.none,
                                      hintText: 'Email / Mobile',
                                      hintStyle: TextStyle(
                                        color: extraDarkBlue,
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
                                  margin: EdgeInsets.all(10.0),
                                  width: MediaQuery.of(context).size.width,
                                  height: 50.0,
                                  decoration: BoxDecoration(
                                    borderRadius:
                                        BorderRadius.all(Radius.circular(35.0)),
                                  ),
                                  child: RaisedButton(
                                    onPressed: () async {
                                      if (_formKey.currentState.validate()) {
                                        final body = {
                                          "email": _emailController.text,
                                        };
                                        _forgotPassword(body);
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
                                      'Send',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 18,
                                        fontFamily: 'FivoSansRegular',
                                      ),
                                    ),
                                  ),
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
      ),
    );
  }

  void _forgotPassword(data) async {
    setState(() {
      showSpinner = true;
    });
    var res;
    var body;
    var userId;
    try {
      res = await CallApi().postData(data, 'forgot_password');
      body = json.decode(res.body);
      if (_emailController.text.isNotEmpty) {
        if (body['success'] == true) {
          setState(() {
            showSpinner = false;
          });
          userId = body['data']['id'];
          Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context) => SignIn(),
              ));
        } else {
          showDialog(
            builder: (context) => AlertDialog(
              title: Text('Error'),
              content: Text(body['data'].toString()),
              actions: <Widget>[
                FlatButton(
                  onPressed: () {
                    setState(() {
                      showSpinner = false;
                    });
                    Navigator.pop(context);
                  },
                  child: Text('Try Again'),
                )
              ],
            ),
            context: context,
          );
        }
      } else {
        showDialog(
          builder: (context) => AlertDialog(
            title: Text('Email Error'),
            content: Text('please enter valid Email'),
            actions: <Widget>[
              FlatButton(
                onPressed: () {
                  setState(() {
                    showSpinner = false;
                  });
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
          title: Text('Email/Phone Error'),
          content: Text(e.toString()),
          actions: <Widget>[
            FlatButton(
              onPressed: () {
                setState(() {
                  showSpinner = false;
                });
                Navigator.pop(context);
              },
              child: Text('Try Again'),
            )
          ],
        ),
        context: context,
      );
    }
  }
}
