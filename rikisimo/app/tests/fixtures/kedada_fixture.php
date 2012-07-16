<?php 
/* SVN FILE: $Id$ */
/* Kedada Fixture generated on: 2009-01-26 20:01:18 : 1232998758*/

class KedadaFixture extends CakeTestFixture {
	var $name = 'Kedada';
	var $table = 'kedadas';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'node_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
			'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
			'title' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 150),
			'description' => array('type'=>'text', 'null' => false, 'default' => NULL),
			'date' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
			'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
	var $records = array(array(
			'id'  => 1,
			'node_id'  => 1,
			'user_id'  => 1,
			'title'  => 'Lorem ipsum dolor sit amet',
			'description'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'date'  => '2009-01-26 20:39:18',
			'created'  => '2009-01-26 20:39:18',
			'modified'  => '2009-01-26 20:39:18'
			));
}
?>