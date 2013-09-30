<?php 
class ParanoidUserUtilSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $user_events = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'ip' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'message' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 125, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 125, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'time' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'type' => array('column' => 'type', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

}
