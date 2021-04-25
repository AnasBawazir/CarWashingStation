import 'dart:async';
import 'package:flutter/material.dart';
import 'home/home_page.dart';
import 'carousel/slide_dots.dart';
import 'carousel/slide_item.dart';
import '../models/carousel_details.dart';

const darkBlue = Color(0xFF265E9E);

class OnBoarding extends StatefulWidget {
  @override
  _OnBoardingState createState() => _OnBoardingState();
}

class _OnBoardingState extends State<OnBoarding> {
  int _currentPage = 0;
  PageController _pageController = PageController(initialPage: 0);

  @override
  void initState() {
    super.initState();
    Timer.periodic(Duration(seconds: 5), (Timer timer) {
      if (_currentPage < 2) {
        _currentPage++;
      } else {
        _currentPage = 0;
      }
    });
  }

  @override
  void dispose() {
    super.dispose();
    _pageController.dispose();
  }

  _onPageChanged(int index) {
    setState(() {
      _currentPage = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Container(
          width: double.infinity,
          color: Colors.white,
          child: Padding(
            padding: EdgeInsets.all(20.0),
            child: Stack(
              alignment: AlignmentDirectional.center,
              children: <Widget>[
                PageView.builder(
                  onPageChanged: _onPageChanged,
                  controller: _pageController,
                  scrollDirection: Axis.horizontal,
                  itemCount: silderList.length,
                  itemBuilder: (context, index) {
                    return SlideItem(index);
                  },
                ),
                Stack(
                  alignment: AlignmentDirectional.topStart,
                  children: <Widget>[
                    Positioned(
                      top: MediaQuery.of(context).size.height / 1.8,
                      left: MediaQuery.of(context).size.width / 3,
                      child: Container(
                        margin: EdgeInsets.only(bottom: 35),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            for (int i = 0; i < silderList.length; i++)
                              if (i == _currentPage)
                                SlidDots(true)
                              else
                                SlidDots(false)
                          ],
                        ),
                      ),
                    )
                  ],
                ),
                Align(
                  alignment: Alignment.bottomRight,
                  child: FlatButton(
                    onPressed: () {
                      Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => HomePage(),
                          ));
                    },
                    child: Text(
                      'Next',
                      style: TextStyle(
                        fontFamily: 'PoppinsMedium',
                        color: darkBlue,
                      ),
                    ),
                  ),
                )
              ],
            ),
          ),
        ),
      ),
    );
  }
}
