<?php 
/* SVN FILE: $Id$ */
/* GroupsController Test cases generated on: 2009-01-26 15:01:27 : 1232981307*/
App::import('Controller', 'Groups');

class TestGroups extends GroupsController {
	var $autoRender = false;
}

class GroupsControllerTest extends CakeTestCase {
	var $Groups = null;

	function setUp() {
		$this->Groups = new TestGroups();
		$this->Groups->constructClasses();
	}

	function testGroupsControllerInstance() {
		$this->assertTrue(is_a($this->Groups, 'GroupsController'));
	}

	function tearDown() {
		unset($this->Groups);
	}
}
?>