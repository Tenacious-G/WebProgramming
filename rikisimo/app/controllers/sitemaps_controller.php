<?php
/**
 * Copyright 2008 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class SitemapsController extends AppController{

	var $name = 'Sitemaps';
	var $uses = array('Node');
	var $helpers = array('Time');
	var $components = array('RequestHandler');

	/*
	 * overload beforeFilter
	 */

	function beforeFilter() {
		$this->Auth->allow('index');
		return parent::beforeFilter();
    }

	/**
	 * set variables for sitemap
	 */

	function index(){    
		$this->set('nodes', $this->Node->find('all'));
	}
}
?>