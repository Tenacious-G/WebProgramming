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

class City extends AppModel {

	var $name = 'City';
	var $useTable = 'cities';
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'Name must be at least 2 characters long',
			'required' => true,
			'allowEmpty' => false,
		),
		'country_id' => array(
			'rule' => array('numeric'),
			'message' => 'You must select a country',
			'required' => true,
			'allowEmpty' => false,
		)								
	);
		
	var $belongsTo = array('Country'=>array('className'=>'Country'));
	var $hasMany = array('Node'=>array('className'=>'Node')); 

	/**
	 * create city slug before save
	 */

	function beforeSave() {
		if($slug = Inflector::slug(strtolower($this->data['City']['name']),'-')) {
			$this->data['City']['slug'] = $slug;
		}
		else {
			$this->data['City']['slug'] = $this->data['City']['name'];
		}
		$this->data['City']['name'] = ucfirst($this->data['City']['name']);
		return parent::beforeSave();
	}

	/**
	*  Get all cities with nodes
	*/

	function getCities() {
		$this->recursive = -1;
		$countries = $this->find('all', array(
			'fields'=>array('Country.name', 'City.name', 'City.slug', 'count(Node.id)'),
			'joins' => array('LEFT JOIN nodes AS Node ON City.id = Node.city_id',
							 'LEFT JOIN countries AS Country ON Country.id = City.country_id'),
			'group' => 'City.id HAVING count(Node.id) > 0'
			));
		$f = array();
		if(!empty($countries)) {
			foreach($countries as $c) {
				$f[__($c['Country']['name'], true)][] = $c['City'];
			}
		}
		return $f;
	}

	/**
	 * return a list of cities ready to use in form selects
	 */

	function getCitiesList() {
		$this->recursive = 0;
		$cities = $this->Find('all');

		$list = array();
		foreach($cities as $city) {
			$list[__($city['Country']['name'], true)][$city['City']['id']] = $city['City']['name']; 
		}
		return($list);
	}
}
?>