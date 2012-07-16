<?php 
/* SVN FILE: $Id$ */
/* Grouppost Fixture generated on: 2009-01-26 16:01:09 : 1232982009*/

class GrouppostFixture extends CakeTestFixture {
	var $name = 'Grouppost';
	var $table = 'groupposts';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'group_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
			'user_id' => array('type'=>'integer', 'null' => false, 'default' => NULL),
			'title' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 100),
			'slug' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 150),
			'post' => array('type'=>'text', 'null' => false, 'default' => NULL),
			'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'modified' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
	var $records = array(array(
			'id'  => 1,
			'group_id'  => 1,
			'user_id'  => 1,
			'title'  => 'Lorem ipsum dolor sit amet',
			'slug'  => 'Lorem ipsum dolor sit amet',
			'post'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-26 16:00:09',
			'modified'  => '2009-01-26 16:00:09'
			));
}
?>