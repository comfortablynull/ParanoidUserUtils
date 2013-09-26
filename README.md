ParanoidUserUtils
=================
## WAIT STOP DO NOT USE
This project is incomplete and just something I am working on. Hopefully it will be done soon.

ParanoidUserUtils is a collection of components, behaviors, and log engines. It's
Goal is to provide many features that are not out of the box with CakePHP authentication system
that developers might want.
Features include:
* Authentication system that stores unique salts for each user.
* An abstract class paranoidpasswordhasher class so other developers can make
  hashing components to use with ParanoidFormAuthenticate.
* User event log.
* Password reset requests
* Simple protection from brute force:
    * Set max number of failed login attempts for valid users.
    * Set max number of failed login attempts by ip address.
    * Set time out time.
    * Maybe recaptcha support

The master branch has the following requirements:

* CakePHP 2.2.0 or greater.
* PHP 5.3.0 or greater.
* Mcrypt

## Installation

* Clone/Copy the files in this directory into `app/Plugin/ParanoidUserUtils`
* Load the plugin In `app/Config/bootstrap.php`:
    * With User Event Log add: `CakePlugin::load('ParanoidUserUtils', array('bootstrap' => true));`
    * Without User Event Log add: `CakePlugin::load('ParanoidUserUtils');`

## TODO
* Write unit tests
* Test with said unit tests
* Create Schema(tables not done this is why it is not included)
* Make ParanoidFormAuthenticate component more portable.
* Make ParanoidFormPasswordHasher component portable.
* Make ParanoidSaltBehavior behavior portable.
* Make UserEventLog portable.
* Create the password reset behavior.
* Create views and controllers
* Check documentations and export PHPDoc
* Versioning
* Finish this readme