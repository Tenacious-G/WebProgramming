<?php 
/* SVN FILE: $Id$ */
/* GrouppostsController Test cases generated on: 2009-01-26 16:01:50 : 1232982170*/
App::import('Controller', 'Groupposts');

class TestGroupposts extends GrouppostsController {
	var $autoRender = false;
}

class GrouppostsControllerTest extends CakeTestCase {
	var $Groupposts = null;

	function setUp() {
		$this->Groupposts = new TestGroupposts();
		$this->Groupposts->constructClasses();
	}

	function testGrouppostsControllerInstance() {
		$this->assertTrue(is_a($this->Groupposts, 'GrouppostsController'));
	}

	function tearDown() {
		unset($this->Groupposts);
	}
}
?>