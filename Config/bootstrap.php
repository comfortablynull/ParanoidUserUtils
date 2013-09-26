<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of boostrap
 *
 * @author dcaiazzo
 */

App::uses('CakeLog', 'Log');
CakeLog::config('UserEvent', array(
        'engine' => 'ParanoidUserUtils.UserEvent',
        'model' => 'ParanoidUserUtils.UserEvent',
        'types' => array('Failed Login', 'Successful Login', 
                         'Successful Logout','Requested Reset',
                         'expired_reset','successful_reset'),
        'scopes' => array('UserEvent')
));