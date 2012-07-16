<?php
/**
 * Copyright 2010 Ivan Ribas
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Ivan Ribas
 * @copyright Copyright 2008 Ivan Ribas
 * @license		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Tag extends AppModel {

	var $name = 'Tag';
	var $useTable = 'tags';
	var $uses = array('Tag', 'NodesTag');
	var $validate = array(
		'name' => array(
			'rule' => array('minLength', 2),
			'message' => 'Tag must be at least 2 characters long',
			'required' => true 
		),
	);

	var $hasAndBelongsToMany = array('Node'=>array(
		'className'=>'Node',
		'unique' => false,
	)); 

	/**
	 * overload beforeSave function to create the tag slug
	 */

	function beforeSave() {
		if($slug = Inflector::slug(strtolower(trim($this->data['Tag']['name'])),'-')) {
			$this->data['Tag']['slug'] = $slug;
		}
		else $this->data['Tag']['slug'] = $this->data['Tag']['name'];
		return parent::beforeSave();
	}

	/**
	 * returns the most used tags
	 *
	 * @param $city_id int
	 * @param $limit int
	 */

	function getTopTags($city_id = null, $limit = 30) {
		$conditions = array();
		if($city_id) $conditions = array('Node.city_id'=>$city_id);
		$toptags = $this->find('all', array(
			'fields'=>array('Tag.slug', 'Tag.name', 'Tag.id', 'count(NodesTag.id) AS nodes_num'),
			'joins' => array('LEFT JOIN nodes_tags AS NodesTag ON NodesTag.tag_id = Tag.id',
							 'LEFT JOIN nodes AS Node ON Node.id = NodesTag.node_id'),
			'group' => 'Tag.id',
			'order' => 'Tag.name',
			'limit' => $limit,
			'conditions' => $conditions
			));
		return $toptags;
	}
  
	/**
	 * delete all tags in a node
	 *
	 * @param $node_id int
	 */

	function deleteNodeTags($node_id) {
		return $this->NodesTag->deleteAll(array('node_id'=>$node_id));
	}
}
?>