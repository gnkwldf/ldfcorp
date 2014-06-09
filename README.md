# Readme

This is the Ldfcorp website, here it will be explained how to install it.

## Installation

First of all you need this some programs

* [php](http://www.php.net/)
* [database](https://mariadb.org/) (mysql/postgresql/mariadb/perconadb …)
* [web server](http://nginx.org/) (nginx recommanded)
* [git](http://git-scm.com/) (2.x recommanded)
* [curl](http://curl.haxx.se/)
* [composer](https://getcomposer.org/) (you can install it at the root of your
  project)
* [A browser](http://www.chromium.org/) (chrome/firefox/opera/safari …)

Now you can install this project based on [Symfony 2.3.x](http://symfony.com/) …

### Initialize project

* Get the project
  `git clone <repository-url>`
* Install composer at the root of your peoject
  `curl -s http://getcomposer.org/installer | php`
* Use this command and answer configuration questions
  `php composer.phar install`
* Check if your php config is correct
  `php app/check.php`
* Create the database
  `php app/console doctrine:schema:update`
* Populate the database with this script
  `php app/console ldfcorp:populate`
* Add the nginx or your favorite web server 
  [symfony 2 configuration](http://wiki.nginx.org/Symfony)

Now you can view the project on your favorite browser
