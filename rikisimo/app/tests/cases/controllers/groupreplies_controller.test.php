<?php 
/* SVN FILE: $Id$ */
/* GrouprepliesController Test cases generated on: 2009-01-26 16:01:24 : 1232982204*/
App::import('Controller', 'Groupreplies');

class TestGroupreplies extends GrouprepliesController {
	var $autoRender = false;
}

class GrouprepliesControllerTest extends CakeTestCase {
	var $Groupreplies = null;

	function setUp() {
		$this->Groupreplies = new TestGroupreplies();
		$this->Groupreplies->constructClasses();
	}

	function testGrouprepliesControllerInstance() {
		$this->assertTrue(is_a($this->Groupreplies, 'GrouprepliesController'));
	}

	function tearDown() {
		unset($this->Groupreplies);
	}
}
?>