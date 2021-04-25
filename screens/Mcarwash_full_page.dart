import 'dart:convert';
import 'package:Magasil/models/carwash.dart';
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

class MCarWashFull extends StatefulWidget {
  @override
  _MCarWashFullState createState() => _MCarWashFullState();
}

class _MCarWashFullState extends State<MCarWashFull> {
  CarWash Mcarwashs;
  List<String> Mlist = [
    "summer",
    "ramadan",
    "crystal clear",
    'soft',
    'more',
    'ui'
  ];
  var showSnipper = false;


  @override
  void initState() {
    super.initState();
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
          'Mobile Car Wash',
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
            itemCount: Mlist.length,
            itemBuilder: (context, index) {
              var numPic=index+5;

              return Container(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    GestureDetector(
                      onTap: () {
                        Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (context) => EmployeeProfile(
                                //specialistId: passId,
                              ),
                            ));
                      },
                      child: ClipRRect(
                        borderRadius: BorderRadius.all(Radius.circular(10.0)),
                        child: Image.asset(
                          'assets/images/portfolio$numPic.png',
                          fit: BoxFit.fill,
                          width: 163,
                          height: 217,
                        )
                      ),
                    ),
                    SizedBox(height: 5.0),
                    Text(
                      Mlist[index],
                      style: TextStyle(
                        color: darkBlue,
                        fontSize: 18,
                        fontFamily: 'FivoSansMedium',
                      ),
                    ),
                    SizedBox(height: 4.0),
                    Text(
                      'Great Clean',
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
                        //rating: specialist.rating,
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
