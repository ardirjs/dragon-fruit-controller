#include <Arduino.h>
#include <EEPROM.h>
#include <SIM800L.h>
#include <ArduinoJson.h>
#include <SoftwareSerial.h>

#define DEBUG true
#define DEBUGSIM false
#define BAUDRATE 9600

#define ACS712 A0
#define ZMPT101B A1

#define ADCVREF 5.0
#define ADCSAMPLE 300
#define ADCSCALE 1023.0
#define READADC(a) analogRead(a)

#define SWITCHA 7
#define SWITCHB 8
#define STANDBY A3
#define SENDING A4
#define NETWORK A5

#define SIMRS 11
#define SIMRX 12
#define SIMTX 13

#define INTERVAL 50
#define TIMEOUT 10000
#define BUFFERSIZE 100
#define SUCCESSSTATUS 200

#define STRDEVIC "id"
#define STRLAMPA "la"
#define STRLAMPB "lb"
#define STRVOLTS "vv"
#define STRAMPER "va"
#define DEVICEID "12345678"

#define APN "internet"
#define HOST "http://dragon.rjsdevs.icu/get/save.php"

static uint8_t status = 0;

struct __a__ {
  uint8_t hour, mnut;
} timer;

struct __b__ {
  uint8_t lamp, hron, hrof, mnon, mnof;
} lampa, lampb;

/*
 * class initialize
 */

SIM800L *SIM;

void pinInit(void) {
  uint8_t NUM = 8;
  uint8_t pin[NUM] = {
    SWITCHA, SWITCHB, STANDBY, SENDING, NETWORK, SIMRS,
    ACS712, ZMPT101B
  };
  for (int i = 0; i < NUM; i++) {
    pinMode(pin[i], i < (NUM - 2) ? 1 : 0);
  }
}

void timerInterrupt(void) {
  TCCR2A = 0x00;
  TCCR2B = 0x00;
  TCNT2 = 0x00;
  OCR2A = 0xf9;
  TCCR2A |= 1 << WGM21;
  TCCR2B |= 1 << CS21;
  TIMSK2 |= 1 << OCIE2A;
  sei();
}

void indicator(int state) {
  digitalWrite(STANDBY, state == STANDBY ? 1 : 0);
  digitalWrite(SENDING, state == SENDING ? 1 : 0);
  digitalWrite(NETWORK, state == NETWORK ? 1 : 0);
}

String voltage(void) {
  float adc = 0, maxAdc = 0, minAdc = ADCSCALE;
  for (int i = 0; i < ADCSAMPLE; i++) {
    adc = READADC(ZMPT101B);
    maxAdc = adc > maxAdc ? adc : maxAdc;
    minAdc = adc < minAdc ? adc : minAdc;
  }
  adc = (maxAdc - minAdc) * (ADCVREF / ADCSCALE);
  float output = (adc > 0.5 ? adc : 0) * 345.5000;
  #if DEBUG
  Serial.println((String)"ZMPT101B : vadc: " + (String)adc + " volt " + (String)output);
  #endif
  char out[BUFFERSIZE / 5];
  return dtostrf(output, 4, 2, out);
}

String current(void) {
  float adc = 0, maxAdc = 0, minAdc = ADCSCALE;
  for (int i = 0; i < ADCSAMPLE; i++) {
    adc = READADC(ACS712);
    maxAdc = adc > maxAdc ? adc : maxAdc;
    minAdc = adc < minAdc ? adc : minAdc;
  }
  adc = (maxAdc - minAdc) * (ADCVREF / ADCSCALE);
  float output = (adc > 0.13 ? adc : 0) * 1.1034;
  #if DEBUG
  Serial.println((String)"ACS712   : vadc: " + (String)adc + " arus " + (String)output);
  #endif
  char out[BUFFERSIZE / 5];
  return dtostrf(output, 4, 2, out);
}

void simStartInit(void) {
  #if DEBUG
  Serial.begin(BAUDRATE);
  while (!Serial);
  #endif
  SoftwareSerial *stream = new SoftwareSerial(SIMTX, SIMRX);
  stream->begin(BAUDRATE);
  #if !DEBUGSIM
  SIM = new SIM800L((Stream*)stream, 0, BUFFERSIZE * 2, BUFFERSIZE * 2);
  #else
  SIM = new SIM800L((Stream*)stream, 0, BUFFERSIZE * 2, BUFFERSIZE * 2, (Stream*)&Serial);
  #endif
}

void simIsReset(void) {
  for (int i = 0; i < 5; i++) {
    digitalWrite(SIMRS, !(i % 2));
    delay(100);
  }
}

void simConnect(void) {
  simIsReset();
  while (!SIM->isReady());
  while (!SIM->getSignal());
  status = NETWORK;
  while (!SIM->getRegistrationStatus());
  while (!SIM->setupGPRS(APN));
  while (!SIM->connectGPRS());
  status = SENDING;
}

String simStoreData(void) {
  String urlpath = (String)HOST;
  urlpath += "?" + (String)STRDEVIC + "=" + (String)DEVICEID;
  urlpath += "&" + (String)STRLAMPA + "=" + (String)1;
  urlpath += "&" + (String)STRLAMPB + "=" + (String)1;
  urlpath += "&" + (String)STRVOLTS + "=" + (String)voltage();
  urlpath += "&" + (String)STRAMPER + "=" + (String)current();
  char url[BUFFERSIZE];
  urlpath.toCharArray(url, BUFFERSIZE);
  String packetReceived;
  simConnect();
  if (SIM->doGet(url, TIMEOUT) == SUCCESSSTATUS) {
    packetReceived = SIM->getDataReceived();
    #if DEBUG
    Serial.println("Send packet: " + (String)url);
    Serial.println("Recv packet: " + (String)packetReceived);
    #endif
  }
  if (packetReceived.length()) {
    return packetReceived;
  } else {
    return '\0';
  }
}

void simParseJson(String json) {
  if (json.length()) {
    char packet[BUFFERSIZE];
    json.toCharArray(packet, BUFFERSIZE);
    StaticJsonDocument<200> document;
    deserializeJson(document, packet);
    lampa.lamp = document["a"][0];
    lampa.hron = document["a"][1];
    lampa.hrof = document["a"][2];
    lampa.mnon = document["a"][3];
    lampa.mnof = document["a"][4];
    lampb.lamp = document["b"][0];
    lampb.hron = document["b"][1];
    lampb.hrof = document["b"][2];
    lampb.mnon = document["b"][3];
    lampb.mnof = document["b"][4];
    timer.hour = document["t"][0];
    timer.mnut = document["t"][1];
    #if DEBUG
    Serial.print("lama: " + (String)lampa.lamp);
    Serial.print(" hon: " + (String)lampa.hron + " hof: " + (String)lampa.hrof);
    Serial.print(" mon: " + (String)lampa.mnon + " mof: " + (String)lampa.mnof);
    Serial.println();
    Serial.print("lamb: " + (String)lampb.lamp);
    Serial.print(" hon: " + (String)lampb.hron + " hof: " + (String)lampb.hrof);
    Serial.print(" mon: " + (String)lampb.mnon + " mof: " + (String)lampb.mnof);
    Serial.println();
    Serial.print("hour: " + (String)timer.hour + " minute " + (String)timer.mnut);
    Serial.println();
    #endif
  }
}

void setup(void) {
  pinInit();
  simStartInit();
  timerInterrupt();
}

void loop(void) {
  status = STANDBY;
  delay(10000);
  simParseJson(simStoreData());
}

ISR(TIMER2_COMPA_vect) {
  static int counter = 0;
  counter++;
  if (counter < 500) {
    indicator(status);
  } else {
    digitalWrite(NETWORK, false);
    digitalWrite(SENDING, false);
    counter = counter > 1000 ? 0 : counter;
  }
}
