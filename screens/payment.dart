import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:flutter_paystack/flutter_paystack.dart';
import 'package:Magasil/screens/webview_screen.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:geolocator/geolocator.dart';
import 'package:giffy_dialog/giffy_dialog.dart';
import 'package:logger/logger.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/models/addvalueforservice.dart';
import 'package:Magasil/screens/custom_drawer.dart';
import 'package:fluttertoast/fluttertoast.dart';
/*
import 'package:razorpay_flutter/razorpay_flutter.dart';
*/
import 'package:Magasil/screens/stripe.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);
const containerShadow = Color(0xFF91B4D8);

enum CardType { visa }

enum Service { shop, home }

class Payment extends StatefulWidget {
  final String stripeToken;
  final int paymentTokenKnow;
  final int paymentStatus;
  final String paymentType;
  final int selectedIndex;
  // final List<AddValues> addValue;
  const Payment({
    Key key,
    this.stripeToken,
    this.paymentTokenKnow,
    this.paymentStatus,
    this.paymentType,
    this.selectedIndex,
    // this.addValue,
  }) : super(key: key);
  _PaymentState createState() => _PaymentState();
}

class _PaymentState extends State<Payment> {
  CardType _cardType = CardType.visa;
  int _selected;
  var totalPayableAmount;
  var showSpinner = false;
  var cod;
  var stripe;
  var paypal;
  var razorpay;
  var razorpayAmount;
  var serviceId;
  List<int> serviceIdList = List<int>();
  var coworkerId;
  var timeSlot;
  var date;
  var paymentType;
  var prefillMobileNumber;
  var prefillEmailId;
  var paymentToken;
  int paymentTokenPassKnow;
  int paymentTokenKnow;
  var razorpayToken;
  Map<String, dynamic> body;
  var payment_status;
  var service_type = 'SHOP';
  var lat;
  var long;
  var totalDiscount;
  bool addressView = false;
  bool addressNotView = true;
  var offerId;
  var razorpayKey = '';
  String stripeToken = '';
  int stripePaymentStatus;
  String stripePaymentType;
  int previousSelectedIndex;
  // List<AddValues> _addValue;
  var logger = Logger();

  final _addressController = TextEditingController();

  Service _service = Service.shop;

/*
  Razorpay _razorpay;
*/

  @override
  void initState() {
    stripeToken = widget.stripeToken;
    paymentTokenKnow = widget.paymentTokenKnow;
    stripePaymentStatus = widget.paymentStatus;
    stripePaymentType = widget.paymentType;
    previousSelectedIndex = widget.selectedIndex;
    // _addValue = widget.addValue;
    // print('add value is ${_addValue[0].serviceDescription}');
    previousSelectedIndex == null
        ? _selected = _selected
        : setState(() {
            _selected = previousSelectedIndex;
          });
    _totalPayableAmount();
    _paymentMethodSelection();
    getLocation();
    PrefillInfo();
    /*_razorpay = Razorpay();
    _razorpay.on(Razorpay.EVENT_PAYMENT_SUCCESS, _handlePaymentSuccess);
    _razorpay.on(Razorpay.EVENT_PAYMENT_ERROR, _handlePaymentError);
    _razorpay.on(Razorpay.EVENT_EXTERNAL_WALLET, _handleExternalWallet);*/
    super.initState();
  }

  @override
  void dispose() {
    super.dispose();
/*
    _razorpay.clear();
*/
  }

  void getLocation() async {
    Position position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high);
    lat = position.latitude;
    long = position.longitude;
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    localStorage.setString('latitude', lat.toString());
    localStorage.setString('longitude', long.toString());
  }

  Future<void> PrefillInfo() async {
    setState(() {
      showSpinner = true;
    });
    var res = await CallApi().getWithToken('user');
    var body = json.decode(res.body);
    var theData = body;
    prefillMobileNumber = theData['phone'];
    prefillEmailId = theData['email'];

    var res2 = await CallApi().getWithToken('payment_setting');
    var body2 = json.decode(res2.body);
    var theData2 = body2['data'];
    razorpayKey = theData2['razorpay_key'];
    setState(() {
      showSpinner = false;
    });
  }

  void RazorpayMethod() async {
    // totalPayableAmount = double.parse(totalPayableAmount);
    double convertToDouble = double.parse(totalPayableAmount.toString());
    razorpayAmount = convertToDouble * 100;

    var options = {
      'key': '$razorpayKey',
      'amount': razorpayAmount,
      'name': 'Magasil',
      'prefill': {'contact': prefillMobileNumber, 'email': prefillEmailId},
    };

    try {
/*
      _razorpay.open(options);
*/
    } catch (e) {
      debugPrint(e);
    }
  }

  /*void _handlePaymentSuccess(PaymentSuccessResponse response) {
    razorpayToken = response.paymentId;
    Fluttertoast.showToast(
      msg: "SUCCESS: " + response.paymentId,
      timeInSecForIosWeb: 4,
    );
  }

  void _handlePaymentError(PaymentFailureResponse response) {
    Fluttertoast.showToast(
        msg: "ERROR: " + response.code.toString() + " - " + response.message,
        timeInSecForIosWeb: 4);
  }

  void _handleExternalWallet(ExternalWalletResponse response) {
    Fluttertoast.showToast(
        msg: "EXTERNAL_WALLET: " + response.walletName, timeInSecForIosWeb: 4);
  }*/

  _totalPayableAmount() async {
    setState(() {
      showSpinner = true;
    });
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    totalPayableAmount = localStorage.getString('totalPayableAmount');
    serviceId = localStorage.getInt('serviceId');
    // serviceIdList.add(serviceId);
    coworkerId = localStorage.getString('coworkerId');
    timeSlot = localStorage.getString('timeSlot');
    date = localStorage.getString('date');
    totalDiscount = localStorage.getString('totalDiscount');
    offerId = localStorage.getString('offer_id');
    setState(() {
      showSpinner = false;
    });
  }

  Future<void> bookingAppointment(data) async {
    setState(() {
      showSpinner = true;
    });
    try {
      // print('data is $data');
      // logger.d(data);
      var res = await CallApi().postDataWithToken(data, 'book_appoinment');
      // print(res.body);
      var body = json.decode(res.body);
      // print('body is $body');
      // logger.d(body);
      // print('the res is ${body['success']}');
      // print('new the res is ${body['message']}');
      var theData = body['data'];
      if (body['success'] == true) {
        setState(() {
          showSpinner = false;
        });
        SharedPreferences localStorage = await SharedPreferences.getInstance();
        localStorage.remove('addValue');
        showDialog(
          context: context,
          builder: (_) => AssetGiffyDialog(
            onlyOkButton: true,
            image: Image(
              image: AssetImage(
                'assets/images/payment_success.png',
              ),
              fit: BoxFit.fill,
            ),
            title: Text(
              'Payment Successful',
              style: TextStyle(
                color: darkBlue,
                fontSize: 20,
                fontFamily: 'PoppinsMedium',
              ),
            ),
            description: Text(
              'Booking Confirmed',
              style: TextStyle(
                color: darkBlue,
                fontSize: 25,
                fontFamily: 'PoppinsMedium',
              ),
            ),
            entryAnimation: EntryAnimation.TOP_LEFT,
            buttonOkColor: Colors.green[400],
            onOkButtonPressed: () {
              Navigator.pushNamedAndRemoveUntil(
                  context, '/HomePage', (route) => false);
            },
          ),
        );
      } else {
        showDialog(
          context: context,
          builder: (_) => AlertDialog(
            title: Text('Payment Fail'),
            content: Text('Invalid currency'),
            actions: <Widget>[
              FlatButton(
                onPressed: () {
                  Navigator.pop(context);
                },
                child: Text('Try Again'),
              )
            ],
          ),
        );
      }
    } catch (e) {
      showDialog(
        builder: (context) => AlertDialog(
          title: Text('Error'),
          content: Text(e.toString()),
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
    setState(() {
      showSpinner = false;
    });
  }

  Future<void> _paymentMethodSelection() async {
    setState(() {
      showSpinner = true;
    });
    var res = await CallApi().getWithToken('payment_setting');
    var body = json.decode(res.body);
    var theData = body['data'];
    cod = theData['id'];
    stripe = theData['stripe'];
    paypal = theData['paypal'];
    razorpay = theData['razorpay'];
    setState(() {
      showSpinner = false;
    });
    SharedPreferences localStorage = await SharedPreferences.getInstance();
    localStorage.remove('isFrom');
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        leading: IconButton(
          icon: Icon(
            Icons.chevron_left,
            size: 24,
            color: darkBlue,
          ),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
        centerTitle: true,
        title: Text(
          'Payment',
          style: TextStyle(
            color: darkBlue,
            fontSize: 18.0,
            fontFamily: 'FivoSansMedium',
          ),
        ),
        actions: [
          IconButton(
            icon: Icon(
              FontAwesomeIcons.bars,
              size: 22,
              color: darkBlue,
            ),
            onPressed: () {
              Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => CustomDrawer(),
                  ));
            },
          )
        ],
      ),
      body: GestureDetector(
        onTap: () {
          FocusScopeNode currentFocus = FocusScope.of(context);
          if (!currentFocus.hasPrimaryFocus) {
            currentFocus.unfocus();
          }
        },
        child: ModalProgressHUD(
          inAsyncCall: showSpinner,
          child: Stack(
            children: [
              ListView(
                scrollDirection: Axis.vertical,
                children: [
                  Padding(
                    padding: const EdgeInsets.all(15.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        SizedBox(height: 20.0),
                        Text(
                          'Total Payable',
                          style: TextStyle(
                            color: darkBlue,
                            fontSize: 18.0,
                            fontFamily: 'FivoSansMedium',
                          ),
                        ),
                        SizedBox(height: 10.0),
                        Text(
                          'SAR$totalPayableAmount',
                          style: TextStyle(
                            color: darkBlue,
                            fontSize: 30.0,
                            fontFamily: 'FivoSansMedium',
                          ),
                        ),
                      ],
                    ),
                  ),
                  SizedBox(height: 15.0),
                  Divider(
                    color: extraDarkBlue.withOpacity(0.3),
                    height: 5.0,
                    thickness: 3.0,
                  ),
                  Padding(
                    padding: const EdgeInsets.all(15.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Payment Method',
                          style: TextStyle(
                            color: darkBlue,
                            fontSize: 18.0,
                            fontFamily: 'FivoSansMedium',
                          ),
                        ),
                        SizedBox(height: 5.0),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            /*Expanded(
                              child: RaisedButton(
                                onPressed: razorpay == 1
                                    ? () {
                                        paymentTokenKnow = 1;
                                        paymentTokenPassKnow = 1;
                                        RazorpayMethod();
                                        PrefillInfo();
                                        payment_status = 1;
                                        paymentType = 'Razor';
                                        setState(() {
                                          _selected = 0;
                                        });
                                      }
                                    : null,
                                color: _selected == 0
                                    ? Theme.of(context).primaryColor
                                    : Theme.of(context)
                                        .primaryColor
                                        .withOpacity(0.5),
                                child: Container(
                                  decoration: BoxDecoration(
                                    borderRadius:
                                        BorderRadius.all(Radius.circular(3.0)),
                                  ),
                                  child: Text(
                                    'Razorpay',
                                    style: TextStyle(
                                      color: Colors.white,
                                      fontSize: 10,
                                      fontFamily: 'FivoSansMedium',
                                    ),
                                  ),
                                ),
                              ),
                            ),
                            SizedBox(width: 10.0),
                            Expanded(
                              child: RaisedButton(
                                onPressed: stripe == 1
                                    ? () {
                                        setState(() {
                                          _selected = 2;
                                        });
                                        paymentTokenKnow = 2;
                                        paymentTokenPassKnow = 2;
                                        payment_status = 1;
                                        paymentType = 'Stripe';
                                        Navigator.push(
                                            context,
                                            MaterialPageRoute(
                                              builder: (context) =>
                                                  PaymentStripe(
                                                // amount: totalPayableAmount,
                                                paymentTokenKnow:
                                                    paymentTokenPassKnow,
                                                paymentStatus: payment_status,
                                                patmentType: paymentType,
                                                selectedIndex: _selected,
                                              ),
                                            ));
                                      }
                                    : null,
                                color: _selected == 2
                                    ? Theme.of(context).primaryColor
                                    : Theme.of(context)
                                        .primaryColor
                                        .withOpacity(0.5),
                                child: Container(
                                  decoration: BoxDecoration(
                                    borderRadius:
                                        BorderRadius.all(Radius.circular(3.0)),
                                  ),
                                  child: Center(
                                    child: Text(
                                      'Stripe',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 10,
                                        fontFamily: 'FivoSansMedium',
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            ),
                            SizedBox(width: 10.0),*/
                            Expanded(
                              child: RaisedButton(
                                onPressed: cod == 1
                                    ? () {
                                        payment_status = 0;
                                        paymentType = 'COD';
                                        setState(() {
                                          _selected = 3;
                                        });
                                      }
                                    : null,
                                color: _selected == 3
                                    ? Theme.of(context).primaryColor
                                    : Theme.of(context)
                                        .primaryColor
                                        .withOpacity(0.5),
                                child: Container(
                                  decoration: BoxDecoration(
                                    borderRadius:
                                        BorderRadius.all(Radius.circular(3.0)),
                                  ),
                                  child: Center(
                                    child: Text(
                                      'COD',
                                      style: TextStyle(
                                        color: Colors.white,
                                        fontSize: 10,
                                        fontFamily: 'FivoSansMedium',
                                      ),
                                    ),
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                        SizedBox(height: 30.0),
                        Container(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Where Would you like to service at?',
                                style: TextStyle(
                                  color: darkBlue,
                                  fontSize: 18.0,
                                  fontFamily: 'FivoSansMedium',
                                ),
                              ),
                              RadioListTile<Service>(
                                  value: Service.shop,
                                  groupValue: _service,
                                  activeColor: Theme.of(context).primaryColor,
                                  title: Text(
                                    'Shop',
                                    style: TextStyle(
                                      color: darkBlue,
                                      fontSize: 15.0,
                                      fontFamily: 'FivoSansMedium',
                                    ),
                                  ),
                                  onChanged: (Service val) {
                                    setState(() {
                                      _service = val;
                                      service_type = "SHOP";
                                      addressView = false;
                                      addressNotView = true;
                                    });
                                  }),
                              RadioListTile<Service>(
                                  value: Service.home,
                                  groupValue: _service,
                                  activeColor: Theme.of(context).primaryColor,
                                  title: Text(
                                    'Home',
                                    style: TextStyle(
                                      color: darkBlue,
                                      fontSize: 15.0,
                                      fontFamily: 'FivoSansMedium',
                                    ),
                                  ),
                                  onChanged: (Service val) {
                                    setState(() {
                                      _service = val;
                                      service_type = "HOME";
                                      addressView = true;
                                      addressNotView = false;
                                    });
                                  })
                            ],
                          ),
                        ),
                        SizedBox(height: 20.0),
                        // addressView == 1? :
                        Visibility(
                          visible: addressView,
                          child: Container(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Your address Please...',
                                  style: TextStyle(
                                    color: darkBlue,
                                    fontSize: 18.0,
                                    fontFamily: 'FivoSansMedium',
                                  ),
                                ),
                                SizedBox(
                                  height: 10,
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
                                  child: TextField(
                                    controller: _addressController,
                                    enableSuggestions: false,
                                    keyboardType: TextInputType.visiblePassword,
                                    decoration: InputDecoration(
                                      contentPadding: EdgeInsets.all(15),
                                      border: InputBorder.none,
                                      hintText: 'Type your address here',
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
                              ],
                            ),
                          ),
                        ),
                        Visibility(
                          visible: addressNotView,
                          child: Container(
                            height: 1,
                            width: 1,
                          ),
                        ),
                        SizedBox(height: 30.0),
                      ],
                    ),
                  ),
                  SizedBox(height: 30.0),
                  // RaisedButton(onPressed: () async {
                  //   SharedPreferences localStorage =
                  //       await SharedPreferences.getInstance();
                  //   int addvaluelength = localStorage.getInt('addValueLength');
                  //   // var convertinarray = [];
                  //   // convertinarray = localStorage.getStringList('addValue');
                  //   List<String> lsServicename = [];
                  //   List<String> lsModelserviceid = [];
                  //   List<String> lsServiceprice = [];
                  //   List<String> lsServiceduration = [];
                  //   List<String> lsServicedescription = [];
                  //   List<String> lsModelcoworkerid = [];
                  //   List<String> lsServicerate = [];
                  //   List<int> lsModelserviceidConverted = [];
                  //   List<int> lsServicepriceConverted = [];
                  //   List<int> lsServicedurationConverted = [];
                  //   List<int> lsModelcoworkeridConverted = [];
                  //   List<int> lsServicerateConverted = [];
                  //
                  //   lsServicename =
                  //       (localStorage.getStringList('serviceName') ??
                  //           List<String>());
                  //   lsModelserviceid =
                  //       (localStorage.getStringList('modelServiceId') ??
                  //           List<String>());
                  //   var convertmodelserviceid;
                  //   for (int a = 0; a < addvaluelength; a++) {
                  //     convertmodelserviceid = int.parse(lsModelserviceid[a]);
                  //     lsModelserviceidConverted.add(convertmodelserviceid);
                  //   }
                  //   lsServiceprice =
                  //       (localStorage.getStringList('servicePrice') ??
                  //           List<String>());
                  //   var convertserviceprice;
                  //   for (int b = 0; b < addvaluelength; b++) {
                  //     convertserviceprice = int.parse(lsServiceprice[b]);
                  //     lsServicepriceConverted.add(convertserviceprice);
                  //   }
                  //   lsServiceduration =
                  //       (localStorage.getStringList('serviceDuration') ??
                  //           List<String>());
                  //   var convertserviceduration;
                  //   for (int c = 0; c < addvaluelength; c++) {
                  //     convertserviceduration = int.parse(lsServiceduration[c]);
                  //     lsServicedurationConverted.add(convertserviceduration);
                  //   }
                  //   lsServicedescription =
                  //       (localStorage.getStringList('serviceDescription') ??
                  //           List<String>());
                  //   lsModelcoworkerid =
                  //       (localStorage.getStringList('modelCoworkerId') ??
                  //           List<String>());
                  //   var convertmodelcoworkerid;
                  //   for (int d = 0; d < addvaluelength; d++) {
                  //     convertmodelcoworkerid = int.parse(lsModelcoworkerid[d]);
                  //     lsModelcoworkeridConverted.add(convertmodelcoworkerid);
                  //   }
                  //   lsServicerate =
                  //       (localStorage.getStringList('serviceRate') ??
                  //           List<String>());
                  //   var convertservicerate;
                  //   for (int e = 0; e < addvaluelength; e++) {
                  //     convertservicerate = int.parse(lsServicerate[e]);
                  //     lsServicerateConverted.add(convertservicerate);
                  //   }
                  //   // for (int j = 0; j < addvaluelength; j++) {
                  //   //
                  //   // }
                  //   // var decode;
                  //   // for (int k = 0; k < convertinarray.length; k++) {
                  //   //   decode = json.decode(convertinarray[k]);
                  //   // }
                  //   // var convertt = convertinarray[0].split(',');
                  //   // logger.d(lsServicename);
                  //   // logger.d(lsModelserviceidConverted);
                  //   // logger.d(lsServicepriceConverted);
                  //   // logger.d(lsServicedurationConverted);
                  //   // logger.d(lsServicedescription);
                  //   // logger.d(lsModelcoworkeridConverted);
                  //   // logger.d(lsServicerateConverted);
                  //   List theservicesis = [];
                  //   // print('size $allservice');
                  //   for (int i = 0; i < addvaluelength; i++) {
                  //     theservicesis.add(lsServicename[i]);
                  //     theservicesis.add(lsModelserviceidConverted[i]);
                  //     theservicesis.add(lsServicepriceConverted[i]);
                  //     theservicesis.add(lsServicedurationConverted[i]);
                  //     theservicesis.add(lsServicedescription[i]);
                  //     theservicesis.add(lsModelcoworkeridConverted[i]);
                  //     theservicesis.add(lsServicerateConverted[i]);
                  //   }
                  //   logger.d(theservicesis);
                  //   // print('all service is$allservice');
                  //   // print('all service is$theservicesis');
                  //   // var abcd = json.decode(allservice);
                  // })
                ],
              ),
              Positioned(
                bottom: 0.01,
                child: Container(
                  height: 50.0,
                  width: MediaQuery.of(context).size.width,
                  child: RaisedButton(
                    onPressed: () async {
                      if (payment_status == null) {
                        if (stripePaymentStatus == null) {
                          showDialog(
                            context: context,
                            builder: (_) => AlertDialog(
                              title: Text('Payment Selection'),
                              content: Text('Please select Payment method'),
                              actions: <Widget>[
                                FlatButton(
                                  onPressed: () {
                                    Navigator.pop(context);
                                  },
                                  child: Text('Try Again'),
                                )
                              ],
                            ),
                          );
                        }
                      }
                      var totalDiscountINT;
                      if (totalDiscount != 'null') {
                        var a = double.parse(totalDiscount);
                        totalDiscountINT = a.toInt();
                      } else {
                        totalDiscountINT = 0;
                      }
                      var b = double.parse(totalPayableAmount.toString());
                      var totalPayableINT = b.toInt();
                      if (paymentTokenKnow == 1) {
                        paymentToken = razorpayToken;
                      } else if (paymentTokenKnow == 2) {
                        paymentToken = stripeToken;
                        payment_status = 1;
                        paymentType = 'Stripe';
                      }

                      var passAddress = '';
                      if (addressView == false) {
                        passAddress = 'SHOP';
                      } else {
                        passAddress = _addressController.text;
                      }

                      //--------------new---------
                      SharedPreferences localStorage =
                          await SharedPreferences.getInstance();
                      int addvaluelength =
                          localStorage.getInt('addValueLength');
                      // var convertinarray = [];
                      // convertinarray = localStorage.getStringList('addValue');
                      // List<String> lsServicename = [];
                      List<String> lsModelserviceid = [];
                      // List<String> lsServiceprice = [];
                      // List<String> lsServiceduration = [];
                      // List<String> lsServicedescription = [];
                      // List<String> lsModelcoworkerid = [];
                      // List<String> lsServicerate = [];
                      List<int> lsModelserviceidConverted = [];
                      // List<int> lsServicepriceConverted = [];
                      // List<int> lsServicedurationConverted = [];
                      // List<int> lsModelcoworkeridConverted = [];
                      // List<int> lsServicerateConverted = [];

                      // lsServicename =
                      //     (localStorage.getStringList('serviceName') ??
                      //         List<String>());
                      lsModelserviceid =
                          (localStorage.getStringList('modelServiceId') ??
                              List<String>());
                      var convertmodelserviceid;
                      for (int a = 0; a < addvaluelength; a++) {
                        convertmodelserviceid = int.parse(lsModelserviceid[a]);
                        lsModelserviceidConverted.add(convertmodelserviceid);
                      }
                      // lsServiceprice =
                      //     (localStorage.getStringList('servicePrice') ??
                      //         List<String>());
                      // var convertserviceprice;
                      // for (int b = 0; b < addvaluelength; b++) {
                      //   convertserviceprice = int.parse(lsServiceprice[b]);
                      //   lsServicepriceConverted.add(convertserviceprice);
                      // }
                      // lsServiceduration =
                      //     (localStorage.getStringList('serviceDuration') ??
                      //         List<String>());
                      // var convertserviceduration;
                      // for (int c = 0; c < addvaluelength; c++) {
                      //   convertserviceduration =
                      //       int.parse(lsServiceduration[c]);
                      //   lsServicedurationConverted.add(convertserviceduration);
                      // }
                      // lsServicedescription =
                      //     (localStorage.getStringList('serviceDescription') ??
                      //         List<String>());
                      // lsModelcoworkerid =
                      //     (localStorage.getStringList('modelCoworkerId') ??
                      //         List<String>());
                      // var convertmodelcoworkerid;
                      // for (int d = 0; d < addvaluelength; d++) {
                      //   convertmodelcoworkerid =
                      //       int.parse(lsModelcoworkerid[d]);
                      //   lsModelcoworkeridConverted.add(convertmodelcoworkerid);
                      // }
                      // lsServicerate =
                      //     (localStorage.getStringList('serviceRate') ??
                      //         List<String>());
                      // var convertservicerate;
                      // for (int e = 0; e < addvaluelength; e++) {
                      //   convertservicerate = int.parse(lsServicerate[e]);
                      //   lsServicerateConverted.add(convertservicerate);
                      // }
                      // for (int j = 0; j < addvaluelength; j++) {
                      //
                      // }
                      // var decode;
                      // for (int k = 0; k < convertinarray.length; k++) {
                      //   decode = json.decode(convertinarray[k]);
                      // }
                      // var convertt = convertinarray[0].split(',');
                      // logger.d(lsServicename);
                      // logger.d(lsModelserviceidConverted);
                      // logger.d(lsServicepriceConverted);
                      // logger.d(lsServicedurationConverted);
                      // logger.d(lsServicedescription);
                      // logger.d(lsModelcoworkeridConverted);
                      // logger.d(lsServicerateConverted);

                      for (int i = 0; i < addvaluelength; i++) {
                        serviceIdList.add(lsModelserviceidConverted[i]);
                        // map = {
                        //   'service_name': lsServicename[i],
                        //   'id': lsModelserviceidConverted[i],
                        //   'price': lsServicepriceConverted[i],
                        //   'duration': lsServicedurationConverted[i],
                        //   'description': lsServicedescription[i],
                        //   'coworker_id': lsModelcoworkeridConverted[i],
                        //   'rate': lsServicerateConverted[i]
                        // };
                        // passall_service.add(map);
                        // theservicesis.add(lsServicename[i]);
                        // theservicesis.add(lsModelserviceidConverted[i]);
                        // theservicesis.add(lsServicepriceConverted[i]);
                        // theservicesis.add(lsServicedurationConverted[i]);
                        // theservicesis.add(lsServicedescription[i]);
                        // theservicesis.add(lsModelcoworkeridConverted[i]);
                        // theservicesis.add(lsServicerateConverted[i]);
                      }
                      // logger.d(serviceIdList);

                      //--------------over------------
                      body = {
                        'service_id': serviceIdList,
                        'coworker_id': coworkerId,
                        'start_time': timeSlot,
                        'discount': totalDiscountINT,
                        'coupen_id': offerId,
                        'date': date,
                        'payment_type': paymentType,
                        'payment_token': paymentToken,
                        'amount': totalPayableINT,
                        'payment_status': payment_status.toString(),
                        'service_type': service_type,
                        'lat': lat.toString(),
                        'lang': long.toString(),
                        'address': passAddress,
                      };

                      // print('the payment body is $body');
                      bookingAppointment(body);
                    },
                    color: Theme.of(context).primaryColor,
                    child: Text(
                      'Complete Booking',
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
    );
  }
}
