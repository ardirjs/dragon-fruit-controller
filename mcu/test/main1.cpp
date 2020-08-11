#include <Arduino.h>
#include <SoftwareSerial.h>
#include <ZMPT101B.h>
#include <ACS712.h>
#include <LiquidCrystal.h>
#include <EEPROM.h>
#include <SIM800L.h>
#include <ArduinoJson.h>

#define DEVICE "12345678"

#define DEBUG true
#define DEBUG_SIM false
#define BAUD 9600
#define BAUD_SIM 9600

#define RS_SIM 11
#define RX_SIM 12
#define TX_SIM 13

#define BIT_ACS712 A0
#define BIT_ZMPT101B A1
#define RELAY_CH1 7
#define RELAY_CH2 8
#define INDICATOR_STANDBY A3
#define INDICATOR_SENDING A4
#define INDICATOR_NETWORK A5

#define BUFFER 77
#define SUCCESS 200
#define INTERVAL 50
#define TIMEOUT 10000
#define ID "id"
#define LA "la"
#define LB "lb"
#define VV "vv"
#define VA "va"
#define APN "internet"
#define HOST "http://dragon.rjsdevs.icu/get/save.php"

SIM800L *SIM;
ZMPT101B voltageSensor(BIT_ZMPT101B);
ACS712 currentSensor(ACS712_20A, BIT_ACS712);

uint8_t indicator = 0;
int data = 0;

void pinModeInit(void) {
  const int num = 6;
  static uint8_t pin[num] = {
    RS_SIM,
    RELAY_CH1,
    RELAY_CH2,
    INDICATOR_STANDBY,
    INDICATOR_SENDING,
    INDICATOR_NETWORK,
  };
  for (uint8_t i = 0; i < num; i++) {
    pinMode(pin[i], true);
    digitalWrite(pin[i], false);
  }
}

void interruptTimerInit(void) {
  TCCR2A = 0x00;
  TCCR2B = 0x00;
  TCNT2 = 0x00;
  OCR2A = 0xf9;
  TCCR2A |= 1 << WGM21;
  TCCR2B |= 1 << CS21;
  TIMSK2 |= 1 << OCIE2A;
  sei();
}

void indicatorTurning(uint8_t value) {
  digitalWrite(INDICATOR_STANDBY, value == INDICATOR_STANDBY ? 1 : 0);
  digitalWrite(INDICATOR_NETWORK, value == INDICATOR_NETWORK ? 1 : 0);
  digitalWrite(INDICATOR_SENDING, value == INDICATOR_SENDING ? 1 : 0);
}

void simReset(void) {
  for (uint8_t i = 0; i < 3; i++) {
    digitalWrite(RS_SIM, !(i % 2));
    delay(100);
  }
}

void simConnect(void) {
  simReset();
  while (!SIM->isReady());
  indicator = INDICATOR_NETWORK;
  while (!SIM->getSignal());
  while (!SIM->getRegistrationStatus());
  while (!SIM->setupGPRS(APN));
  while (!SIM->connectGPRS());
}

void simInit(void) {
  SoftwareSerial *stream = new SoftwareSerial(TX_SIM, RX_SIM);
  stream->begin(BAUD_SIM);
  #if !DEBUG_SIM
    SIM = new SIM800L((Stream*)stream, 0, 200, 512);
  #else
    SIM = new SIM800L((Stream*)stream, 0, 200, 512, (Stream*)&Serial);
  #endif
}

void simStoreData(int la, int lb, int vv, float va) {
  simConnect();
  String urlpath = (String)HOST;
  urlpath += "?";
  urlpath += (String)ID;
  urlpath += "=";
  urlpath += (String)DEVICE;
  urlpath += "&";
  urlpath += (String)LA;
  urlpath += "=";
  urlpath += (String)la;
  urlpath += "&";
  urlpath += (String)LB;
  urlpath += "=";
  urlpath += (String)lb;
  urlpath += "&";
  urlpath += (String)VV;
  urlpath += "=";
  urlpath += (String)vv;
  urlpath += "&";
  urlpath += (String)VA;
  urlpath += "=";
  urlpath += (String)va;
  char url[BUFFER];
  urlpath.toCharArray(url, BUFFER);
  String packetReceived;
  indicator = INDICATOR_SENDING;
  if (SIM->doGet(url, TIMEOUT) == SUCCESS) {
    packetReceived = SIM->getDataReceived();
    #if DEBUG
      Serial.print("Received: ");
      Serial.println(packetReceived);
    #endif
  }
  if (packetReceived.length()) {
    char packet[BUFFER];
    packetReceived.toCharArray(packet, BUFFER);
    StaticJsonDocument<200> document;
    DeserializationError error = deserializeJson(document, packet);
    if (error) {
      #if DEBUG
        Serial.println("Parse Json Error");
      #endif
      return;
    }
    data = document["a"][0];
    digitalWrite(INDICATOR_NETWORK, data);
    #if DEBUG
      Serial.print("Data: ");
      Serial.println(data);
    #endif
  }
}

void setup(void) {
  #if DEBUG
    Serial.begin(9600);
  #endif
  //simInit();
  pinModeInit();
  interruptTimerInit();
  voltageSensor.calibrate();
  currentSensor.calibrate();
}

void loop(void) {
  indicator = INDICATOR_STANDBY;
  static int values = 0;
  values++;
  //simStoreData(data, 0, 3, values);
  float U = voltageSensor.getVoltageAC();
  float I = currentSensor.getCurrentAC();

  // To calculate the power we need voltage multiplied by current
  float P = U * I;

  Serial.println(String("U = ") + U + " V");
  Serial.println(String("I = ") + I + " A");
  Serial.println(String("P = ") + P + " Watts");

  delay(1000);
}

ISR(TIMER2_COMPA_vect) {
  static int interval = 0;
  interval++;
  if (interval < 100) {
    indicatorTurning(indicator);
  } else {
    digitalWrite(INDICATOR_NETWORK, false);
    digitalWrite(INDICATOR_SENDING, false);
    interval = interval > 200 ? 0 : interval;
  }
}
