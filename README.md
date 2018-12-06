# Social network for connecting with people

## Overview

Social networking is one of the most common thing now-a-days.  In this project I created a basic site which resembles facebook during its initial stage.  Most of the functionalities like adding friends, posting messages and uploading photos has been included.

## Requirements and languages used

 - PHP
 - MYSQL
 - APACHE web server
 - Javascript(AJAX)
 - HTML and CSS

## Installation

PHP, MYSQL and APACHE web server can be installed separately and configured.  Or all of them can be easily installed by using LAMP(bitnami) in linux and WAMP in case of windows.

## Structure

The code base is constructed in such a way that for each page that we navigate throughout the site we have a separate PHP file containing the front end requirements in it and connecting to a main class in another
file for backend processing. Some of the important files are<br>
**index.php** - It is the homepage that shows the sign in page or connects to profile if already logged in.<br>
**email.php** - It handles the email verification by using phpmailer class.<br>
**app.php** - Contains the configurations for images.<br>
**dp.php** - Contains the login credentials for the database. <br>
**images, photos, post** - They contain the profile images, album photos and post images respectively <br>
**class2.php** - It contains the class comprising of all the functions needed.
for all the pages.  Every page calls this class and uses it accordingly. <br>

## Site functions

The users can create an account and after an email verification they will be able to access it.  Every user can search for other profiles and give them a friend request, post content with images, like and comment on other people's post and photos.  A user can also block other users.


## Issues
I created this site during the time when I started programming and learnt web development.  So I didn't use any modern PHP or Javascript frameworks for efficiency.  Since I didn't find time after that to modify the code base the code can be made more redundant and structured better for efficiency. 
