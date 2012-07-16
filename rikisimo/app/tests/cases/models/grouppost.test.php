<?php 
/* SVN FILE: $Id$ */
/* Grouppost Test cases generated on: 2009-01-26 16:01:16 : 1232982016*/
App::import('Model', 'Grouppost');

class GrouppostTestCase extends CakeTestCase {
	var $Grouppost = null;
	var $fixtures = array('app.grouppost', 'app.group', 'app.user', 'app.groupreply');

	function startTest() {
		$this->Grouppost =& ClassRegistry::init('Grouppost');
	}

	function testGrouppostInstance() {
		$this->assertTrue(is_a($this->Grouppost, 'Grouppost'));
	}

	function testGrouppostFind() {
		$this->Grouppost->recursive = -1;
		$results = $this->Grouppost->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Grouppost' => array(
			'id'  => 1,
			'group_id'  => 1,
			'user_id'  => 1,
			'title'  => 'Lorem ipsum dolor sit amet',
			'slug'  => 'Lorem ipsum dolor sit amet',
			'post'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-26 16:00:09',
			'modified'  => '2009-01-26 16:00:09'
			));
		$this->assertEqual($results, $expected);
	}
}
?>