#include "DHT.h"
#include <ESP8266WiFi.h>
#include <WiFiClient.h>

#define DHTPIN D2
#define DHTTYPE DHT21
DHT dht(DHTPIN, DHTTYPE);
float humidityData;
float temperatureData;
const char* ssid = "Upoma";
const char* password = "12345678";
char server[] = "192.168.0.101"; // Replace with your server IP address
WiFiClient client;

// Motor control pin
const int motorPin = D1; // Change to your motor control pin

void setup() {
  Serial.begin(115200);
  delay(500);
  dht.begin();

  // Connect to WiFi network
  Serial.println();
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");

  Serial.println("Server started");
  Serial.print("Local IP Address: ");
  Serial.println(WiFi.localIP());
  delay(500);
  Serial.println("Connecting to server...");

  // Initialize motor control pin
  pinMode(motorPin, OUTPUT);
  digitalWrite(motorPin, LOW); // Initially turn off the motor
}

void loop() {
  humidityData = dht.readHumidity();
  temperatureData = dht.readTemperature();
  String water_level = getWaterLevelStatus();

  // Control the motor based on the temperature or water level
   if (water_level == "High" || temperatureData < 25) {
    digitalWrite(motorPin, LOW); // Turn off the motor when water level is high or temperature is high
  } else {
    digitalWrite(motorPin, HIGH); // Turn on the motor when water level is not high and temperature is not high
  }

  Sending_To_phpmyadmindatabase(humidityData, temperatureData, water_level);
  delay(500); // interval
}

String getWaterLevelStatus() {
  int water_level = analogRead(A0); // Read the analog value from the water level sensor


  if (water_level < 250) {
    return "Low";
  } else if (water_level >= 250 && water_level < 350) {
    return "Medium";
  } else {
    return "High";
  }
}

void Sending_To_phpmyadmindatabase(float humidity, float temperature, String water_level) {
  if (client.connect(server, 80)) {
    Serial.println("Connected to server");

    // Make an HTTP request with all parameters
    Serial.print("GET /testcode/dht.php?humidity=");
    client.print("GET /testcode/dht.php?humidity=");
    Serial.println(humidity);
    client.print(humidity);

    client.print("&temperature=");
    Serial.print("temperature=");
    client.print(temperature);

    // Add water_level parameter
    client.print("&water_level=");
    Serial.print("water_level=");
    client.print(water_level);

    client.println(" HTTP/1.1");
    client.print("Host: ");
    client.println(server); // Replace with your server's host
    client.println("Connection: close");
    client.println();
  } else {
    Serial.println("Connection to server failed");
  }
}
