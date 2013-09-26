ParanoidUserUtils
=================
The master branch has the following requirements:

* CakePHP 2.2.0 or greater.
* PHP 5.3.0 or greater.
* Mcrypt

## Installation

* Clone/Copy the files in this directory into `app/Plugin/ParanoidUserUtils`
* Load the plugin In `app/Config/bootstrap.php`:
    * With User Event log add: 'CakePlugin::load('ParanoidUserUtils', array('bootstrap' => true));
    * Without User Event Log add: `CakePlugin::load('ParanoidUserUtils');
