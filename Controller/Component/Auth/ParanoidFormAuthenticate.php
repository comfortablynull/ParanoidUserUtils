<?php
/**
 * PHP 5
 *
 * Copyright (c) David Caiazzo
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) David Caaizzo (http://www.comfortablynull.com)
 * @link          http://www.comfortablynull.com David Caiazzo
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * CakePHP paranoidAuthorize
 * @author David Caiazzo
 */
App::uses('FormAuthenticate', 'Controller/Component/Auth');
class ParanoidFormAuthenticate extends FormAuthenticate {
    /**
     * The message and type of log to be written when the destructor is called.
     * I don't really need to check for this staying a array... do I?
     * @var array 
     */
    protected $_logMessage = array('type'=>null, 'message'=>null);
    public function __construct(ComponentCollection $collection, $settings) {
        $this->settings['fields']['salt'] = 'salt';
        $this->settings['passwordHasher'] = 'ParanoidUserUtils.ParanoidPassword';
        $this->settings['log'] = true;
        parent::__construct($collection, $settings);
    }
    public function __destruct() {
        //easier to do 1 check for logging instead of multiple 
        if($this->settings['log']===true){
            CakeLog::write($this->_logMessage['type'], $this->_logMessage['message']);
        }
        parent::__destruct();
    }
    /**
     * Pretty much a carbon copy of _findUser from BaseAuthenticate with the exception
     * of looking for a seperate salt field in the db to use for hashing. I wrote this becuase
     * I do not want to rely on truncating the string like BlowfishAuthenticate does.
     * @param string $username
     * @param string $password
     * @return boolean
     */
    protected function _findUser($username, $password = null) {
            $userModel = $this->settings['userModel'];
            list(, $model) = pluginSplit($userModel);
            $fields = $this->settings['fields'];
            $saltField = $fields['salt'];
            if (is_array($username)) {
                    $conditions = $username;
            } else {
                    $conditions = array(
                            $model . '.' . $fields['username'] => $username
                    );
            }

            if (!empty($this->settings['scope'])) {
                    $conditions = array_merge($conditions, $this->settings['scope']);
            }

            $result = ClassRegistry::init($userModel)->find('first', array(
                    'conditions' => $conditions,
                    'recursive' => $this->settings['recursive'],
                    'contain' => $this->settings['contain'],
            ));
            if (empty($result[$model])) {
                    $this->_logMessage['type'] = 'login_error';
                    $this->_logMessage['message'] = 'User Not Found';
                    //Generating a fresh salt will probably be picked up in a time attack.
                    $this->passwordHasher()->hashWithSecuritySalt($password);
                    unset($password);
                    return false;
            }
            $user = $result[$model];
            if ($password) {
                if (!$this->passwordHasher()->check($password, $user[$fields['password']], $result[$model][$saltField])) {
                        $this->_logMessage['type'] = 'login_error';
                        $this->_logMessage['message'] = array('message'=>"Invalid Password",'user_id'=>$user['id']);
                        return false;
                }
                unset($user[$fields['password']]);
                unset($user[$saltField]);
            }
            unset($result[$model]);
            $this->_logMessage['type'] = 'login_success';
            $this->_logMessage['message'] = 'Logged On';
            return array_merge($user, $result);
    }
        
}
