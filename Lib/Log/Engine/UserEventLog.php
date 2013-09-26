<?php
App::uses('CakeLogInterface', 'Log');
App::uses('ClassRegistry', 'Utility');
App::uses('UserEvent','ParanoidUserUtils.Model');
/**
 * Description of UserEventLogger
 *
 * @author dcaiazzo
 */
class UserEventLog implements CakeLogInterface{
    private $Log;
    public function __construct($options = array()) {
        $this->Log = ClassRegistry::init('ParanoidUserUtils.UserEvent');
    }

    public function write($type, $message) {
        $this->Log->create();
        if(is_array($message)){
            $log['message'] = $message['message'];
            $log['user_id'] = $message['user_id'];
        }
        else{ $log['message'] = $message; }
        $log['type'] = $type;
        return $this->Log->save($log);
    }
}