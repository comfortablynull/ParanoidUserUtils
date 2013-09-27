<?php
App::uses('AppModel', 'Model');
/**
 * UserEvent Model
 *
 * @property User $User
 * @property EventReset $EventReset
 */
class UserEvent extends AppModel {
//    protected $_eventTypes = array(0=>'Action',1=>'Logged On',2=>'Logged Out',3=>'Failed Login', 4=>'Password Reset');
    public function afterFind($results, $primary = false) {
        foreach($results as $key=>$val){
            if(isset($val['UserEvent']['ip'])){
                //takes the long stored ip address and formats it to 127.0.0.1
                $results[$key]['UserEvent']['ip'] = long2ip($val['UserEvent']['ip']);
            }
//            if(isset($val['UserEvent']['type'])){
//                $results[$key]['UserEvent']['type'] = $val['UserEvent']['type'];
//            }
        }
        return parent::afterFind($results, $primary);
    }
    public function beforeSave($options = array()) {
//            App::uses('AuthComponent', 'Controller/Component');
            if($this->data['UserEvent']['user_id'] === null){
                //if the userid cannot be set the save should fail.
                $this->data['UserEvent']['user_id'] = 0;//AuthComponent::user('id');
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
