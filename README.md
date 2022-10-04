<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# About the Project
A Dating website made of only one user-type (user), where user can favorite, block, chat and visit people with the gender he/she is interested in. This is a project to practice Laravel wit MYSQL during my envolvement in Full-Stack Web Development Bootcamp with SE Factory.

----------
# Getting Started
# Installation
Clone the repository

    git clone https://github.com/mohammadammash/dating_website
    
Switch to the repo folder

    cd dating-website
    
Install all the dependencies using composer:

    composer install
    
Copy the example env file to .env file (here no changes needed):

    cp .env.example .env
    
Create the database: 'freehugs_db' and add the name in '.env'
    
Start the local development server

    php artisan serve
    
Run in the cmd (to create tables):

    php artisan migrate
    
You can now start the website by running the path of 'landing.html': <br>
    
    YOURDIRECTORYPATH/dating-website/frontend/views/landing/landing.html
----------
# Authors:
  mohammadammash31@gmail.com
