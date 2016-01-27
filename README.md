# AValcohol [![Build Status](https://magnum.travis-ci.com/fpm5022/AValcohol.svg?token=crFjMNDDdzj4qoYgDDWv&branch=master)](https://magnum.travis-ci.com/fpm5022/AValcohol)
##Initliazing this project locally:
1. clone this repo
2. open up terminal and navigate to the cloned repo
3. type `bower install` to install the front-end dependencies (make sure you have bower installed first)
4. type `php composer.phar install` (make sure you have composer installed first)

##Current Deploy Process To dev.avalcohol.com
1. push changes to this repo
2. cd to AValcohol-dev
4. php sync.php
5. git push
6. ssh onto server
7. cd www/dev/AValcohol-dev/
8. git pull

##API Design Philosophy (loosely followed for the time being)
###Middleware
performs logic that does not require any data (IE whether or not the store is open for orders, etc)
certain authentication
calls controller

###Controllers
perform required data validation
calls service
return response

###Services
can pull in other services if needed to communicate with other services (not other repositories)???
handles business rules (permissions, etc) by calling the repositories methods and arranging logic based on repositories response
throws business exceptions
calls repository
fire events

###Repositories
performs and returns individual logic. each method performs one task
strict parameters listed in each function
throws find or fail exceptions

###Models
eloquent stuff
