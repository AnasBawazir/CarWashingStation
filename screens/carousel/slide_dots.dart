import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';

class SlidDots extends StatelessWidget {
  final bool isActive;
  SlidDots(this.isActive);
  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      duration: Duration(milliseconds: 150),
      margin: const EdgeInsets.symmetric(horizontal: 10.0),
      height: isActive ? 12 : 8,
      width: isActive ? 12 : 20,
      decoration: BoxDecoration(
        color: isActive
            ? Theme.of(context).primaryColor
            : Color(0xFF6B48FF).withOpacity(0.3),
        borderRadius: BorderRadius.all(Radius.circular(12)),
      ),
    );
  }
}
