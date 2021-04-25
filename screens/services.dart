import 'dart:async';
import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/models/addvalueforservice.dart';
import 'package:Magasil/models/category_wise_service_coworker.dart';
import 'package:Magasil/models/coworkerM.dart';
import 'package:Magasil/models/home_category.dart';
import 'package:Magasil/screens/book_appointment.dart';
import 'package:Magasil/screens/custom_drawer.dart';
import 'package:Magasil/screens/employee_profile.dart';
import 'package:smooth_star_rating/smooth_star_rating.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);
const ratingStar = Color(0xFFFECD03);
const containerBackground = Color(0xFFE9F0F7);

class Services extends StatefulWidget {
  final int index;
  final int categoryId;
  final int selecetedSkill;
  final int previuosSpeId;

  Services(
      {Key key,
      this.index,
      this.categoryId,
      this.selecetedSkill,
      this.previuosSpeId})
      : super(key: key);

  @override
  _ServicesState createState() => _ServicesState();
}

class _ServicesState extends State<Services> {
  var passId;
  var showSnipper = false;
  var specialistName;
  var coworkerId;
  var categoryId;
  int verified;
  int verifiedCat;
  int categoryCheck;
  var totalValue;
  var totalDuration;
  var passTheData;
  var previousSpeId;
  var previousCatId;
  var selectedCatId;
  var serviceId;
  var sendCoworkerData;
  var selectCoworkerData;
  var dataFound = false;
  var dataNotFound = true;
  var servicelength;
  var forValueInsert;

  Map<int, dynamic> checkbox;

  List<bool> checkboxBool = [];
  List<bool> selected = List<bool>();
  List<CoworkerM> cw = List<CoworkerM>();
  CoworkerM cwc = CoworkerM();

  List<Categories> ct = List<Categories>();
  Categories c = Categories();

  List<category_wise_service_coworker> cws =
      List<category_wise_service_coworker>();

  List<AddValues> av = List<AddValues>();

  @override
  void initState() {
    coworkerId = widget.previuosSpeId;
    previousCatId = widget.categoryId;
    selectedCatId = previousCatId;
    passTheData = {
      "coworker_id": '$coworkerId',
      "category_id": '$previousCatId'
    };
    servicelength = widget.selecetedSkill;
    setState(() {
      if (0 < servicelength) {
        dataFound = true;
        for (int a = 0; a < servicelength; a++) {
          checkboxBool.add(false);
        }
      }
    });
    verified = 0;
    verifiedCat = widget.index;
    selected = [false];
    specialistName = 'Select Specialist';
    totalValue = 00;
    totalDuration = 00;
    categoryId = 1;
    _getDataSpecialist();
    _getDataCategory();
    _getDataServices(passTheData);
    super.initState();
  }

  Future<void> _getDataSpecialist() async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().getWithToken('all_coworker');
    var body = json.decode(res.body);
    var success = body['success'];
    if (success == true) {
      setState(() {
        showSnipper = false;
      });
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
    cw = [];
    for (int i = 0; i < theData.length; i++) {
      Map<String, dynamic> map = theData[i];
      cw.add(CoworkerM.fromJson(map));
    }
    specialistName = cw[0].name;
    passId = cw[0].id;
  }

  Future<void> _getDataCategory() async {
    setState(() {
      showSnipper = true;
    });
    var res = await CallApi().getWithToken('category');
    var body = json.decode(res.body);
    var success = body['success'];
    if (success == true) {
      setState(() {
        showSnipper = false;
      });
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
    ct = [];
    for (int i = 0; i < theData.length; i++) {
      Map<String, dynamic> map = theData[i];
      ct.add(Categories.fromJson(map));
    }
  }

  Future<void> _getDataServices(passTheData) async {
    setState(() {
      showSnipper = true;
    });
    try {
      var res = await CallApi()
          .postData(passTheData, 'category_wise_service_coworker');
      if (res.statusCode == 200) {
        var body = json.decode(res.body);
        var theData = body['data'];
        if (body['success'] == true) {
          cws = [];
          servicelength = theData.length;
          if (theData.length != 0) {
            dataFound = true;
            for (int i = 0; i < theData.length; i++) {
              Map<String, dynamic> map = theData[i];
              cws.add(category_wise_service_coworker.fromJson(map));
            }
          }
        }
        setState(() {
          showSnipper = false;
        });
      }
    } catch (e) {
      showDialog(
        builder: (context) => AlertDialog(
          title: Text('Error'),
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
  }

  Future<void> _getData() async {
    setState(() {
      _getDataSpecialist();
      _getDataCategory();
      _getDataServices(passTheData);
      verified = 0;
      verifiedCat = 0;
      totalValue = 00;
      totalDuration = 00;
      selected = [false];
    });
  }

  servicePrice(servicePriceIndex, servicePrice) {
    servicePriceIndex == true
        ? totalValue += servicePrice
        : totalValue -= servicePrice;
  }

  serviceDuration(serviceDurationIndex, serviceDuration) {
    serviceDurationIndex == true
        ? totalDuration += serviceDuration
        : totalDuration -= serviceDuration;
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
          'Select Services',
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
        child: SafeArea(
          right: false,
          left: false,
          child: Stack(
            children: [
              CustomScrollView(
                scrollDirection: Axis.vertical,
                slivers: [
                  SliverList(
                    delegate: SliverChildListDelegate(
                      [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Padding(
                              padding:
                                  const EdgeInsets.symmetric(horizontal: 15.0),
                              child: Text(
                                'Our Specialist',
                                style: TextStyle(
                                  color: darkBlue,
                                  fontSize: 18,
                                  fontFamily: 'FivoSansMedium',
                                ),
                              ),
                            ),
                            SizedBox(height: 10.0),
                            Container(
                              margin: EdgeInsets.symmetric(horizontal: 8.0),
                              height: 120.0,
                              // color: Colors.red,
                              child: ListView.builder(
                                scrollDirection: Axis.horizontal,
                                itemCount: cw.length,
                                itemBuilder: (context, index) {
                                  CoworkerM specialist = cw[index];
                                  return GestureDetector(
                                    onTap: () {
                                      verified = index;
                                      coworkerId = specialist.id;
                                      passTheData = {
                                        'coworker_id': '$coworkerId',
                                        'category_id': '$selectedCatId'
                                      };
                                      _getDataServices(passTheData);
                                      checkboxBool.clear();
                                      av.clear();
                                      totalDuration = 0;
                                      totalValue = 0;
                                      specialistName = specialist.name;
                                      passId = specialist.id;
                                      selected = [false];
                                    },
                                    child: Stack(
                                      children: [
                                        Container(
                                          height: 100.0,
                                          width: 74.0,
                                          // color: Colors.yellow,
                                          margin: EdgeInsets.all(8.0),
                                          child: ClipRRect(
                                            borderRadius:
                                                BorderRadius.circular(10.0),
                                            child: Image(
                                              height: 74.0,
                                              width: 74.0,
                                              fit: BoxFit.fill,
                                              image: NetworkImage(
                                                  specialist.image),
                                            ),
                                          ),
                                        ),
                                        Positioned(
                                          right: 1.0,
                                          child: Container(
                                            height: 20.0,
                                            width: 20.0,
                                            decoration: BoxDecoration(
                                              color: verified == index
                                                  ? Theme.of(context)
                                                      .primaryColor
                                                  : Colors.transparent,
                                              shape: BoxShape.circle,
                                            ),
                                            child: Icon(
                                              Icons.check,
                                              color: verified == index
                                                  ? Colors.white
                                                  : Colors.transparent,
                                              size: 18.0,
                                            ),
                                          ),
                                        )
                                      ],
                                    ),
                                  );
                                },
                              ),
                            ),
                            Container(
                              alignment: Alignment.center,
                              // color: Colors.red,
                              margin: EdgeInsets.all(8.0),
                              child: InkWell(
                                child: Text(
                                  specialistName,
                                  style: TextStyle(
                                    color: darkBlue,
                                    fontSize: 18,
                                    fontFamily: 'FivoSansMedium',
                                  ),
                                ),
                                onTap: () {
                                  Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => EmployeeProfile(
                                            specialistId: passId),
                                      ));
                                },
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  SliverList(
                    delegate: SliverChildListDelegate(
                      [
                        SizedBox(height: 8.0),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 15.0),
                          child: Align(
                            alignment: Alignment.topLeft,
                            child: Text(
                              'Categories',
                              style: TextStyle(
                                color: darkBlue,
                                fontSize: 18,
                                fontFamily: 'FivoSansMedium',
                              ),
                            ),
                          ),
                        ),
                        Container(
                          margin: EdgeInsets.all(8.0),
                          height: 130.0,
                          width: MediaQuery.of(context).size.width,
                          child: ListView.builder(
                            scrollDirection: Axis.horizontal,
                            itemCount: ct.length,
                            itemBuilder: (context, index) {
                              Categories categoryicons = ct[index];
                              return GestureDetector(
                                onTap: () {
                                  setState(() {
                                    verifiedCat = index;
                                    passTheData = {
                                      'coworker_id': '$coworkerId',
                                      'category_id': '${categoryicons.id}'
                                    };
                                    _getDataServices(passTheData);
                                    checkboxBool.clear();
                                    av.clear();
                                    totalDuration = 0;
                                    totalValue = 0;
                                  });
                                  setState(() {
                                    selectedCatId = categoryicons.id;
                                  });
                                  selected = [false];
                                },
                                child: Stack(
                                  children: [
                                    Column(
                                      children: [
                                        Container(
                                            margin: EdgeInsets.all(8.0),
                                            height: MediaQuery.of(context)
                                                    .size
                                                    .height /
                                                11,
                                            width: MediaQuery.of(context)
                                                    .size
                                                    .width /
                                                6,
                                            decoration: BoxDecoration(
                                              shape: BoxShape.rectangle,
                                              borderRadius: BorderRadius.all(
                                                  Radius.circular(15.0)),
                                              color: Colors.white,
                                            ),
                                            child: Image(
                                              image: NetworkImage(
                                                  categoryicons.image),
                                              fit: BoxFit.scaleDown,
                                              height: 28,
                                              width: 27,
                                            )),
                                        Padding(
                                          padding: const EdgeInsets.symmetric(
                                              horizontal: 8.0),
                                          child: Text(
                                            categoryicons.category_name,
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
                                    Positioned(
                                      top: 5.5,
                                      right: 2.5,
                                      child: Container(
                                        height: 17.0,
                                        width: 17.0,
                                        decoration: BoxDecoration(
                                          color: verifiedCat == index
                                              ? Theme.of(context).primaryColor
                                              : Colors.transparent,
                                          shape: BoxShape.circle,
                                        ),
                                        child: Icon(
                                          Icons.check,
                                          color: verifiedCat == index
                                              ? Colors.white
                                              : Colors.transparent,
                                          size: 16.0,
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
                  ),
                  SliverList(
                      delegate: SliverChildBuilderDelegate((context, index) {
                    return SingleChildScrollView(
                      child: Container(
                        margin: EdgeInsets.all(8.0),
                        // color: Colors.red,
                        child: cws.length == 0
                            ? Container(
                                height: 100.0,
                                width: MediaQuery.of(context).size.width,
                                child: Center(
                                    child: Text(
                                  'data not found',
                                  style: TextStyle(
                                    color: darkBlue,
                                    fontSize: 16,
                                    fontFamily: 'FivoSansMediumOblique',
                                  ),
                                )))
                            : ListView.builder(
                                shrinkWrap: true,
                                physics: ClampingScrollPhysics(),
                                scrollDirection: Axis.vertical,
                                itemCount: cws.length,
                                itemBuilder: (context, index) {
                                  category_wise_service_coworker
                                      servicelistdetails = cws[index];
                                  for (int bool = 0;
                                      bool < cws.length;
                                      bool++) {
                                    checkboxBool.add(false);
                                  }
                                  return InkWell(
                                    onTap: () {
                                      setState(() {
                                        checkboxBool[index] =
                                            !checkboxBool[index];
                                        servicePrice(checkboxBool[index],
                                            servicelistdetails.price);
                                        serviceDuration(checkboxBool[index],
                                            servicelistdetails.duration);
                                        serviceId = servicelistdetails.id;
                                        if (checkboxBool[index] == true) {
                                          av.add(AddValues(
                                            modelServiceId: serviceId,
                                            modelCoworkerId: coworkerId,
                                            serviceName:
                                                servicelistdetails.service_name,
                                            servicePrice:
                                                servicelistdetails.price,
                                            serviceDuration:
                                                servicelistdetails.duration,
                                            serviceRate:
                                                servicelistdetails.rate,
                                            serviceDescription:
                                                servicelistdetails.description,
                                          ));
                                        }
                                        if (checkboxBool[index] == false) {
                                          for (int check = 0;
                                              check < av.length;
                                              check++) {
                                            if (servicelistdetails.id ==
                                                av[check].modelServiceId) {
                                              av.removeAt(check);
                                            }
                                          }
                                        }
                                      });
                                    },
                                    child: ListTile(
                                      title: Row(
                                        children: [
                                          Expanded(
                                            child: Text(
                                              servicelistdetails.service_name,
                                              style: TextStyle(
                                                color: darkBlue,
                                                fontSize: 18,
                                                fontFamily: 'FivoSansMedium',
                                              ),
                                            ),
                                          ),
                                          Text(
                                            'SAR${servicelistdetails.price}',
                                            style: TextStyle(
                                              color: darkBlue,
                                              fontFamily: 'FivoSansMedium',
                                              fontSize: 16,
                                            ),
                                          ),
                                          SizedBox(
                                            width: 5.0,
                                          ),
                                          Container(
                                            height: 25.0,
                                            width: 25.0,
                                            decoration: BoxDecoration(
                                              border: Border.all(
                                                color: Theme.of(context)
                                                    .primaryColor,
                                              ),
                                              borderRadius: BorderRadius.all(
                                                Radius.circular(5.0),
                                              ),
                                            ),
                                            child: Theme(
                                              data: Theme.of(context).copyWith(
                                                unselectedWidgetColor:
                                                    Colors.transparent,
                                              ),
                                              child: Checkbox(
                                                checkColor: Theme.of(context)
                                                    .primaryColor,
                                                activeColor: Theme.of(context)
                                                    .primaryColor,
                                                value: checkboxBool[index],
                                                onChanged: (value) {
                                                  setState(() {
                                                    checkboxBool[index] =
                                                        !checkboxBool[index];
                                                    servicePrice(
                                                        checkboxBool[index],
                                                        servicelistdetails
                                                            .price);
                                                    serviceDuration(
                                                        checkboxBool[index],
                                                        servicelistdetails
                                                            .duration);
                                                    serviceId =
                                                        servicelistdetails.id;
                                                    if (checkboxBool[index] ==
                                                        true) {
                                                      av.add(AddValues(
                                                        modelServiceId:
                                                            serviceId,
                                                        modelCoworkerId:
                                                            coworkerId,
                                                        serviceName:
                                                            servicelistdetails
                                                                .service_name,
                                                        servicePrice:
                                                            servicelistdetails
                                                                .price,
                                                        serviceDuration:
                                                            servicelistdetails
                                                                .duration,
                                                        serviceRate:
                                                            servicelistdetails
                                                                .rate,
                                                        serviceDescription:
                                                            servicelistdetails
                                                                .description,
                                                      ));
                                                    }
                                                    if (checkboxBool[index] ==
                                                        false) {
                                                      for (int check = 0;
                                                          check < av.length;
                                                          check++) {
                                                        if (servicelistdetails
                                                                .id ==
                                                            av[check]
                                                                .modelServiceId) {
                                                          av.removeAt(check);
                                                        }
                                                      }
                                                    }
                                                  });
                                                },
                                              ),
                                            ),
                                          )
                                        ],
                                      ),
                                      subtitle: Padding(
                                        padding:
                                            EdgeInsets.symmetric(vertical: 5.0),
                                        child: Column(
                                          crossAxisAlignment:
                                              CrossAxisAlignment.start,
                                          children: [
                                            Row(
                                              mainAxisAlignment:
                                                  MainAxisAlignment
                                                      .spaceBetween,
                                              children: [
                                                Text(
                                                  'Duration : ${servicelistdetails.duration}',
                                                  style: TextStyle(
                                                    color: extraDarkBlue,
                                                    fontSize: 14,
                                                    fontFamily:
                                                        'FivoSansMedium',
                                                  ),
                                                ),
                                                SmoothStarRating(
                                                  borderColor: ratingStar,
                                                  color: ratingStar,
                                                  size: 15,
                                                  defaultIconData:
                                                      Icons.star_border,
                                                  rating: double.parse(
                                                      servicelistdetails.rate),
                                                  spacing: 1.0,
                                                  allowHalfRating: true,
                                                  isReadOnly: true,
                                                ),
                                              ],
                                            ),
                                            SizedBox(height: 5.0),
                                            Text(
                                              servicelistdetails.description,
                                              style: TextStyle(
                                                color: extraDarkBlue,
                                                fontSize: 14,
                                                fontFamily: 'FivoSansMedium',
                                              ),
                                            ),
                                            SizedBox(height: 5.0),
                                            Divider(
                                              color: extraDarkBlue,
                                              height: 10.0,
                                            ),
                                          ],
                                        ),
                                      ),
                                    ),
                                  );
                                },
                              ),
                      ),
                    );
                  }, childCount: 1)),
                  SliverList(
                    delegate: SliverChildListDelegate([
                      SizedBox(
                          height: MediaQuery.of(context).size.height * 0.15),
                    ]),
                  ),
                ],
              ),
              Positioned(
                bottom: 0.01,
                child: Column(
                  children: [
                    Container(
                      height: 50.0,
                      width: MediaQuery.of(context).size.width / 1.07,
                      decoration: BoxDecoration(
                        color: Color(0xFFE9F0F7),
                        borderRadius: BorderRadius.all(
                          Radius.circular(30.0),
                        ),
                      ),
                      child: Padding(
                        padding: EdgeInsets.symmetric(horizontal: 15.0),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Total Payable',
                                  style: TextStyle(
                                    color: darkBlue,
                                    fontSize: 16,
                                    fontFamily: 'FivoSansMediumOblique',
                                  ),
                                ),
                                Text(
                                  'Duration : $totalDuration Min',
                                  style: TextStyle(
                                    color: darkBlue,
                                    fontSize: 14,
                                    fontFamily: 'FivoSansOblique',
                                  ),
                                ),
                              ],
                            ),
                            Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Text(
                                  'SAR$totalValue',
                                  style: TextStyle(
                                    color: darkBlue,
                                    fontSize: 14,
                                    fontFamily: 'FivoSansMedium',
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),
                    ),
                    SizedBox(height: 20.0),
                    Container(
                      height: 50.0,
                      width: MediaQuery.of(context).size.width,
                      child: RaisedButton(
                        onPressed: () {
                          if (av.isNotEmpty) {
                            // print('av is $av');
                            Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => BookAppointment(
                                    previousTotalValue: totalValue,
                                    previousTotalTime: totalDuration,
                                    previousSpecialistID: passId,
                                    addValues: av,
                                  ),
                                ));
                          } else {
                            showDialog(
                                builder: (context) => AlertDialog(
                                      title: Text('Error'),
                                      content:
                                          Text('Please select any service'),
                                      actions: <Widget>[
                                        FlatButton(
                                          onPressed: () {
                                            Navigator.pop(context);
                                          },
                                          child: Text('ok'),
                                        )
                                      ],
                                    ),
                                context: context);
                          }
                        },
                        color: Theme.of(context).primaryColor,
                        child: Text(
                          'Continue',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 18.0,
                            fontFamily: 'FivoSansMedium',
                          ),
                        ),
                      ),
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
