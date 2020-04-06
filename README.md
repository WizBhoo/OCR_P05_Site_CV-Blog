# OCR_P05_Create_Your_First_Site/Blog_in_PHP
OpenClassrooms - Training Course DA PHP/Symfony - Project P05<br>
My WebSite is Online and you can visit it : [APi - Site CV](https://adrien-pierrard.fr)

## Version 1.0.0 - April 2020

*   This file explains how to install and run the project.
*   IDE used : PhpStorm.
*   I use a Docker Stack as personal local development environment but you can use your own environment.
*   Both method to install the project are described bellow.
*   NOTE that only OCR Evaluators have access to my DB file (see project renderings) with a set of data for demo.

-------------------------------------------------------------------------------------------------------------------------------------

Realized by Adrien PIERRARD - [adrien.pierrard@gmail.com](mailto:adrien.pierrard@gmail.com)<br>
Supported by Antoine De Conto - OCR Mentor<br>
Special thanks to Yann LUCAS for PR Reviews</br>

-------------------------------------------------------------------------------------------------------------------------------------

### How to install the project with your own local environment

What you need :

*   PHP 7.2
*   MySQL 8 - PHPMyAdmin to create the DataBase
*   Database UML schemas are provided in a project folder named "UML_diagrams"

Follow each following steps :

*   First clone this repository from your terminal in your preferred project directory.

```
https://github.com/WizBhoo/OCR_P05_Site_CV-Blog.git
```

*   You need to edit the ".env" file to setup your DB environment variable for connection.
*   You need also to setup inside ENV variables for SwiftMailer to allow sending contact message or use the forgotten password form to send a reset password email for user account.
*   If you prefer you can copy the ".env" file and setup your credentials in a ".env.local" file.
*   Launch your local environment and create the database following UML schemas (only OCR Evaluators have access to my DB file (see project renderings) with a set of data for demo).
*   From your terminal, go to the project directory.
*   I used some package with composer so don't forget to tape those command lines in you terminal :

```
composer install
composer update
composer dump-autoload
```

*   Well done ! The project is installed so you just have to go to your localhost home page.

-------------------------------------------------------------------------------------------------------------------------------------

### How to install the project using my Docker Stack (recommended method)

*   My Docker stack provide a development environment ready to run a PHP project.
*   Follow this link and read the README file to install it : [Docker PHP](https://github.com/WizBhoo/docker_php)
*   Prerequisite : to have Docker and Docker-Compose installed on your machine - [Docker Install](https://docs.docker.com/install/)
*   Preferred Operating System to use it : Linux / Mac OSx

Once you have well installed my Docker Stack, follow each following steps :

*   From your terminal go to the php_project directory created by docker.
*   Clone this repository in this directory.

```
https://github.com/WizBhoo/OCR_P05_Site_CV-Blog.git
```

*   You need to edit the ".env" file to setup your DB environment variable for connection.
*   You need also to setup inside ENV variables for SwiftMailer to allow sending contact message or use the forgotten password form to send a reset password email for user account.
*   If you prefer you can copy the ".env" file and setup your credentials in a ".env.local" file.
*   From your terminal go to the Docker directory and launch Docker using those command lines :

```
make build
make start or make up
```

<blockquote>
You can also use "make help" to see what "make" command are available.
</blockquote>

*   Go to [pma.localhost](http://pma.localhost) to access to PHPMyAdmin and create the database following UML schemas (only OCR Evaluators have access to my DB file (see project renderings) with a set of data for demo).
*   From your terminal, go to the php_project directory.
*   I used some package with composer so don't forget to tape those command lines in you terminal :

```
composer install
composer update
composer dump-autoload
```

*   Well done ! The project is installed so you just have to go to [mon-site.localhost](http://mon-site.localhost) home page.

-------------------------------------------------------------------------------------------------------------------------------------

### What you can do on this WebSite

*   This project was the occasion for me to realize a CV Portfolio so it contains my personal background information.

<blockquote> 
Please don't use it for personal uses !
</blockquote>

*   A Dashboard is available to administrate Blog and Users.
*   NOTE that if you want to access to the dashboard, you need to create an admin user account manually in DB because users created from the site are setup as simple user by default.
*   Visitors can't access to the Dashboard and can't post any comment in my articles.
*   To have a registered user account activated, an admin has to activate the user account in the Dashboard.
*   A registered and logged user can post comments in articles.
*   Each comment has to be approved by an admin from the dashboard before being published.
*   Only admin users can create articles from the dashboard.
*   Admin users can add, modify, delete articles / approved or delete comments / activate, delete users.
*   Admin users can also change user right form user to admin or admin to user.
*   From the public site part, each user can modify his profile (first and last name and password if needed).
*   A functional contact form is available.
*   A user can reset his password using the forgotten password form and by following a reset link received by email.

-------------------------------------------------------------------------------------------------------------------------------------

### Contact

Thanks in advance for Star contribution
Any question / trouble ? Please send me an e-mail ;-) - [adrien.pierrard@gmail.com](mailto:adrien.pierrard@gmail.com)
