<?php 
/* SVN FILE: $Id$ */
/* Groupreply Fixture generated on: 2009-01-26 16:01:57 : 1232982057*/

class GroupreplyFixture extends CakeTestFixture {
	var $name = 'Groupreply';
	var $table = 'groupreplies';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'grouppost_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
			'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
			'post' => array('type'=>'text', 'null' => false, 'default' => NULL),
			'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
	var $records = array(array(
			'id'  => 1,
			'grouppost_id'  => 1,
			'user_id'  => 1,
			'post'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-26 16:00:57',
			'modified'  => '2009-01-26 16:00:57'
			));
}
?>