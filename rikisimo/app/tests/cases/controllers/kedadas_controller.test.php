<?php 
/* SVN FILE: $Id$ */
/* KedadasController Test cases generated on: 2009-01-26 20:01:50 : 1232998850*/
App::import('Controller', 'Kedadas');

class TestKedadas extends KedadasController {
	var $autoRender = false;
}

class KedadasControllerTest extends CakeTestCase {
	var $Kedadas = null;

	function setUp() {
		$this->Kedadas = new TestKedadas();
		$this->Kedadas->constructClasses();
	}

	function testKedadasControllerInstance() {
		$this->assertTrue(is_a($this->Kedadas, 'KedadasController'));
	}

	function tearDown() {
		unset($this->Kedadas);
	}
}
?>