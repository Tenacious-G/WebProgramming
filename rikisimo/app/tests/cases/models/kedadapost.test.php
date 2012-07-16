<?php 
/* SVN FILE: $Id$ */
/* Kedadapost Test cases generated on: 2009-01-26 20:01:18 : 1232998818*/
App::import('Model', 'Kedadapost');

class KedadapostTestCase extends CakeTestCase {
	var $Kedadapost = null;
	var $fixtures = array('app.kedadapost', 'app.kedada', 'app.user');

	function startTest() {
		$this->Kedadapost =& ClassRegistry::init('Kedadapost');
	}

	function testKedadapostInstance() {
		$this->assertTrue(is_a($this->Kedadapost, 'Kedadapost'));
	}

	function testKedadapostFind() {
		$this->Kedadapost->recursive = -1;
		$results = $this->Kedadapost->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Kedadapost' => array(
			'id'  => 1,
			'kedada_id'  => 1,
			'user_id'  => 1,
			'post'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-26 20:40:18',
			'modified'  => '2009-01-26 20:40:18'
			));
		$this->assertEqual($results, $expected);
	}
}
?>