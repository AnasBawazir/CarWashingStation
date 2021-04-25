import 'dart:convert';
import 'package:connectivity/connectivity.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/models/employee_profile_skills.dart';
import 'package:Magasil/models/employee_review.dart';
import 'package:Magasil/screens/home/home_page.dart';
import 'package:smooth_star_rating/smooth_star_rating.dart';
import 'custom_drawer.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);
const ratingStar = Color(0xFFFECD03);

class EmployeeProfile extends StatefulWidget {
  final int specialistId;
  EmployeeProfile({Key key, this.specialistId}) : super(key: key);
  @override
  _EmployeeProfileState createState() => _EmployeeProfileState();
}

class _EmployeeProfileState extends State<EmployeeProfile>
    with SingleTickerProviderStateMixin {
  TabController _tabController;
  var showSnipper = false;
  var image = '';
  var _previousID;
  var name = '';
  var description = '';
  var skills = [];
  var experience = '0';
  List<int> skillLength = [];

  List<Review> r = List<Review>();

  List<Skill> s = List<Skill>();

  List<String> allImages = [];

  bool skilldatavisible = false;
  bool skilldatanotvisible = true;
  bool checkConnectivity;

  @override
  void initState() {
    super.initState();
    checkConnection();
    _previousID = widget.specialistId;
    _tabController = TabController(length: 3, vsync: this);
    _tabController.addListener(_handleTabSelection);
    _getProfile(_previousID);
    _getReview();
  }

  void _handleTabSelection() {
    setState(() {});
  }

  Future<void> checkConnection() async {
    Connectivity().onConnectivityChanged.listen((ConnectivityResult result) {
      if (result == ConnectivityResult.mobile ||
          result == ConnectivityResult.wifi) {
        setState(() {
          checkConnectivity = true;
        });
      } else {
        {
          setState(() {
            checkConnectivity = false;
          });
        }
      }
    });
  }

  Future<void> _getProfile(_previousID) async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().getWithToken('single_coworker/$_previousID');
    var body = json.decode(res.body);
    var theData = body['data'];
    // print('thedata is = $theData');
    setState(() {
      showSnipper = false;
    });
    image = theData['completeImage'];
    name = theData['name'];
    description = theData['description'];
    experience = theData['experience'].toString();
    skills = theData['skills'];
    for (int i = 0; i < skills.length; i++) {
      Map<String, dynamic> map = skills[i];
      s.add(Skill.fromJson(map));
    }

    if (skills.length > 0) {
      skilldatavisible = true;
      skilldatanotvisible = false;
    } else {
      skilldatavisible = false;
      skilldatanotvisible = true;
    }
    skillLength = [];
    for (int j = 0; j < skills.length; j++) {
      skillLength.add(j);
    }

    var gallaryImages;
    gallaryImages = theData['images'];
    for (int i = 0; i < gallaryImages.length; i++) {
      allImages.add(gallaryImages[i]['image']);
    }
  }

  Future<Review> _getReview() async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().getWithToken('single_coworker/$_previousID');
    var body = json.decode(res.body);
    var theData = body['data'];
    var reviewis = theData['review'];
    for (int i = 0; i < reviewis.length; i++) {
      Map<String, dynamic> map = reviewis[i];
      r.add(Review.fromjson(map));
    }
    setState(() {
      showSnipper = false;
    });
  }

  Future<void> _getData() async {
    await _getProfile(_previousID);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: PreferredSize(
        preferredSize:
            Size.fromHeight(MediaQuery.of(context).size.height / 2.9), //3.5
        child: ModalProgressHUD(
          inAsyncCall: showSnipper,
          child: SafeArea(
            child: CustomAppBar(
              tabController: _tabController,
              clipRRect: ClipRRect(
                borderRadius: BorderRadius.vertical(
                  bottom: Radius.circular(50.0),
                ),
                child: Image.network(
                  image,
                  fit: BoxFit.fill,
                  height: MediaQuery.of(context).size.height / 3.5, //4.5
                  width: MediaQuery.of(context).size.width,
                  alignment: Alignment.topCenter,
                ),
              ),
            ),
          ),
        ),
      ),
      body: RefreshIndicator(
        onRefresh: _getData,
        child: ModalProgressHUD(
            inAsyncCall: showSnipper,
            child: TabBarView(
              children: [
                //first tab
                Container(
                  child: Padding(
                    padding: const EdgeInsets.all(15.0),
                    child: Container(
                      height: MediaQuery.of(context).size.height / 1.8,
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.start,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            '$name',
                            style: TextStyle(
                              color: darkBlue,
                              fontSize: 22,
                              fontFamily: 'FivoSansMedium',
                            ),
                          ),
                          Text(
                            '$description',
                            style: TextStyle(
                              color: darkBlue,
                              fontSize: 14,
                              fontFamily: 'FivoSansRegular',
                            ),
                          ),
                          SizedBox(
                            height: 20.0,
                          ),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'SKILLS',
                                style: TextStyle(
                                  color: darkBlue,
                                  fontSize: 15,
                                  fontFamily: 'FivoSansMedium',
                                ),
                              ),
                              Visibility(
                                visible: skilldatavisible,
                                child: Wrap(
                                    children: skillLength
                                        .map((i) => Container(
                                              margin: EdgeInsets.all(7.0),
                                              padding: EdgeInsets.all(7.0),
                                              decoration: BoxDecoration(
                                                // color: Colors.red,
                                                border: Border.all(
                                                    color: extraDarkBlue),
                                                borderRadius: BorderRadius.all(
                                                  Radius.circular(5.0),
                                                ),
                                              ),
                                              child: Text(
                                                s[i].name,
                                                style: TextStyle(
                                                  color: darkBlue,
                                                  fontSize: 15,
                                                  fontFamily: 'FivoSansMedium',
                                                ),
                                              ),
                                            ))
                                        .toList()),
                              ),
                              Visibility(
                                visible: skilldatanotvisible,
                                child: Container(
                                  height: 100.0,
                                  width: MediaQuery.of(context).size.width,
                                  child: Center(
                                    child: Text(
                                      "No Skills Found",
                                      style: TextStyle(
                                        color: extraDarkBlue,
                                        fontSize: 16,
                                        fontFamily: 'FivoSansMedium',
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                          SizedBox(
                            height: 20.0,
                          ),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'EXPERIENCE',
                                style: TextStyle(
                                  color: darkBlue,
                                  fontSize: 15,
                                  fontFamily: 'FivoSansMedium',
                                ),
                              ),
                              Text(
                                '$experience + Year Experience',
                                style: TextStyle(
                                  color: extraDarkBlue,
                                  fontSize: 16,
                                  fontFamily: 'FivoSansMedium',
                                ),
                              ),
                            ],
                          ),
                          SizedBox(
                            height: 20.0,
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
                //second tab
                SingleChildScrollView(
                  child: ModalProgressHUD(
                    inAsyncCall: showSnipper,
                    child: Container(
                      margin: EdgeInsets.only(top: 30.0),
                      // color: Colors.red,
                      child: r.length == 0
                          ? Container(
                              height: MediaQuery.of(context).size.height / 2,
                              child: Center(
                                  child: Text(
                                'Not found any review',
                                style: TextStyle(
                                  color: darkBlue,
                                  fontSize: 20,
                                  fontFamily: 'FivoSansMediumOblique',
                                ),
                              )))
                          : ListView.builder(
                              shrinkWrap: true,
                              physics: ClampingScrollPhysics(),
                              padding: EdgeInsets.zero,
                              scrollDirection: Axis.vertical,
                              itemCount: r.length,
                              itemBuilder: (context, index) {
                                Review review = r[index];
                                return ListTile(
                                  leading: Container(
                                      padding: EdgeInsets.all(4.0),
                                      decoration: BoxDecoration(
                                        color: Colors.white,
                                        borderRadius: BorderRadius.all(
                                          Radius.circular(5.0),
                                        ),
                                        boxShadow: [
                                          BoxShadow(
                                            color: Colors.black26,
                                            blurRadius: 5.0,
                                            spreadRadius: 0.25,
                                          )
                                        ],
                                      ),
                                      child: ClipRRect(
                                        borderRadius: BorderRadius.all(
                                          Radius.circular(5.0),
                                        ),
                                        child: Image.network(
                                          review.image,
                                          fit: BoxFit.fill,
                                          height: 50.0,
                                          width: 50.0,
                                        ),
                                      )),
                                  title: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        review.name,
                                        style: TextStyle(
                                          color: darkBlue,
                                          fontSize: 16,
                                          fontFamily: 'FivoSansMedium',
                                        ),
                                      ),
                                      SizedBox(height: 5.0),
                                      Text(
                                        review.date,
                                        style: TextStyle(
                                          color: extraDarkBlue,
                                          fontSize: 12,
                                          fontFamily: 'FivoSansRegular',
                                        ),
                                      ),
                                      SizedBox(height: 5.0),
                                      Text(
                                        review.comment,
                                        style: TextStyle(
                                          color: extraDarkBlue,
                                          fontSize: 14,
                                          fontFamily: 'FivoSansRegular',
                                        ),
                                      ),
                                      SizedBox(height: 5.0),
                                      Align(
                                        alignment: Alignment.topLeft,
                                        child: SmoothStarRating(
                                          borderColor: ratingStar,
                                          color: ratingStar,
                                          size: 15,
                                          defaultIconData: Icons.star_border,
                                          rating: review.rating.toDouble(),
                                          spacing: 1.0,
                                          allowHalfRating: true,
                                          isReadOnly: true,
                                        ),
                                      ),
                                      Divider(
                                        height: 10.0,
                                        color: extraDarkBlue.withOpacity(0.5),
                                      )
                                    ],
                                  ),
                                );
                              },
                            ),
                    ),
                  ),
                ),
                //third tab
                Container(
                  margin: EdgeInsets.all(15),
                  child: allImages.length == 0
                      ? Container(
                          child: Center(
                              child: Text(
                            'Not found any image',
                            style: TextStyle(
                              fontSize: 20.0,
                              color: darkBlue,
                              fontFamily: 'FivoSansMediumOblique',
                            ),
                          )),
                        )
                      : GridView.builder(
                          gridDelegate:
                              SliverGridDelegateWithFixedCrossAxisCount(
                            crossAxisCount: 3,
                            crossAxisSpacing: 10.0,
                            mainAxisSpacing: 10.0,
                          ),
                          itemCount: allImages.length,
                          itemBuilder: (BuildContext context, int index) {
                            return Container(
                              decoration: BoxDecoration(
                                  border: Border.all(
                                      color: Colors.white, width: 2.0),
                                  color: Colors.white,
                                  boxShadow: [
                                    BoxShadow(
                                        color: Colors.black26,
                                        spreadRadius: 0.5,
                                        blurRadius: 2,
                                        offset: Offset(0.5, 0.5))
                                  ]),
                              child: Image.network(
                                '${allImages[index]}',
                                fit: BoxFit.fitWidth,
                                alignment: Alignment.center,
                              ),
                            );
                          },
                        ),
                ),
              ],
              controller: _tabController,
            )),
      ),
    );
  }
}

class customWrap extends StatelessWidget {
  customWrap({
    Key key,
    @required this.skillLength,
  }) : super(key: key);

  int i = 0;
  final skillLength;
  // List<Skill> s = List<Skill>();

  @override
  Widget build(BuildContext context) {
    if (skillLength != null) {
      for (i = 0; i < skillLength; i++) {
        return Container(
          margin: EdgeInsets.all(7.0),
          padding: EdgeInsets.all(7.0),
          decoration: BoxDecoration(
            // color: Colors.red,
            border: Border.all(color: extraDarkBlue),
            borderRadius: BorderRadius.all(
              Radius.circular(5.0),
            ),
          ),
          child: Text(
            'Not Available $i',
            style: TextStyle(
              color: darkBlue,
              fontSize: 15,
              fontFamily: 'FivoSansMedium',
            ),
          ),
        );
      }
    } else {
      return Text('Data not available');
    }
  }
}

class CustomAppBar extends StatelessWidget {
  const CustomAppBar(
      {Key key,
      @required TabController tabController,
      this.height,
      this.clipRRect})
      : _tabController = tabController,
        super(key: key);

  final TabController _tabController;
  final double height;
  final Widget clipRRect;

  @override
  Widget build(BuildContext context) {
    return Container(
      child: AppBar(
        flexibleSpace: clipRRect,
        elevation: 0,
        backgroundColor: Theme.of(context).primaryColor,
        shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.vertical(bottom: Radius.circular(50.0))),
        leading: IconButton(
          iconSize: 25.0,
          icon: Icon(Icons.chevron_left),
          onPressed: () {
            Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => HomePage(),
                ));
          },
        ),
        actions: [
          IconButton(
            icon: Icon(
              FontAwesomeIcons.bars,
              size: 22,
            ),
            onPressed: () {
              Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => CustomDrawer(),
                  ));
            },
          ),
        ],
        bottom: TabBar(
          labelStyle: TextStyle(
            color: Theme.of(context).primaryColor,
            fontSize: 18,
            fontFamily: 'FivoSansMedium',
          ),
          unselectedLabelStyle: TextStyle(
            color: Color(0xFF91B4D8),
            fontSize: 18,
            fontFamily: 'FivoSansMedium',
          ),
          indicatorColor: Colors.white,
          tabs: [
            Tab(
              child: Text(
                'Profile',
              ),
            ),
            Tab(
              child: Text(
                'Review',
              ),
            ),
            Tab(
              child: Text(
                'Portfolio',
              ),
            ),
          ],
          controller: _tabController,
        ),
      ),
    );
  }
}
