<?php
/**
 * Copyright 2008 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class FeedsController extends AppController{

	var $name = 'Feeds';
	var $uses = array('Node');
	var $helpers = array('Time');
	var $components = array('RequestHandler');

	/**
	 * overload beforeFilter to allow public access to the index action
	 */

	function beforeFilter() {
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	/**
	 * build the feed with the most recent nodes added
	 *
	 * @param $feed string
	 */

	function index($feed = 'nodes'){    
		$conditions['limit'] = 25;
		$conditions['order'] = 'created DESC';
		if($feed!='nodes') $conditions['conditions'] = array('City.slug'=>$feed);
		$this->set('nodes', $this->Node->find('all',$conditions));
		$this->set('feed',$feed);
	}
}
?>