import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/models/coworkerM.dart';
import 'package:Magasil/models/employee_profile_skills.dart';
import 'package:Magasil/screens/custom_drawer.dart';
import 'package:Magasil/screens/employee_profile.dart';
import 'package:connectivity/connectivity.dart';
import 'package:smooth_star_rating/smooth_star_rating.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);
const ratingStar = Color(0xFFFECD03);

class SpecialistFull extends StatefulWidget {
  @override
  _SpecialistFullState createState() => _SpecialistFullState();
}

class _SpecialistFullState extends State<SpecialistFull> {
  var showSnipper = false;
  var passId;
  List<CoworkerM> sp = new List<CoworkerM>();
  CoworkerM s = new CoworkerM();
  List<Skill> sk = List<Skill>();

  @override
  void initState() {
    _getDataSpecialistFull();
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

  Future<void> _getDataSpecialistFull() async {
    check().then((intenet) async {
      if (intenet != null && intenet) {
        setState(() {
          showSnipper = true;
        });
        var res = await CallApi().getWithToken('all_coworker');
        var body = json.decode(res.body);
        var success = body['success'];
        if (success == true) {
        } else {
          setState(() {
            showSnipper = false;
            showDialog(
                builder: (context) => AlertDialog(
                      title: Text('Error'),
                      content: Text('Something went wrong'),
                      actions: <Widget>[
                        FlatButton(
                          onPressed: () {
                            Navigator.pop(context);
                          },
                          child: Text('Reload'),
                        )
                      ],
                    ),
                context: context);
          });
        }
        var theData = body['data'];
        sp = [];
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
                        builder: (context) => SpecialistFull(),
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
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.white,
        leading: IconButton(
          icon: Icon(
            Icons.chevron_left,
            color: darkBlue,
          ),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
        title: Text(
          'Specialist',
          style: TextStyle(
            color: darkBlue,
            fontFamily: 'FivoSansMedium',
            fontSize: 18,
          ),
        ),
        centerTitle: true,
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
                color: darkBlue,
              ),
            ),
          ),
        ],
        elevation: 0,
      ),
      body: ModalProgressHUD(
        inAsyncCall: showSnipper,
        child: Container(
          // color: Colors.red,
          child: GridView.builder(
            primary: false,
            padding: const EdgeInsets.all(20),
            scrollDirection: Axis.vertical,
            itemCount: sp.length,
            itemBuilder: (context, index) {
              CoworkerM specialist = sp[index];
              Skill skill = sk[index];
              return Container(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    GestureDetector(
                      onTap: () {
                        passId = sp[index].id;
                        Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => EmployeeProfile(
                                specialistId: passId,
                              ),
                            ));
                      },
                      child: ClipRRect(
                        borderRadius: BorderRadius.all(Radius.circular(10.0)),
                        child: Image(
                          width: 163,
                          height: 217,
                          fit: BoxFit.fill,
                          image: NetworkImage('${specialist.image}'),
                        ),
                      ),
                    ),
                    SizedBox(height: 5.0),
                    Text(
                      specialist.name,
                      style: TextStyle(
                        color: darkBlue,
                        fontSize: 18,
                        fontFamily: 'FivoSansMedium',
                      ),
                    ),
                    SizedBox(height: 4.0),
                    Text(
                      skill.name,
                      style: TextStyle(
                        color: extraDarkBlue,
                        fontSize: 14,
                        fontFamily: 'FivoSansMedium',
                      ),
                    ),
                    SizedBox(height: 4.0),
                    Align(
                      alignment: Alignment.topLeft,
                      child: SmoothStarRating(
                        borderColor: ratingStar,
                        color: ratingStar,
                        size: 15,
                        defaultIconData: Icons.star_border,
                        rating: specialist.rating,
                        spacing: 1.0,
                        allowHalfRating: true,
                        isReadOnly: true,
                      ),
                    ),
                  ],
                ),
              );
            },
            gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisSpacing: 10,
              mainAxisSpacing: 10,
              crossAxisCount: 2,
              childAspectRatio: MediaQuery.of(context).size.width *
                  0.4 /
                  MediaQuery.of(context).size.height *
                  3,
              // childAspectRatio: 163 / 300,     original
            ),
          ),
        ),
      ),
    );
  }
}
