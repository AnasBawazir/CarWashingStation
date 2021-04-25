import 'dart:convert';
import 'dart:io' as Io;
import 'dart:io';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/screens/custom_drawer.dart';
import 'package:image_picker/image_picker.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);

class FullProfile extends StatefulWidget {
  @override
  _FullProfileState createState() => _FullProfileState();
}

class _FullProfileState extends State<FullProfile> {
  final _oldPasswordController = TextEditingController();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  File _image;
  var name = '';
  var phone;
  var image;
  var showSnipper = false;
  var image64;
  var imageData;
  var changeName;
  var apiName;
  var apiPassword;
  var completeImage = '';
  var nameChange = 0;
  var proPicChange = 0;
  var passwordCheck = 0;

  @override
  void initState() {
    _getProfileInfo();
    super.initState();
  }

  Future<void> updateImage() async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().postDataWithToken(imageData, 'update_image');
    var body = json.decode(res.body);
    if (body['success'] == true) {
      Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (context) => CustomDrawer(),
          ));
    } else {
      showDialog(
        builder: (context) => AlertDialog(
          title: Text('Image error'),
          content: Text(body['data'].toString()),
          actions: <Widget>[
            FlatButton(
              onPressed: () async {
                Navigator.pop(context);
              },
              child: Text('OK'),
            )
          ],
        ),
        context: context,
      );
    }
    setState(() {
      showSnipper = false;
    });
  }

  Future<void> updateName(apiName) async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().postDataWithToken(apiName, 'update_profile');
    var body = json.decode(res.body);
    if (body['success'] == true) {
      Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (context) => CustomDrawer(),
          ));
    } else {
      showDialog(
        builder: (context) => AlertDialog(
          title: Text('Name error'),
          content: Text(body['data'].toString()),
          actions: <Widget>[
            FlatButton(
              onPressed: () async {
                Navigator.pop(context);
              },
              child: Text('OK'),
            )
          ],
        ),
        context: context,
      );
    }
    setState(() {
      showSnipper = false;
    });
  }

  Future<void> updatePassword(apiPassword) async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().postDataWithToken(apiPassword, 'change_password');
    var body = json.decode(res.body);
    if (body['success'] == true) {
      Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (context) => CustomDrawer(),
          ));
    } else {
      showDialog(
        builder: (context) => AlertDialog(
          title: Text('Password Error'),
          content: Text(body['data'].toString()),
          actions: <Widget>[
            FlatButton(
              onPressed: () async {
                Navigator.pop(context);
              },
              child: Text('OK'),
            )
          ],
        ),
        context: context,
      );
    }
    setState(() {
      showSnipper = false;
    });
  }

  _imgFromCamera() async {
    image = await ImagePicker.pickImage(
        source: ImageSource.camera, imageQuality: 50);
    final bytes = Io.File(image.path).readAsBytesSync();
    String img64 = base64Encode(bytes);
    image64 = img64;
    setState(() {
      _image = image;
      imageData = {"image": "$image64"};
    });
  }

  _imgFromGallery() async {
    File image = await ImagePicker.pickImage(
        source: ImageSource.gallery, imageQuality: 50);
    final bytes = Io.File(image.path).readAsBytesSync();
    String img64 = base64Encode(bytes);
    image64 = img64;
    setState(() {
      _image = image;
      imageData = {"image": "$image64"};
    });
  }

  void _showPicker(context) {
    showModalBottomSheet(
        context: context,
        builder: (BuildContext bc) {
          return SafeArea(
            child: Container(
              child: new Wrap(
                children: <Widget>[
                  new ListTile(
                      leading: new Icon(Icons.photo_library),
                      title: new Text('Photo Library'),
                      onTap: () {
                        _imgFromGallery();
                        Navigator.of(context).pop();
                      }),
                  new ListTile(
                    leading: new Icon(Icons.photo_camera),
                    title: new Text('Camera'),
                    onTap: () {
                      _imgFromCamera();
                      Navigator.of(context).pop();
                    },
                  ),
                ],
              ),
            ),
          );
        });
  }

  Future<void> _getProfileInfo() async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().getWithToken('user');
    var body = json.decode(res.body);
    var theData = body;
    name = theData['name'];
    phone = theData['phone'];
    completeImage = theData['completeImage'];
    setState(() {
      showSnipper = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Scaffold(
        body: SafeArea(
          child: ModalProgressHUD(
            inAsyncCall: showSnipper,
            child: GestureDetector(
              onTap: () {
                FocusScopeNode currentFocus = FocusScope.of(context);
                if (!currentFocus.hasPrimaryFocus) {
                  currentFocus.unfocus();
                }
              },
              child: Stack(
                children: [
                  ListView(
                    children: [
                      Container(
                        decoration: BoxDecoration(
                          color: Theme.of(context).primaryColor,
                          borderRadius: BorderRadius.vertical(
                              bottom: Radius.circular(50.0)),
                        ),
                        child: Stack(
                          children: [
                            Positioned(
                              left: 1.0,
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
                                  SizedBox(height: 30.0),
                                  Stack(
                                    children: [
                                      Container(
                                        decoration: BoxDecoration(
                                          shape: BoxShape.circle,
                                          color: Colors.white,
                                          border: Border.all(
                                            color: Colors.white,
                                            width: 3.0,
                                          ),
                                        ),
                                        child: CircleAvatar(
                                          radius: 50,
                                          child: _image != null
                                              ? ClipRRect(
                                                  borderRadius:
                                                      BorderRadius.circular(50),
                                                  child: Image.file(
                                                    _image,
                                                    width: 100,
                                                    height: 100,
                                                    fit: BoxFit.fill,
                                                  ),
                                                )
                                              : ClipRRect(
                                                  borderRadius:
                                                      BorderRadius.circular(50),
                                                  child: Image.network(
                                                    completeImage,
                                                    width: 100,
                                                    height: 100,
                                                    fit: BoxFit.fill,
                                                  ),
                                                ),
                                        ),
                                      ),
                                      Positioned(
                                        bottom: 1.0,
                                        right: 1.0,
                                        child: Container(
                                          height: 30.0,
                                          width: 30.0,
                                          decoration: BoxDecoration(
                                            color: Colors.white,
                                            shape: BoxShape.circle,
                                          ),
                                          child: IconButton(
                                            onPressed: () {
                                              proPicChange = 1;
                                              _showPicker(context);
                                            },
                                            padding: EdgeInsets.zero,
                                            icon: Icon(
                                              Icons.camera_alt,
                                              color: darkBlue,
                                            ),
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                  SizedBox(height: 20.0),
                                  Text(
                                    name,
                                    style: TextStyle(
                                      fontFamily: 'FivoSansMedium',
                                      fontSize: 18,
                                      color: Colors.white,
                                    ),
                                  ),
                                  SizedBox(height: 30.0),
                                ],
                              ),
                            ),
                            Positioned(
                              right: 1.0,
                              child: IconButton(
                                icon: Icon(
                                  FontAwesomeIcons.bars,
                                  color: Colors.white,
                                  size: 22.0,
                                ),
                                onPressed: () {
                                  Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => CustomDrawer(),
                                      ));
                                },
                              ),
                            ),
                          ],
                        ),
                      ),
                      Container(
                        padding: EdgeInsets.all(15.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Name',
                              style: TextStyle(
                                color: extraDarkBlue,
                                fontSize: 14,
                                fontFamily: 'FivoSansRegular',
                              ),
                            ),
                            TextField(
                              controller: TextEditingController()..text = name,
                              enableSuggestions: false,
                              keyboardType: TextInputType.visiblePassword,
                              onChanged: (name) {
                                nameChange = 1;
                                changeName = name;
                              },
                              decoration: InputDecoration(
                                hintText: 'Anas Bawazir',
                                hintStyle: TextStyle(
                                  color: darkBlue,
                                  fontSize: 18,
                                  fontFamily: 'FivoSansMedium',
                                  letterSpacing: 0.2,
                                ),
                              ),
                              style: TextStyle(
                                color: darkBlue,
                                fontSize: 18,
                                fontFamily: 'FivoSansMedium',
                                letterSpacing: 0.2,
                              ),
                            ),
                            SizedBox(height: 20.0),
                            Text(
                              'Phone',
                              style: TextStyle(
                                color: extraDarkBlue,
                                fontSize: 14,
                                fontFamily: 'FivoSansRegular',
                              ),
                            ),
                            TextField(
                              controller: TextEditingController()..text = phone,
                              readOnly: true,
                              decoration: InputDecoration(
                                suffixIcon: Container(
                                  margin: EdgeInsets.all(10.0),
                                  height: 22,
                                  width: 61,
                                  decoration: BoxDecoration(
                                    color: Colors.green,
                                    borderRadius:
                                        BorderRadius.all(Radius.circular(30.0)),
                                  ),
                                  child: Center(
                                    child: Text(
                                      'Verified',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 12,
                                        fontFamily: 'Rubik',
                                        letterSpacing: 0.3,
                                      ),
                                    ),
                                  ),
                                ),
                                hintText: '+1 903 698 8574',
                                hintStyle: TextStyle(
                                  color: darkBlue,
                                  fontSize: 18,
                                  fontFamily: 'FivoSansMedium',
                                  letterSpacing: 0.2,
                                ),
                              ),
                              style: TextStyle(
                                color: darkBlue,
                                fontSize: 18,
                                fontFamily: 'FivoSansMedium',
                                letterSpacing: 0.2,
                              ),
                            ),
                            SizedBox(height: 20.0),
                            ExpansionTile(
                              title: Text(
                                'Change Password',
                                style: TextStyle(
                                  color: darkBlue,
                                  fontSize: 18.0,
                                  fontFamily: 'FivoSansMedium',
                                ),
                              ),
                              children: [
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      'Current Password',
                                      style: TextStyle(
                                        color: extraDarkBlue,
                                        fontSize: 14,
                                        fontFamily: 'FivoSansRegular',
                                      ),
                                    ),
                                    TextFormField(
                                      onTap: () {
                                        passwordCheck = 1;
                                      },
                                      controller: _oldPasswordController,
                                      validator: (value) {
                                        if (value.isEmpty) {
                                          return "Please Enter Password";
                                        } else if (value.length < 6) {
                                          return "Password must be atleast 6 characters long";
                                        } else {
                                          return null;
                                        }
                                      },
                                      obscureText: true,
                                      decoration: InputDecoration(
                                        hintText: '*******',
                                        hintStyle: TextStyle(
                                          color: darkBlue,
                                          fontSize: 18,
                                          fontFamily: 'FivoSansMedium',
                                          letterSpacing: 0.2,
                                        ),
                                      ),
                                      style: TextStyle(
                                        color: darkBlue,
                                        fontSize: 18,
                                        fontFamily: 'FivoSansMedium',
                                        letterSpacing: 0.2,
                                      ),
                                    ),
                                    SizedBox(height: 20.0),
                                    Text(
                                      'New Password',
                                      style: TextStyle(
                                        color: extraDarkBlue,
                                        fontSize: 14,
                                        fontFamily: 'FivoSansRegular',
                                      ),
                                    ),
                                    TextFormField(
                                      controller: _newPasswordController,
                                      validator: (value) {
                                        if (value.isEmpty) {
                                          return "Please Enter Password";
                                        } else if (value.length < 6) {
                                          return "Password must be atleast 6 characters long";
                                        } else {
                                          return null;
                                        }
                                      },
                                      obscureText: true,
                                      decoration: InputDecoration(
                                        hintText: '*******',
                                        hintStyle: TextStyle(
                                          color: darkBlue,
                                          fontSize: 18,
                                          fontFamily: 'FivoSansMedium',
                                          letterSpacing: 0.2,
                                        ),
                                      ),
                                      style: TextStyle(
                                        color: darkBlue,
                                        fontSize: 18,
                                        fontFamily: 'FivoSansMedium',
                                        letterSpacing: 0.2,
                                      ),
                                    ),
                                    SizedBox(height: 20.0),
                                    Text(
                                      'Confirm Password',
                                      style: TextStyle(
                                        color: extraDarkBlue,
                                        fontSize: 14,
                                        fontFamily: 'FivoSansRegular',
                                      ),
                                    ),
                                    TextFormField(
                                      controller: _confirmPasswordController,
                                      validator: (value) {
                                        if (value.isEmpty) {
                                          return "Please Enter Password";
                                        } else if (value !=
                                            _newPasswordController.text) {
                                          return "Password must be same as above";
                                        } else {
                                          return null;
                                        }
                                      },
                                      obscureText: true,
                                      decoration: InputDecoration(
                                        hintText: '*******',
                                        hintStyle: TextStyle(
                                          color: darkBlue,
                                          fontSize: 18,
                                          fontFamily: 'FivoSansMedium',
                                          letterSpacing: 0.2,
                                        ),
                                      ),
                                      style: TextStyle(
                                        color: darkBlue,
                                        fontSize: 18,
                                        fontFamily: 'FivoSansMedium',
                                        letterSpacing: 0.2,
                                      ),
                                    ),
                                    SizedBox(height: 40.0),
                                  ],
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                  Positioned(
                    bottom: 0.10,
                    child: Container(
                      height: 50.0,
                      width: MediaQuery.of(context).size.width,
                      child: RaisedButton(
                        onPressed: () {
                          if (_formKey.currentState.validate()) {
                            if (proPicChange == 1) {
                              updateImage();
                            }
                            if (passwordCheck == 1) {
                              apiPassword = {
                                "old_password":
                                    "${_oldPasswordController.text}",
                                "password": "${_newPasswordController.text}",
                                "password_confirmation":
                                    "${_confirmPasswordController.text}"
                              };
                              updatePassword(apiPassword);
                            }
                            if (nameChange == 1) {
                              apiName = {"name": "$changeName"};
                              updateName(apiName);
                            }
                          }
                        },
                        color: Theme.of(context).primaryColor,
                        child: Text(
                          'SAVE',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 18.0,
                            fontFamily: 'FivoSansMedium',
                          ),
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
    );
  }
}
