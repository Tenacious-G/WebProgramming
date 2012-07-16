<?php 
/* SVN FILE: $Id$ */
/* Kedada Test cases generated on: 2009-01-26 20:01:18 : 1232998758*/
App::import('Model', 'Kedada');

class KedadaTestCase extends CakeTestCase {
	var $Kedada = null;
	var $fixtures = array('app.kedada', 'app.node', 'app.user', 'app.kedadapost');

	function startTest() {
		$this->Kedada =& ClassRegistry::init('Kedada');
	}

	function testKedadaInstance() {
		$this->assertTrue(is_a($this->Kedada, 'Kedada'));
	}

	function testKedadaFind() {
		$this->Kedada->recursive = -1;
		$results = $this->Kedada->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Kedada' => array(
			'id'  => 1,
			'node_id'  => 1,
			'user_id'  => 1,
			'title'  => 'Lorem ipsum dolor sit amet',
			'description'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'date'  => '2009-01-26 20:39:18',
			'created'  => '2009-01-26 20:39:18',
			'modified'  => '2009-01-26 20:39:18'
			));
		$this->assertEqual($results, $expected);
	}
}
?>