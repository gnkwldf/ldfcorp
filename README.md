# Readme

This is the Ldfcorp website, here it will be explained how to install it.

## Installation

First of all you need this some programs

* [php](http://www.php.net/)
* [database](https://mariadb.org/) (mysql/postgresql/mariadb/perconadb …)
* [web server](http://nginx.org/) (nginx recommanded)
* [node.js](http://nodejs.org/)
* [git](http://git-scm.com/) (2.x recommanded)
* [curl](http://curl.haxx.se/)
* [composer](https://getcomposer.org/) (you can install it at the root of your
  project)
* [A browser](http://www.chromium.org/) (chrome/firefox/opera/safari …)

Now you can install this project based on [Symfony 2.3.x](http://symfony.com/) …

### Initialize project

* Get the project
  `git clone <repository-url>`
* Install node modules
  `npm install`
* Install _forever_ as root user
  `sudo npm install forever -g`
* Launch __server.js__
  `forever start server.js`
* Install composer at the root of your peoject
  `curl -s http://getcomposer.org/installer | php`
* Use this command and answer configuration questions
  `php composer.phar install`
    * For database, you should create a database with one user who can only
    access this base
    * For `node_client`, you should use your server hostname
    `node_client: http://my.host.org:8000`
* Check if your php config is correct
  `php app/check.php`
* Create the database
  `php app/console doctrine:schema:update --force`
* Populate the database with this script
  `php app/console ldfcorp:populate`
* Add the nginx or your favorite web server 
  [symfony 2 configuration](http://wiki.nginx.org/Symfony)
* Create an admin user (replace _adminuser_ by login you want)
  `php app/console fos:user:create adminuser --super-admin`
* Clear your prod cache
  `php app/console cache:clear --env=prod`

Now you can view the project on your favorite browser

## Technical informations

This project use __symfony2__ framework because of the his good project
architecture. We use _Doctrine_ for entities, _Symfony Form Types_ for forms
using entities, _Symfony Controllers_ to link Entities and _Twig_ views or
_HttpFoundation_ Responses.

To purpose dynamic content, we use _socket.io_ from __node.js__ because this
component can notify the web browser.

To link __node.js__ and __symfony2__, we use _elephant.io_ component because
it's a PHP _socket.io_ implementation.

We use __node.js__ server on two ports, one listening __symfony2__ and one
listening web browser client :

1. Web browser send a request to __symfony2__
2. __symfony2__ notify __node.js__ through _elephant.io__
3. __node.js__ push notification to the Web browser