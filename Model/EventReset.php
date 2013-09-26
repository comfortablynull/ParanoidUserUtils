<?php
App::uses('AppModel', 'Model');
/**
 * EventReset Model
 *
 * @property UserEvent $UserEvent
 */
class EventReset extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'UserEvent' => array(
			'className' => 'UserEvent',
			'foreignKey' => 'user_event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
