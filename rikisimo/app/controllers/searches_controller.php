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

class SearchesController extends AppController{

	var $name = 'Searches';
	var $uses = array('Node', 'Price', 'Category', 'City');
	var $helpers = array('Html', 'Form', 'Time', 'Text', 'Ajax', 'Javascript', 
		'googleMap', 'RfRating');	
	var $paginate = array('limit' => 15, 'order' => array('Node.created' => 'desc'));

	/**
	 * allow all users to access search
	 */

	function beforeFilter() {
		$this->Auth->allow('*');
		parent::beforeFilter();
	}
	
	/**
	 * set some variables and display the search form
	 */

	function index() {
			$this->set('prices', $this->Price->find('list', array('fields'=>array('value'))));
			$this->set('cities', $this->City->getCitiesList());
			$this->set('categories', $this->Category->find('list', array('fields'=>array('value'))));			
			$this->set('title_for_layout', __('Search', true));

			$this->Session->delete('Search.term');
	}

	/**
	 * set variables and show main search results
	 */

	function results() {
		$this->set('title_for_layout', __('Search results', true));
		$this->set('prices', $this->Price->find('list', array('fields'=>array('value'))));
		$this->set('cities', $this->City->getCitiesList());
		$this->set('categories', $this->Category->find('list', array('fields'=>array('value'),
		 	'order'=>'value')));
		
		if($this->Session->read('Search.conditions') or !empty($this->data)) {
			if($this->Session->read('Search.conditions') and empty($this->data)) {
				$session_conditions = $this->Session->read('Search.conditions');
				$search = $session_conditions['Searches']['term'];
				$this->data = $session_conditions;
			}
			else {
				$this->Session->write('Search.conditions', $this->data);				
			}

			$this->Node->recursive = -1;
			$this->paginate['fields'] = array('Node.id', 'Node.name', 'Node.slug', 'Node.notes', 
						'Node.created', 'City.name', 'City.slug', 'Country.name', 'Country.slug', 'User.id',
						'User.username', 'User.slug', 'User.photo', 'count(Vote.id) as Votes', 
						'SUM(Vote.vote)/count(Vote.id) as Points', 'SUM(Vote.vote) as totalPoints');	
			$this->paginate['order'] = 'Votes DESC, Points DESC';
			$this->paginate['joins'] = array(
						'LEFT JOIN users AS User on Node.user_id = User.id',			
						'LEFT JOIN cities AS City on Node.city_id = City.id',			
						'LEFT JOIN countries AS Country on Country.id = City.country_id',
						'LEFT JOIN votes AS Vote on Vote.node_id = Node.id',
						'LEFT JOIN prices AS Price ON Node.price_id = Price.id',
						'LEFT JOIN categories AS Category ON Node.category_id = Category.id');
			$this->paginate['group'] = 'Node.id';
			$this->paginate['limit'] = 50;

			$conditions = array();
			if($this->data['Searches']['term']) {
				$conditions['OR'] = array('Node.name like '=>'%'.$this->data['Searches']['term'].'%', 
					'Node.notes like '=>'%'.$this->data['Searches']['term'].'%');				
			}
			if($this->data['Searches']['price']) {
				$conditions['Node.price_id'] = $this->data['Searches']['price'];
			}
			if($this->data['Searches']['category']) {
				$conditions['Node.category_id'] = $this->data['Searches']['category'];
			}
			if($this->data['Searches']['city']) {
				$conditions['Node.city_id'] = $this->data['Searches']['city'];
			}
			
			$nodes = $this->paginate('Node', $conditions);
			
			$this->set('nodes', $nodes);
		}
	}
}
?>