<?php 
/* SVN FILE: $Id$ */
/* Groupreply Test cases generated on: 2009-01-26 16:01:57 : 1232982057*/
App::import('Model', 'Groupreply');

class GroupreplyTestCase extends CakeTestCase {
	var $Groupreply = null;
	var $fixtures = array('app.groupreply', 'app.grouppost', 'app.user');

	function startTest() {
		$this->Groupreply =& ClassRegistry::init('Groupreply');
	}

	function testGroupreplyInstance() {
		$this->assertTrue(is_a($this->Groupreply, 'Groupreply'));
	}

	function testGroupreplyFind() {
		$this->Groupreply->recursive = -1;
		$results = $this->Groupreply->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Groupreply' => array(
			'id'  => 1,
			'grouppost_id'  => 1,
			'user_id'  => 1,
			'post'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-26 16:00:57',
			'modified'  => '2009-01-26 16:00:57'
			));
		$this->assertEqual($results, $expected);
	}
}
?>