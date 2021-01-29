# Simple-Login
This is a simple login file aimed at authenticating the user.
My main aim is to showcase skill in PHP language.

## Table of contents
* [Technologies](#technologies)
* [Launch](#launch)

## Technologies
created with:
* Bootstrap version 4.5
* PHP version 7.4

## Launch
* To run this project you need to have PHP installed as well as Mysql Database.
* You can install XAMPP and it comes shipped with both technologies.
* You also need a browser preferably Google Chrome or Mozilla Firefox.
#### Database
First create the datadase using this sql command.
```
CREATE DATABASE test
```
Then create a table using this sql command
```
CREATE TABLE members(
id int(11) PRIMARY KEY AUTO_INCREMENT,
name varchar(255) NOT NULL,
email varchar(255) NOT NULL,
username varchar(255) NOT NULL,
password varchar(255) NOT NULL
)
```
Later ensure that your correctly reference your server details, then you can launch the project.
