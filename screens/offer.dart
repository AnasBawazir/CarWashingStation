import 'dart:convert';
import 'dart:math' as math;
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:modal_progress_hud/modal_progress_hud.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:Magasil/api/api.dart';
import 'package:Magasil/models/addvalueforservice.dart';
import 'package:Magasil/models/offer.dart';
import 'package:Magasil/screens/appointment_review.dart';
import 'package:Magasil/screens/custom_drawer.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);

class Offers extends StatefulWidget {
  final previousDate;
  final previousTimeSlot;
  final List<AddValues> addvalues;
  final previousSpecialistId;
  final previousTotalValue;
  final previousTotalTime;
  final previousDisplayDate;

  const Offers(
      {Key key,
      this.previousDate,
      this.previousTimeSlot,
      this.addvalues,
      this.previousSpecialistId,
      this.previousTotalValue,
      this.previousTotalTime,
      this.previousDisplayDate})
      : super(key: key);
  @override
  _OffersState createState() => _OffersState();
}

class _OffersState extends State<Offers> {
  var showSpinner = false;
  var selectedOfferId;
  int selectedOfferDiscount;
  var selectedOfferType;
  var selectedOfferCuopen;
  var previousTotalValue;
  var previousSpecialistId;
  var previousTimeSlot;
  var previousDate;
  var previousTotalTime;
  var previousDisplayDate;
  List<Offer> offer = List<Offer>();
  List<AddValues> addvalues;
  @override
  void initState() {
    previousTotalValue = widget.previousTotalValue;
    previousSpecialistId = widget.previousSpecialistId;
    addvalues = widget.addvalues;
    previousTimeSlot = widget.previousTimeSlot;
    previousDate = widget.previousDate;
    previousTotalTime = widget.previousTotalTime;
    previousDisplayDate = widget.previousDisplayDate;
    _offerinfo();
    super.initState();
  }

  Future<void> _offerinfo() async {
    setState(() {
      showSpinner = true;
    });
    var res = await CallApi().getWithToken('offer');
    var body = json.decode(res.body);
    var theData = body['data'];
    for (int i = 0; i < theData.length; i++) {
      Map<String, dynamic> map = theData[i];
      offer.add(Offer.fromJson(map));
    }
    setState(() {
      showSpinner = false;
    });
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
          'Offer',
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
      body: ModalProgressHUD(
        inAsyncCall: showSpinner,
        child: Container(
          child: ListView.builder(
            itemCount: offer.length,
            itemBuilder: (context, index) {
              Offer offers = offer[index];
              return offer.length > 0
                  ? Container(
                      padding:
                          EdgeInsets.symmetric(vertical: 10.0, horizontal: 17),
                      height: 128.0,
                      width: MediaQuery.of(context).size.width,
                      decoration: BoxDecoration(
                          color: Colors.white,
                          border: Border.all(
                              color: Theme.of(context).primaryColor, width: 2),
                          borderRadius: BorderRadius.all(Radius.circular(10.0)),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black12,
                              spreadRadius: 2.5, //1.0
                              blurRadius: 7.0, //3.0
                            )
                          ]),
                      margin: EdgeInsets.all(10.0),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            offers.description,
                            style: TextStyle(
                              color: darkBlue,
                              fontSize: 16,
                              fontFamily: 'FivoSansMedium',
                            ),
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    'Expires',
                                    style: TextStyle(
                                      color: extraDarkBlue,
                                      fontSize: 12,
                                      fontFamily: 'FivoSansRegular',
                                    ),
                                  ),
                                  Text(
                                    offers.expireDate,
                                    style: TextStyle(
                                      color: extraDarkBlue,
                                      fontSize: 13,
                                      fontFamily: 'FivoSansRegular',
                                    ),
                                  ),
                                ],
                              ),
                              RaisedButton(
                                color: Theme.of(context).primaryColor,
                                onPressed: () async {
                                  selectedOfferId = offers.id;
                                  selectedOfferDiscount = offers.discount;
                                  selectedOfferType = offers.type;
                                  SharedPreferences localstorage =
                                      await SharedPreferences.getInstance();
                                  localstorage.setString(
                                      'offer_id', selectedOfferId.toString());
                                  localstorage.setString('offer_discount',
                                      selectedOfferDiscount.toString());
                                  localstorage.setString('offer_type',
                                      selectedOfferType.toString());
                                  Navigator.pushReplacement(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) => AppointmentReview(
                                          previousDisplayDate:
                                              previousDisplayDate,
                                          previousTotalTime: previousTotalTime,
                                          previousDate: previousDate,
                                          previousTimeSlot: previousTimeSlot,
                                          addvalues: addvalues,
                                          previousSpecialistId:
                                              previousSpecialistId,
                                          previousTotalValue:
                                              previousTotalValue,
                                          selectedOfferId: selectedOfferId,
                                          selectedOfferDiscount:
                                              selectedOfferDiscount,
                                          selectedOfferType: selectedOfferType,
                                        ),
                                      ));
                                },
                                child: Text(
                                  'Apply',
                                  style: TextStyle(
                                    color: Colors.white,
                                    fontSize: 15.0,
                                    fontFamily: 'FivoSansMedium',
                                  ),
                                ),
                              )
                            ],
                          )
                        ],
                      ),
                    )
                  : Center(
                      child: Container(
                        child: Text(
                          'Empty Offer List',
                          style: TextStyle(
                            color: extraDarkBlue,
                            fontSize: 12,
                            fontFamily: 'FivoSansRegular',
                          ),
                        ),
                      ),
                    );
            },
          ),
        ),
      ),
    );
  }
}
