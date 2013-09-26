ParanoidUserUtils
=================
The master branch has the following requirements:

* CakePHP 2.2.0 or greater.
* PHP 5.3.0 or greater.
* Mcrypt

## Installation

* Clone/Copy the files in this directory into `app/Plugin/ParanoidUserUtils`
* Load the plugin without UserEvents: In `app/Config/bootstrap.php` add `CakePlugin::load('ParanoidUserUtils');
* OR
* Load the plugin with UserEvents: In `app/Config/bootstrap.php` add 'CakePlugin::load('ParanoidUserUtils', array('bootstrap' => true));
