<?php
App::uses('AppModel', 'Model');
/**
 * UserEvent Model
 *
 * @property User $User
 * @property EventReset $EventReset
 */
class UserEvent extends AppModel {
    public function afterFind($results, $primary = false) {
        static $allowedTypes = array('Failed Login', 'Successful Login', 
                                           'Successful Logout','Requested Reset',
                                           'Expired Reset','Successful Reset');
        foreach($results as $key=>$val){
            if(isset($val['UserEvent']['ip'])){
                //takes the long stored ip address and formats it to 127.0.0.1
                $results[$key]['UserEvent']['ip'] = long2ip($val['UserEvent']['ip']);
            }
        }
        return parent::afterFind($results, $primary);
    }
    public function beforeSave($options = array()) {
            if(!isset($this->data['UserEvent']['user_id'])){
                $this->data['UserEvent']['user_id'] = null;
            }
            elseif($this->data['UserEvent']['user_id'] === null){
                App::uses('AuthComponent', 'Controller/Component');
                $this->data['UserEvent']['user_id'] = AuthComponent::user('id');
            }
            if(!$this->id){
                $this->data['UserEvent']['ip'] = ip2long(Router::getRequest()->clientIp(false)); 
                $this->data['UserEvent']['time'] =  ConnectionManager::getDataSource('default')->expression('NOW()');
            }
        return parent::beforeSave($options);
    }

/**

/**
 * hasMany associations
 *
 * @var array
 */

}
