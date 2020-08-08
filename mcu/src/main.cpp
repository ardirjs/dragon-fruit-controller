#include <Arduino.h>
#include <SoftwareSerial.h>

void setup() {
  pinMode(A4, 1);
  Serial.begin(9600);
  Serial.println("AS");
}

void loop() {
  // put your main code here, to run repeatedly:
}
