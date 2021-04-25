import 'dart:async';
import 'package:flutter/material.dart';
import 'package:giffy_dialog/giffy_dialog.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:webview_flutter/webview_flutter.dart';

const darkBlue = Color(0xFF265E9E);

class WebviewScreen extends StatefulWidget {
  final url;

  const WebviewScreen({Key key, this.url}) : super(key: key);
  @override
  _WebviewScreenState createState() => _WebviewScreenState();
}

class _WebviewScreenState extends State<WebviewScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        child: SafeArea(
          child: WebView(
            navigationDelegate: (NavigationRequest request) {
              final uri = Uri.parse(request.url);
              final payerId = uri.queryParameters['transaction_id'];
              final status = uri.queryParameters['status'];
              if (status == 'successful') {
                if (payerId != null) {
                  Timer(Duration(seconds: 4), () async {
                    SharedPreferences localStorage =
                        await SharedPreferences.getInstance();
                    localStorage.remove('addValue');
                    // showDialog(
                    //   context: context,
                    //   builder: (_) => AssetGiffyDialog(
                    //     onlyOkButton: true,
                    //     image: Image(
                    //       image: AssetImage(
                    //         'assets/images/payment_success.png',
                    //       ),
                    //       fit: BoxFit.fill,
                    //     ),
                    //     title: Text(
                    //       'Payment Successful',
                    //       style: TextStyle(
                    //         color: darkBlue,
                    //         fontSize: 20,
                    //         fontFamily: 'PoppinsMedium',
                    //       ),
                    //     ),
                    //     description: Text(
                    //       'Booking Confirmed',
                    //       style: TextStyle(
                    //         color: darkBlue,
                    //         fontSize: 25,
                    //         fontFamily: 'PoppinsMedium',
                    //       ),
                    //     ),
                    //     entryAnimation: EntryAnimation.TOP_LEFT,
                    //     buttonOkColor: Colors.green[400],
                    //     onOkButtonPressed: () {
                    //       Navigator.pushNamedAndRemoveUntil(
                    //           context, '/HomePage', (route) => false);
                    //     },
                    //   ),
                    // );
                    Navigator.pushNamedAndRemoveUntil(
                        context, '/HomePage', (route) => false);
                  });
                }
              }
              return NavigationDecision.navigate;
            },
            initialUrl: widget.url,
            javascriptMode: JavascriptMode.unrestricted,
          ),
        ),
      ),
    );
  }
}
