#include <Arduino.h>
#include <SIM800L.h>
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

#define BUFFERSIZE 77
#define SUCCESSSTATUS 200
#define INTERVAL 50
#define TIMEOUT 10000

#define STRDEVIC "id"
#define STRLAMPA "la"
#define STRLAMPB "lb"
#define STRVOLTS "vv"
#define STRAMPER "va"

#define APN "internet"
#define HOST "http://dragon.rjsdevs.icu/get/save.php"

static uint8_t status = 0;

/*
 * class initialize
 */

SIM800L *SIM;

void pinInit(void) {
  pinMode(ACS712, false);
  pinMode(ZMPT101B, false);
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

void simStartInit(void) {
  SoftwareSerial *stream = new SoftwareSerial(SIMTX, SIMRX);
  stream->begin(BAUDRATE);
  #if !DEBUGSIM
  SIM = new SIM800L((Stream*)stream, 0, 200, 512);
  #else
  SIM = new SIM800L((Stream*)stream, 0, 200, 512, (Stream*)&Serial);
  #endif
}

void simIsReset(void) {
  for (int i = 0; i < 3; i++) {
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

float voltage(void) {
  float adc = 0, maxAdc = 0, minAdc = ADCSCALE;
  for (int i = 0; i < ADCSAMPLE; i++) {
    adc = READADC(ZMPT101B);
    maxAdc = adc > maxAdc ? adc : maxAdc;
    minAdc = adc < minAdc ? adc : minAdc;
  }
  adc = (maxAdc - minAdc) * (ADCVREF / ADCSCALE);
  float output = (adc > 0.5 ? adc : 0) * 345.0000;
  #if DEBUG
  Serial.println((String)"ZMPT101B \t vadc: " + (String)adc + " volt " + (String)output);
  #endif
  return output;
}

float current(void) {
  float adc = 0, maxAdc = 0, minAdc = ADCSCALE;
  for (int i = 0; i < ADCSAMPLE; i++) {
    adc = READADC(ACS712);
    maxAdc = adc > maxAdc ? adc : maxAdc;
    minAdc = adc < minAdc ? adc : minAdc;
  }
  adc = (maxAdc - minAdc) * (ADCVREF / ADCSCALE);
  float output = (adc > 0.13 ? adc : 0) * 1.1034;
  #if DEBUG
  Serial.println((String)"ACS712 \t vadc: " + (String)adc + " arus " + (String)output);
  #endif
  return output;
}

void setup(void) {
  #if DEBUG
  Serial.begin(BAUDRATE);
  while (!Serial);
  #endif
}

void loop(void) {
  delay(100);
  //voltage();
  current();
}
