#include <LoRa.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const int SS_PIN = 5; // LoRa radio chip select
const int RST_PIN = 14; // LoRa radio reset
const int DI0_PIN = 27; // Change for ESP32 depending on your module

const long frequency = 433E6;
const long BAUD_RATE = 9600;

void setup() {
  Serial.begin(BAUD_RATE);
  while (!Serial);

  if (!LoRa.begin(frequency)) {
    Serial.println("LoRa initialization failed.");
    while (1);
  }

  Serial.println("LoRa Initialized");
}

void loop() {
  // Try to parse packet
  int packetSize = LoRa.parsePacket();
  if (packetSize) {
    // Received a packet
    Serial.println("Received packet!");
    
    // Read packet
    String receivedMessage = "";
    while (LoRa.available()) {
      receivedMessage += (char)LoRa.read();
    }
    
    Serial.println("Received message: " + receivedMessage);
    
    // Parse JSON
    DynamicJsonDocument doc(1024);
    DeserializationError error = deserializeJson(doc, receivedMessage);
    if (error) {
      Serial.println("JSON parsing failed");
      return;
    }

    // Extract UID and URL
    String UID = doc["UID"];
    String URL = doc["URL"];

    // Make HTTP request to check balance
    HTTPClient http;
    String checkBalanceURL = "http://xtreamdevelopers.lk/check_balance?uid=" + UID;
    http.begin(checkBalanceURL);
    int httpResponseCode = http.GET();
    if (httpResponseCode == 200) {
      String payload = http.getString();
      if (payload == "1") {
        // Make HTTP request to call URL
        HTTPClient httpCall;
        httpCall.begin(URL);
        int callResponseCode = httpCall.GET();
        if (callResponseCode == 200) {
          String callResponse = httpCall.getString();
          // Send response to server
          String activityURL = "http://xtreamdevelopers.lk/network_act?uid=" + UID + "&response=" + callResponse;
          httpCall.begin(activityURL);
          int activityResponseCode = httpCall.GET();
          if (activityResponseCode == 200) {
            Serial.println("Activity logged successfully");
          } else {
            Serial.println("Failed to log activity");
          }
        } else {
          Serial.println("Failed to call URL");
        }
      } else {
        Serial.println("Insufficient balance");
      }
    } else {
      Serial.println("Failed to check balance");
    }

    http.end();
  }
}
