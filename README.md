# Pilot Project

A practise project made in Symfony Framework for basic authentication, crud operations etc for making item's list and items as per user logged in
Technologies used : Syfmonfy, MYSQL, JQuery, Bootstrap etc.

1) Firstly clone a project on your local by following command

    git clone https://github.com/inderjit2006/pilot-project.git
    
2) Installing the Standard Edition via composer (If not installed) If you don’t have Composer yet, download it following the instructions on http:/
getcomposer.org/ or just run one of the following command: 

    curl -s http://getcomposer.org/installer | php
    
3) Now install the required dependencies for this project by following command
    
    cd pilot-project
    php composer.phar install

4) Now we have to give persmissions to cache and logs folders by folowing commands

    chmod -R 777 <prjoect-root-directory>/var/cache
    chmod -R 777 <prjoect-root-directory>/var/logs
    chmod -R 777 <prjoect-root-directory>/var/sessions
    
5) Now we have to create database by following command

    php bin/console doctrine:database:create

6) Now we have to make the database tables by following command

    php bin/console doctrine:schema:update --force
    
7) Now run the project with the following command 
    symfony server:start

8) And now we can check the functionality on this url http://127.0.0.1:8000
