//ㅡㅡㅡㅡㅡWIFI 선언부ㅡㅡㅡㅡㅡ 

#include <SoftwareSerial.h>
 
#include "ESP8266.h"
 
#define SSID "abcd"
#define PASS "11111111"
#define DST_IP "52.78.83.17" //baidu.com

SoftwareSerial esp8266Serial = SoftwareSerial(12, 13);
ESP8266 wifi = ESP8266(esp8266Serial);

//ㅡㅡㅡㅡㅡWIFI 선언부ㅡㅡㅡㅡㅡ

//ㅡㅡㅡㅡㅡLCD 선언부ㅡㅡㅡㅡㅡ

#include <TFT.h>                // LCD
#include <SPI.h>                // LCD

#define CS   10   // LCD
#define DC   9    // LCD
#define RESET  8  // LCD

char printout[6];           // 출력할 문자 저장 배열
TFT myScreen = TFT(CS, DC, RESET);


//ㅡㅡㅡㅡㅡLCD 선언부ㅡㅡㅡㅡㅡ

//ㅡㅡㅡㅡㅡSERVO MOTOR 선언부ㅡㅡㅡㅡㅡ

#include <Servo.h>

Servo myservo1;
Servo myservo2;
Servo myservo3;
Servo myservo4;
Servo myservo5;
Servo myservo6;
Servo myservo7;
Servo myservo8;
Servo myservo9;
Servo myservo10;
Servo myservo11;
Servo myservo12;
Servo myservo13;
Servo myservo14;
Servo myservo15;
Servo myservo16;

//ㅡㅡㅡㅡㅡSERVO MOTOR 선언부ㅡㅡㅡㅡㅡ

int money = 1500;

void setup()
{
  Serial.begin(9600);

  pinMode(11, INPUT); // 버튼
  pinMode(12, INPUT); // 버튼
  pinMode(13, INPUT); // 버튼
  pinMode(14, INPUT); // 버튼
  pinMode(15, INPUT); // 버튼
  pinMode(16, INPUT); // 버튼
  pinMode(17, INPUT); // 버튼
  pinMode(18, INPUT); // 버튼

//  myservo1.attach(4); // 서보모터
//  myservo2.attach(5); // 서보모터

//ㅡㅡㅡㅡㅡWIFI IN SETUPㅡㅡㅡㅡㅡ 

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

//ㅡㅡㅡㅡㅡWIFI IN SETUPㅡㅡㅡㅡㅡ  

//ㅡㅡㅡㅡㅡLCD IN SETUPㅡㅡㅡㅡㅡ 

  myScreen.begin();                       // LCD 시작
  myScreen.background(0,0,0);             // LCD 배경 검정색 지정
  myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
  myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
  myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
  myScreen.text("1500", 40, 50);           // LCD (50, 100)위치에 글 출력
  myScreen.setTextSize(2); 
  myScreen.text("KINOTUNAGARI", 20, 100);

//ㅡㅡㅡㅡㅡLCD IN SETUPㅡㅡㅡㅡㅡ 
}

void loop()
{

  int value = digitalRead(11); // BUTTON

  if(value == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo1.attach(1);
    for(int i = 90; i > 0; --i){
      myservo1.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo1.write(i);
      delay(10);
    }
    myservo1.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/1 HTTP/1.0\r\n\r\n")));      
    }

    myservo2.attach(2);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo2.write(i);
      
    }
    //delay(150);
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo2.write(i);
      
    }
    myservo2.detach();
     
  }

  int value2 = digitalRead(12); // BUTTON

  if(value2 == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo3.attach(3);
    for(int i = 90; i > 0; --i){
      myservo3.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo3.write(i);
      delay(10);
    }
    myservo3.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/2 HTTP/1.0\r\n\r\n")));      
    }

    myservo4.attach(4);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo4.write(i);
      
    }
    //delay(150);
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo4.write(i);
      
    }
    myservo4.detach();
     
  }

  int value3 = digitalRead(13); // BUTTON

  if(value3 == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo5.attach(5);
    for(int i = 90; i > 0; --i){
      myservo5.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo5.write(i);
      delay(10);
    }
    myservo5.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/3 HTTP/1.0\r\n\r\n")));      
    }

    myservo6.attach(6);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo6.write(i);
      
    }
    //delay(150);
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo6.write(i);
      
    }
    myservo6.detach();
     
  }

  int value4 = digitalRead(14); // BUTTON

  if(value4 == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo7.attach(7);
    for(int i = 90; i > 0; --i){
      myservo7.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo7.write(i);
      delay(10);
    }
    myservo7.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/4 HTTP/1.0\r\n\r\n")));      
    }

    myservo8.attach(8);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo6.write(i);
      
    }
   
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo8.write(i);
      
    }
    myservo8.detach();
     
  }

  int value5 = digitalRead(15); // BUTTON

  if(value5 == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo9.attach(9);
    for(int i = 90; i > 0; --i){
      myservo9.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo9.write(i);
      delay(10);
    }
    myservo9.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/5 HTTP/1.0\r\n\r\n")));      
    }

    myservo10.attach(10);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo10.write(i);
      
    }
   
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo10.write(i);
      
    }
    myservo10.detach();
     
  }

  int value6 = digitalRead(16); // BUTTON

  if(value5 == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo11.attach(11);
    for(int i = 90; i > 0; --i){
      myservo11.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo11.write(i);
      delay(10);
    }
    myservo11.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/6 HTTP/1.0\r\n\r\n")));      
    }

    myservo12.attach(12);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo12.write(i);
      
    }
   
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo12.write(i);
      
    }
    myservo12.detach();
     
  }

  int value7 = digitalRead(17); // BUTTON

  if(value7 == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo13.attach(13);
    for(int i = 90; i > 0; --i){
      myservo13.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo13.write(i);
      delay(10);
    }
    myservo13.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/7 HTTP/1.0\r\n\r\n")));      
    }

    myservo14.attach(14);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo14.write(i);
      
    }
   
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo14.write(i);
      
    }
    myservo14.detach();
     
  }

  int value8 = digitalRead(18); // BUTTON

  if(value8 == HIGH){  // BUTTON 누르면 - LCD -500, 서보모터, 데이터전송

    myScreen.background(0,0,0);             // LCD 배경 검정색 지정
    myScreen.stroke(255,255,255);           // LCD 글자색 흰색 지정
  
    myScreen.setTextSize(3);                // LCD 글자 크기 2로 지정
    myScreen.text("6-TEAM", 30, 0);    // LCD (10,0)위치에 글 출력
    money -= 500;
    String el = String(money);
    el.toCharArray(printout, 6);
    myScreen.text(printout, 40, 50);
    myScreen.setTextSize(2); 
    myScreen.text("KINOTUNAGARI", 20, 100);

    myservo15.attach(135);
    for(int i = 90; i > 0; --i){
      myservo15.write(i);
      delay(10);
    }
    for(int i = 0; i  < 90; ++i){
      myservo15.write(i);
      delay(10);
    }
    myservo15.detach();
   
    if(money >= 0){

      Serial.println("3개 까지만 데이터가 전달됩니다.");
      
      // connect
      Serial.print("connect: ");
      Serial.println(getStatus(wifi.connect(ESP8266_PROTOCOL_TCP, DST_IP, 80)));
   
      // send
      Serial.print("send: ");
      Serial.println(getStatus(wifi.send("GET /realtime/sendDataFromVdVersionTwo/113/8 HTTP/1.0\r\n\r\n")));      
    }

    myservo16.attach(16);
    for(int i = 80; i > 0; i--){
      delay(10);
      myservo16.write(i);
      
    }
   
    for(int i = 0; i  < 80; i++){
      delay(10);
      myservo16.write(i);
      
    }
    myservo16.detach();
     
  }
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
