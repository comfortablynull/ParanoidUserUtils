<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * CakePHP Behavior
 * @author David Caiazzo
 */
class ParanoidSaltBehavior extends ModelBehavior {
    /*
     * Configurations for this object. Settings passed from authenticator class to
     * the constructor are merged with this property.
     * 
     * `fields` The fields to use to generate a unique salt
     * - 'salt' field in the db that the salt is stored
     * - 'password' field in the db taht the password is stored
     * 'PasswordHasher' the component/plugin component of the desired password hasher
     * 'settings' The settings for the password hasher
     * - 'ivsize' 16 The size of the iv for mcrypt_create_iv to use
     * - 'cost' 09 The TWO DIGIT cost parameter to pass to crypt()
     * @var array
     */
    private $__defaults = array('fields'=>array('salt'=>'salt','password'=>'password'),
                              'passwordHasher'=>'ParanoidUserUtils.ParanoidBlowfish',
                              'settings'=>array(),
                              'log' => true,
                              'logType'=>'account'
                             );
    /**
     * The message to log
     * @var mixed string/array
     */
    private $__logMessage = array('message'=>'','user_id'=>null);
    private $__logEvent = false;
    function setup(Model $model, $config = array()) {
        $this->modelAlias = $model->alias;
        $this->settings[$model->alias] = array_merge($config,$this->__defaults);
    }
    
    function cleanup(Model $model) {
        parent::cleanup($model);
    }
    function beforeSave(Model $model) {
        $alias = $model->alias;
        $saltField = $this->settings[$alias]['fields']['salt'];
        $passwordField = $this->settings[$alias]['fields']['password'];
        if(!$model->id){
            $this->__loadPasswordHasher();
            $salt = $this->passwordHasher->generateSalt($this->settings[$alias]);
            $password = $model->data[$alias][$passwordField];
            $model->data[$alias][$saltField] = $salt;
            $model->data[$alias][$passwordField] = $this->passwordHasher->hash($password, $salt);
            $this->__logMessage = array('message'=>'Account Created');
            $this->__logEvent = true;
        }
        elseif(isset($model->data[$alias][$passwordField])){
            $this->__loadPasswordHasher();
            $this->__logEvent = true;
            $salt = $this->__getSalt($model);
            $this->__logMessage = array('message'=>'Password Changed');
            $model->data[$alias][$passwordField] =  $this->passwordHasher->hash($model->data[$alias][$passwordField],$salt);
        }
    }
    function afterSave(Model $model, $created) {
        if($this->__logEvent === true && $this->settings[$model->alias]['log']){
            $this->__logMessage['user_id'] =  $model->id;
            CakeLog::write($this->settings[$model->alias]['logType'], $this->__logMessage);
        }
        parent::afterSave($model, $created);
    }
    /**
     * Lazy loads the password hasher.
     * Sets the local password hasher property
     */
    private function __loadPasswordHasher(){
        list($plugin, $hasherClass) = pluginSplit($this->settings[$this->modelAlias]['passwordHasher'],true);
        $hasherClass .= 'PasswordHasher';
        App::uses($hasherClass,$plugin.'Controller/Component/Auth');
        $this->passwordHasher = new $hasherClass($this->settings[$this->modelAlias]['settings']);
    }
    /**
     * Gets the current salt for a existing user
     * @param Model $model
     * @return string
     */
    private function __getSalt(Model $model){
        $fields = array($this->modelAlias.'.'.$this->settings[$model->alias]['fields']['salt']);
        $conditions = array($this->modelAlias.'.'.$model->primaryKey=>$model->id);
        $salt = $model->find('first',array('fields'=>$fields,'conditions'=>$conditions));
        return $salt[$model->alias][$this->settings[$model->alias]['fields']['salt']];
    }


}
