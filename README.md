# AValcohol
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
