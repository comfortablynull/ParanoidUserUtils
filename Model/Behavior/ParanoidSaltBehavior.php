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
     * - 'ivsize' 16 The size of the iv for mcrypt_create_iv to use
     * - 'cost' 09 The TWO DIGIT cost parameter to pass to crypt()
     * - 'salt' field in the db that the salt is stored
     * - 'password' field in the db taht the password is stored
     * @var array
     */
    private $defaults = array('salt'=>'salt','password'=>'password',);   
    private $modelAlias;
    private $passwordHasher;
    function setup(Model $model, $config = array()) {
        $this->modelAlias = $model->alias;
        $this->settings[$model->alias] = array_merge($config,$this->defaults);
    }

    function cleanup(Model $model) {
        parent::cleanup($model);
    }
    function beforeSave(Model $model) {
        $alias = $model->alias;
        $saltField = $this->settings[$alias]['salt'];
        $passwordField = $this->settings[$alias]['password'];
        if(!$model->id){
            $this->__loadPasswordHasher();
            $salt = $this->passwordHasher->generateSalt($this->settings[$alias]);
            $password = $model->data[$alias][$passwordField];
            $model->data[$alias][$saltField] = $salt;
            $model->data[$alias][$passwordField] = $this->passwordHasher->hash($password, $salt);
        }
        elseif(isset($model->data[$alias][$passwordField])){
            $this->__loadPasswordHasher();
            $salt = $this->__getSalt($model->id);
            $model->data[$alias][$passwordField] =  $this->passwordHasher->hash($model->data[$alias][$passwordField],$salt);
        }
    }
    /**
     * Lazy loads the password hasher.
     * Sets the local password hasher property
     */
    private function __loadPasswordHasher(){
        App::uses('ParanoidHasher','ParanoidUserUtils.Controller/Component/Auth');
        $this->passwordHasher = new ParanoidHasher($this->settings[$this->modelAlias]);
    }
    /**
     * Gets the current salt for a existing user
     * @param int $id
     * @return string
     */
    private function __getSalt($id){
        $fields = array($this->modelAlias.'.'.$this->settings[$this->settings[$this->modelAlias]]['salt']);
        $conditions = array($this->modelAlias.'.'.$this->modelPrimaryKey=>$id);
        $salt = $model->find('first',array('fields'=>$fields,'conditions'=>$conditions));
        return $salt[$this->modelAlias][$this->settings[$this->settings[$this->modelAlias]]['salt']];
    }


}
