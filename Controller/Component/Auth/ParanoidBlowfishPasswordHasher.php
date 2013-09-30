<?php

/**
 * PHP 5
 *
 * Copyright (c) David Caiazzo (http://www.comfortablynull.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) David Caiazzo
 * @link          http://www.comfortablynull.com David Caiazzo
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * CakePHP paranoidHasher
 * @author David Caiazzo
 */
App::uses('AbstractPasswordHasher', 'Controller/Component/Auth');
class ParanoidBlowfishPasswordHasher extends AbstractPasswordHasher {
    /**
     * Configurations for this object. Settings passed from authenticator class to
     * the constructor are merged with this property.
     * 
     * `fields` The fields to use to generate a unique salt
     * - 'ivsize' 16 The size of the iv for mcrypt_create_iv to use
     * - 'cost' 09 The TWO DIGIT cost parameter to pass to crypt()
     * @var array
     */
    protected $_config = array('ivsize'=>16, 'hashcost'=>10);
    /**
     * Generates a salt of ivsize and crypt const using mcrypt_create iv and dev_urandom
     * @return string random salt
     */
    public function generateSalt(){
        $iv = str_replace('+', '.', base64_encode(mcrypt_create_iv($this->_config['ivsize'], MCRYPT_DEV_URANDOM)));
        $salt = '$2y$'.(string)$this->_config['hashcost'].'$'.$iv;
        return $salt;
    }
    /**
     * To protect against time attacks trying to find usernames this will
     * return a hashed password using the Config security salt with the set ivsize
     * and hashcost. Generating a new salt might take longer and on bigger attacks
     * can deplete enthropy.
     * @return string hashed password
     */
    public function hashWithSecuritySalt($password){
        $salt = '$2y$'.(string)$this->_config['hashcost'].'$'.Configure::read('Security.salt');
        return crypt($password,$salt);      
    }
    /**
     * Salt is required to run since each user has a unique salt in the database.
     * @param string $password
     * @param string $salt
     * @return string
     * @throws Exception
     */
    public function hash($password, $salt=null){
        if(is_null($salt)){
            throw new Exception('Salt cannot be NULL!');
        }
        return crypt($password, $salt);
        
    }
    /**
     * Salt is required. 
     * @param string $password
     * @param string $hashedPassword
     * @param string $salt
     * @return bool
     */
    public function check($password, $hashedPassword, $salt=null) {
        try{
            return $hashedPassword === $this->hash($password, $salt);
        }
        catch (Exception $e){
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
        return false;
    }
}
