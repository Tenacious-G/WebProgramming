<?php 
/* SVN FILE: $Id$ */
/* KedadapostsController Test cases generated on: 2009-01-26 20:01:09 : 1232998869*/
App::import('Controller', 'Kedadaposts');

class TestKedadaposts extends KedadapostsController {
	var $autoRender = false;
}

class KedadapostsControllerTest extends CakeTestCase {
	var $Kedadaposts = null;

	function setUp() {
		$this->Kedadaposts = new TestKedadaposts();
		$this->Kedadaposts->constructClasses();
	}

	function testKedadapostsControllerInstance() {
		$this->assertTrue(is_a($this->Kedadaposts, 'KedadapostsController'));
	}

	function tearDown() {
		unset($this->Kedadaposts);
	}
}
?>