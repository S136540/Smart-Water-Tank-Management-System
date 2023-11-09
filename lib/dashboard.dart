import 'package:flutter/material.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'register.dart';

class DashBoard extends StatelessWidget {
  const DashBoard({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Smart Water Management System',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      debugShowCheckedModeBanner: false,
      home: const MyDataView(),
    );
  }
}

class MyDataView extends StatefulWidget {
  const MyDataView({Key? key}) : super(key: key);

  @override
  _MyDataViewState createState() => _MyDataViewState();
}

class _MyDataViewState extends State<MyDataView> {
  List<Map<String, dynamic>> records = [];

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  Future<void> fetchData() async {
    try {
      String uri = "http://192.168.0.102/testcode/view.php"; // Replace with your API URL
      var res = await http.get(Uri.parse(uri));

      if (res.statusCode == 200) {
        var response = json.decode(res.body);
        setState(() {
          records = List.from(response["records"]);
        });
      } else {
        print("HTTP Error: ${res.statusCode}");
      }
    } catch (e) {
      print("Error: $e");
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Data View"), // Removed DEBUG mark from the app bar title
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            // Navigate back to the Register page
            Navigator.of(context).pushAndRemoveUntil(
              MaterialPageRoute(builder: (context) => Register()),
                  (route) => false,
            );
          },
        ),
      ),
      body: ListView.builder(
        itemCount: records.length,
        itemBuilder: (context, index) {
          return ListTile(
            title: Text("ID: ${records[index]['id']}"),
            subtitle: Text(
              "Temperature: ${records[index]['temperature']}°C\n"
                  "Humidity: ${records[index]['humidity']}%\n"
                  "Water Level: ${records[index]['water_level']}",
            ),
          );
        },
      ),
    );
  }
}