#include <Wire.h> // specify use of Wire.h library.

byte blinkPin = 13;
byte SW0 = 4;
byte SW1 = 5;
byte SW2 = 6;


void setup()

{
  Wire.begin(); // join i2c bus (address optional for master)
  Serial.begin(9600);
  pinMode(blinkPin, OUTPUT);
  digitalWrite(blinkPin, 0);


  pinMode(SW0, INPUT);  // for this use a slide switch
  pinMode(SW1, INPUT);  // N.O. push button switch
  pinMode(SW2, INPUT);  // N.O. push button switch


  digitalWrite(SW0, HIGH); // pull-ups on
  digitalWrite(SW1, HIGH);
  digitalWrite(SW2, HIGH);
}

void loop()
{
  Wire.beginTransmission(0x68);
  Wire.write(0);
  Wire.endTransmission();

  Wire.requestFrom(0x68, 7);
  byte secs = Wire.receive();
  byte mins = Wire.receive();
  byte hrs = Wire.receive();
  byte day = Wire.receive();
  byte date = Wire.receive();
  byte month = Wire.receive();
  byte year = Wire.receive();

  // hours, minutes, seconds

  Serial.print("The time is "); 
  if (hrs < 10) Serial.print("0");
  Serial.print(hrs,HEX);    
  Serial.print(":");
  if (mins < 10) Serial.print("0");
  Serial.print(mins, HEX);
  Serial.print(":");
  if (secs < 10) Serial.print("0");
  Serial.println(secs, HEX);

  // use MM-DD-YYYY

  Serial.print("The date is "); 
  if (month < 10) Serial.print("0");
  Serial.print(month,HEX);    
  Serial.print("-");
  if (date < 10) Serial.print("0");
  Serial.print(date, HEX);
  Serial.print("-");
  Serial.print("20");
  if (year < 10) Serial.print("0");
  Serial.println(year, HEX);
  Serial.println();


  if (!(digitalRead(SW0))) set_time(); // hold the switch to set time
  delay(1000);    //wait a second before next output
  toggle(blinkPin);
} 


// toggle the state on a pin
void toggle(int pinNum) 
{  
  int pinState = digitalRead(pinNum);
  pinState = !pinState;
  digitalWrite(pinNum, pinState); 
}




void set_time()   {
  byte minutes = 0;
  byte hours = 0;

  while (!digitalRead(SW0))  // set time switch must be released to exit
  {
    while (!digitalRead(SW1)) // set minutes
    { 
      minutes++;          
      if ((minutes & 0x0f) > 9) minutes = minutes + 6;
      if (minutes > 0x59) minutes = 0;
      Serial.print("Minutes = ");
      if (minutes >= 9) Serial.print("0");
      Serial.println(minutes, HEX);

      delay(750);
    }

    while (!digitalRead(SW2)) // set hours
    { 
      hours++;          
      if ((hours & 0x0f) > 9) hours =  hours + 6;
      if (hours > 0x23) hours = 0;
      Serial.print("Hours = ");
      if (hours <= 9) Serial.print("0");
      Serial.println(hours, HEX);
      delay(750);
    }

    Wire.beginTransmission(0x68); // activate DS1307
    Wire.write(0); // where to begin
    Wire.write(0x00);          //seconds
    Wire.write(minutes);          //minutes
    Wire.write(0x80 | hours);    //hours (24hr time)
    Wire.write(0x06);  // Day 01-07
    Wire.write(0x01);  // Date 0-31
    Wire.write(0x05);  // month 0-12
    Wire.write(0x09);  // Year 00-99
    Wire.write(0x10); // Control 0x10 produces a 1 HZ square wave on pin 7. 
    Wire.endTransmission();
  }    
}
