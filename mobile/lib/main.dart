import 'package:flutter/material.dart';
import 'screens/welcome_screen.dart';
// import 'screens/home_screen.dart'; // Uncomment this line to test home directly

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Prime Studio',
      theme: ThemeData(primarySwatch: Colors.blue, fontFamily: 'Roboto'),
      home: const WelcomeScreen(), 
      debugShowCheckedModeBanner: false,
    );
  }
}
