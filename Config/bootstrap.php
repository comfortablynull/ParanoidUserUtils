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
//This is here so irrelvant errors don't get written to the database
App::uses('CakeLog', 'Log');
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
CakeLog::config('UserEvent', array(
        'engine' => 'ParanoidUserUtils.UserEvent',
        'model' => 'ParanoidUserUtils.UserEvent',
        'types' => array('login_error', 'login_success', 'logout_success', 'account', 
                         'pwreset_request', 'action'),
));