import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import '../../models/carousel_details.dart';

const darkBlue = Color(0xFF265E9E);
const extraDarkBlue = Color(0xFF91B4D8);

class SlideItem extends StatelessWidget {
  final int index;
  SlideItem(this.index);

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.center,
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Container(
          margin: EdgeInsets.only(top: 50.0),
          height: 170.0,
          width: 267.0,
          decoration: BoxDecoration(
            shape: BoxShape.rectangle,
            image: DecorationImage(
                image: AssetImage(silderList[index].imageUrl),
                fit: BoxFit.fill),
          ),
        ),
        SizedBox(
          height: 230,
        ),
        Text(
          silderList[index].title,
          style: TextStyle(fontSize: 20, color: darkBlue),
        ),
        SizedBox(
          height: 10,
        ),
        Padding(
          padding: EdgeInsets.symmetric(horizontal: 20.0),
          child: Text(
            silderList[index].description,
            textAlign: TextAlign.center,
            style: TextStyle(fontSize: 14, color: extraDarkBlue),
          ),
        )
      ],
    );
  }
}
