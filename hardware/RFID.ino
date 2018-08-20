// RFID 선언부 ㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡ

#include <AddicoreRFID.h>
#include <SPI.h>
 
#define    uchar    unsigned char
#define    uint    unsigned int
 
//4 bytes tag serial number, the first 5 bytes for the checksum byte 
uchar serNumA[5];
 
uchar fifobytes;
uchar fifoValue;
 
AddicoreRFID myRFID; // create AddicoreRFID object to control the RFID module
 
/////////////////////////////////////////////////////////////////////
//set the pins
/////////////////////////////////////////////////////////////////////
const int chipSelectPin = 10;
const int NRSTPD = 5;
const int speakerPin = 8; //스피커가 연결된 디지털핀 설정
 
//Maximum length of the array
#define MAX_LEN 16
 
// WIFI 선언부 ㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡ

#include <SoftwareSerial.h>
 
#include "ESP8266.h"
 
#define SSID "abcd"
#define PASS "11111111"
#define DST_IP "52.78.83.17" //baidu.com
 
SoftwareSerial esp8266Serial = SoftwareSerial(2, 3);
ESP8266 wifi = ESP8266(esp8266Serial);

// SetUp ㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡ

void setup()
{
    Serial.begin(9600);

// RFID in SetUp ㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡ
    // start the SPI library:
    SPI.begin();
    
    pinMode(chipSelectPin,OUTPUT);              // Set digital pin 10 as OUTPUT to connect it to the RFID /ENABLE pin 
    digitalWrite(chipSelectPin, LOW);         // Activate the RFID reader
    pinMode(NRSTPD,OUTPUT);                     // Set digital pin 10 , Not Reset and Power-down
    digitalWrite(NRSTPD, HIGH);
   
    myRFID.AddicoreRFID_Init();        

// WIFI in SetUp ㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡ 
    // ESP8266
    esp8266Serial.begin(9600);
    wifi.begin();
    wifi.setTimeout(1000);
 
    /****************************************/
    /******       Basic commands       ******/
    /****************************************/
    // test
    Serial.print("test: ");
    Serial.println(getStatus(wifi.test()));
 
    // restart
    Serial.print("restart: ");
    Serial.println(getStatus(wifi.restart()));
 
    // getVersion
    char version[16] = {};
    Serial.print("getVersion: ");
    Serial.print(getStatus(wifi.getVersion(version, 16)));
    Serial.print(" : ");
    Serial.println(version);

    // getWifiMode
    ///*
    ESP8266WifiMode mode;
    Serial.print("getWifiMode: ");
    Serial.println(getStatus(wifi.getMode(&mode)));//*/
    Serial.print("Wifi mode>>:"); Serial.println(mode);
    if( mode != ESP8266_WIFI_STATION)
    {
    // setWifiMode
    Serial.print("setWifiMode: ");
    // Serial.println(getStatus(wifi.setMode(ESP8266_WIFI_ACCESSPOINT)));
    Serial.println(getStatus(wifi.setMode(ESP8266_WIFI_STATION )));
    }
    else
    Serial.println("already ESP8266_WIFI_STATION mode");
 
    /****************************************/
    /******        WiFi commands       ******/
    /****************************************/
    // joinAP
    Serial.print("joinAP: ");
    Serial.println(getStatus(wifi.joinAP(SSID, PASS)));
 
 
    /****************************************/
    /******       TCP/IP commands      ******/
    /****************************************/
    // connect
    Serial.print("connect: ");
    Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
 
    // send
    Serial.print("send: ");
    //Serial.println(getStatus(wifi.send("GET /realtime/supplementOK/113 HTTP/1.0\r\n\r\n")));
    //Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/5 HTTP/1.0\r\n\r\n")));
 
}

// LOOP ㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡ
 
void loop()
{

// WIFI in LOOP ㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡㅡ 

  uchar i, tmp, checksum1;
  uchar status;
  uchar str[MAX_LEN];
  uchar RC_size;
  uchar blockAddr;    //Selection operation block address 0 to 63
  String mynum = "";
  str[1] = 0x4400;
        
    //RFID 태그의 타입을 리턴
    status = myRFID.AddicoreRFID_Request(PICC_REQIDL, str);    
    if (status == MI_OK)    //MIFARE 카드일때만 작동
    {
          tone(speakerPin,2000,100);
          Serial.println("RFID tag detected");
            Serial.print(str[0],BIN);
          Serial.print(" , ");
            Serial.print(str[1],BIN);
          Serial.println(" ");
    }
 
  //RFID 충돌방지, RFID 태그의 ID값(시리얼넘버) 등 저장된 값을 리턴함. 4Byte
    status = myRFID.AddicoreRFID_Anticoll(str);
    if (status == MI_OK)      //MIFARE 카드일때만 작동
    {
          checksum1 = str[0] ^ str[1] ^ str[2] ^ str[3];
          Serial.println("The tag's number is  : ");
            //Serial.print(2);
            Serial.print(str[0]);
          Serial.print(" , ");
            Serial.print(str[1],BIN);
          Serial.print(" , ");
            Serial.print(str[2],BIN);
          Serial.print(" , ");
            Serial.print(str[3],BIN);
          Serial.print(" , ");
            Serial.print(str[4],BIN);
          Serial.print(" , ");
          Serial.println(checksum1,BIN);
           
            // Should really check all pairs, but for now we'll just use the first
            if(str[0] == 224)                      //RFID 태그의 ID값이 224번이면 Gil Dong의 카드
            {
                Serial.print("Hello Gil Dong!\n");
            } else if(str[0] == 170) {             //RFID 태그의 ID값이 170번이면 Kang Min의 카드
                Serial.print("Hello Kang Min!\n");
            }
            Serial.println();
            Serial.println("write here something");
    
            /****************************************/
            /******       TCP/IP commands      ******/
            /****************************************/
            // connect
            Serial.print("connect: ");
            Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
         
            // send
            Serial.print("send: ");
            Serial.println(getStatus(wifi.send("GET /realtime/supplementOK/113 HTTP/1.0\r\n\r\n")));
                        
            delay(1000);
    }
        myRFID.AddicoreRFID_Halt();           //Command tag into hibernation   
}
 
String getStatus(bool status)
{
    if (status)
        return "OK";
 
    return "KO";
}
 
String getStatus(ESP8266CommandStatus status)
{
    switch (status) {
    case ESP8266_COMMAND_INVALID:
        return "INVALID";
        break;
 
    case ESP8266_COMMAND_TIMEOUT:
        return "TIMEOUT";
        break;
 
    case ESP8266_COMMAND_OK:
        return "OK";
        break;
 
    case ESP8266_COMMAND_NO_CHANGE:
        return "NO CHANGE";
        break;
 
    case ESP8266_COMMAND_ERROR:
        return "ERROR";
        break;
 
    case ESP8266_COMMAND_NO_LINK:
        return "NO LINK";
        break;
 
    case ESP8266_COMMAND_TOO_LONG:
        return "TOO LONG";
        break;
 
    case ESP8266_COMMAND_FAIL:
        return "FAIL";
        break;
 
    default:
        return "UNKNOWN COMMAND STATUS";
        break;
    }
}
 
String getRole(ESP8266Role role)
{
    switch (role) {
    case ESP8266_ROLE_CLIENT:
        return "CLIENT";
        break;
 
    case ESP8266_ROLE_SERVER:
        return "SERVER";
        break;
 
    default:
        return "UNKNOWN ROLE";
        break;
    }
}
 
String getProtocol(ESP8266Protocol protocol)
{
    switch (protocol) {
    case ESP8266_PROTOCOL_TCP:
        return "TCP";
        break;
 
    case ESP8266_PROTOCOL_UDP:
        return "UDP";
        break;
 
    default:
        return "UNKNOWN PROTOCOL";
        break;
    }
}
