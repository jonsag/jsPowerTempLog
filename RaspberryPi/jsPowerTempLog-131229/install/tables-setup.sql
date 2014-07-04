CREATE TABLE powerLog (
        id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        ts TIMESTAMP,
	currentR1 FLOAT,
	currentS2 FLOAT,
	currentT3 FLOAT,
	currentAverageR1 FLOAT,
	currentAverageS2 FLOAT,
	currentAverageT3 FLOAT,
	temp FLOAT,
	pulses INT,
	event char(255),
        PRIMARY KEY (id)
) CHARACTER SET UTF8;

CREATE TABLE tempLog (
        id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        ts TIMESTAMP,
	temp00 FLOAT,
        temp01 FLOAT,
        temp02 FLOAT,
        temp03 FLOAT,
        temp04 FLOAT,
        temp05 FLOAT,
        temp06 FLOAT,
        temp07 FLOAT,
        temp08 FLOAT,
        temp09 FLOAT,
        temp10 FLOAT,
        event char(255),
        PRIMARY KEY (id)
) CHARACTER SET UTF8;
