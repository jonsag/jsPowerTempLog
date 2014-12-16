jsPowerTempLog
=================

RaspberryPi, arduino project


Installation
===============

RaspberryPi installation
--------------------
Download image from
    http://www.raspberrypi.org/downloads/

Select
    Raspbian Debian Wheezy
and download it

Write the .img-file with Win32 Disk Imager, found at
    http://sourceforge.net/projects/win32diskimager/

Insert the SD card into the RaspberryPi and boot up

RaspberryPi configuration
--------------------------
Select
    1 Expand Filesystem
Select
    Finish
and then
    Reboot

Login with
    pi / raspberry

$ sudo raspi-config

Select
4 Internationalisation Options
    11 Change Locale
Select at least
    en_GB.UTF-8.UTF-8
    sv_SE.UTF-8.UTF-8

Set
    en_GB.UTF-8
as default

Select
4 Internationalisation Options
12 Change Timezone
Select
    Europe -> Stockholm

Select
4 Internationalisation Options
13 Change Keyboard Layout
Select
    Generic 105-key (intl) PC
    Other
    Swedish
Swedish
The default for keyboard layout
For Compose key, set
    Right Control
Then set
    No
for use of Control+Alt+Backspace

Select
    8 Advanced Options
    A2 Hostname
Set to
    <hostname>

Select
    8 Advanced Options
    A3 Memory Split
Set
    16
to the video memory

Select
    8 Advanced Options
    A4 SSH
Select
    Enable
to enable ssh-server

Select
    8 Advanced Options
    A5 SPI
Select
    Yes
to load SPI kernel modules at start

Select
    2 Change User Password
Enter
    <user password>
twice to set new password for user pi

Select
    Finish
    Yes
to reboot

Login with
    pi / <user password>

$ sudo raspi-config

Select
    8 Advanced Options
    A9 Update
to update to latest version

Select
    Finish

$ sudo reboot

RaspberryPi updates and applications
--------------------------------------
$ sudo apt-get update
$ sudo apt-get install screen
$ screen -R apt-get
$ sudo su

# apt-get apache2 mysql-server php5 php5-mysql

Add password for mysql root user
    <root password>
twice

# apt-get install  rsync emacs php-elisp phpmyadmin

Choose
    apache2
for configuration of phpmyadmin

Answer
    Yes
to configure database for use with dbconfig-common
Enter
    <root password>
for root password and 
    <root password>
twice for phpmyadmins password

# passwd
Enter
    <root password>
twice to set root password

Modules for 1-wire devices
--------------------------------
Load modules for 1-wire
# modprobe w1-gpio
# modprobe w1-therm

Add modules for loading at boot
# emacs /etc/modules
    add    w1-gpio
    	   w1-therm

Setup webserver
--------------------------
Transfer directory
    jsPowerTempLog
to
    /var/www/

Change owner and group of files
# chown www-data:www-data -R /var/www

Install database
# cd /var/www/jsPowerTempLog/install
# mysql -u root -p<root password> < database-setup.sql
# mysql -u root -p<root password> powerTempLog < tables-setup.sql

Check the entries in
    add-1wire-devices.sql
edit them accordingly and
# mysql -u root -p<root password> powerTempLog < add-1wire-devices.sql

Check the
    config.php
that all URLs point to the places you want

Also check your
    /etc/hosts
file

Set up serial communication
# stty -F /dev/ttyUSB0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts

Add apache’s user to dialout
# usermod -a -G dialout www-data

Edit
		he2/emacs/sites-availa
Under
	<Directory /var/www/>
change
	AllowOverride None
to
	AllowOverride All
and then
# /etc/init.d/apache2 restart

Add cron jobs
# crontab -e
    add
*/2 * * * * /usr/bin/php /var/www/jsPowerTempLog/powerPoller.php 0 1 cron > /dev/null 2>&1
    */2 * * * * /usr/bin/php /var/www/jsPowerTempLog/tempPoller.php 0 1 cron > /dev/null 2>&1
    */2 * * * * /usr/bin/php /var/www/jsPowerTempLog/weatherPoller.php 0 1 cron > /dev/null 2>&1

Miscellaneous
===============
Add some nifty things to .bashrc
# emacs ~/.bashrc
    add
    alias list='ls -alFh'

Connect to arduino over USB-serial
# screen /dev/ttyUSB0 9600

To display temps from bash
# cat /sys/bus/w1/devices/<device code>/w1_slave
eg
# cat /sys/bus/w1/devices/28-000003c359ac/w1_slave
# cat /sys/bus/w1/devices/28-000003c37731/w1_slave

Backup and restore
--------------------
To do a backup of a SD card
# dd if=/dev/sdX conv=sync,noerror bs=64K | gzip -c  > filename.img.gz

To restore a backup to a SD card
# gunzip -c filename.img.gz | dd of=/dev/sdX
