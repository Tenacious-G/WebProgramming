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

class Node extends AppModel {

	var $name = 'Node';
	var $useTable = 'nodes';
	var $actsAs = array('Containable');
	var $use = array('Node', 'NodesUser');

	var $validate = array(	
		'name' => array(
			'minLength' => array(
				'rule' => array('minLength', 2),
				'message' => 'Name must be at least 2 characters long',
			),
			'unique' => array(
				'rule' => 'notSubmitedBefore',
				'on' => 'create',
				'message' => 'It seems like this has been submited before'
				)
			),
		'notes' => array(
			'rule' => array('minLength', 2),
			'message' => 'Please write a description',
			),
		'address' => array(
			'rule' => array('minLength', 2),
			'message' => 'Please write the address',
			),
		'price_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select the price',
			),
		'category_id' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select the category',				
			),
		'web' => array(
			'rule' => 'url',
			'message' => 'Please enter a valid web address',
			'required' => false,
			'allowEmpty' => true,
			),
	);

	var $hasAndBelongsToMany = array(
		'Tag'=>array('className'=>'Tag'),
	);
  
	var $belongsTo = array(
		'City'=>array('className'=>'City'),
			'User'=>array('className'=>'User',
			'fields' => array('id','username','slug','photo')),
			'Price' => array('className'=>'Price'), 
			'Category' => array('className'=>'Category')
	);
  
	var $hasMany = array(
		'Comment'=>array('className'=>'Comment',
			'order'=> 'Comment.created DESC',
			),
		'Vote' => array('className'=>'Vote',
		'finderQuery' => 'select node_id, count(node_id) as votes, sum(vote) as rating from votes as Vote where node_id in ({$__cakeID__$}) group by Vote.node_id '
			),
		'userVote' => array('className'=>'Vote',
			),						
		'Photo' => array('className'=>'Photo',
			'order' => 'created desc'
			), 
	);    

	/**
	 * As the node id is used in the slug we create it after saving the node.
	 * 
	 * @param $created boolean
	 */

	function afterSave($created) {
		if($created) {
			$node_id = $this->getInsertID();
			$update_node['Node']['slug'] = $node_id;
			if($slug = Inflector::slug(strtolower($this->data['Node']['name']), '-')) {
					$update_node['Node']['slug'].='-'.$slug;		
			}
      		$update_node['Node']['id'] = $node_id;
      
			$this->id = $this->getInsertID();
			$this->saveField('slug',$update_node['Node']['slug']);
		} 
	}

	/**
	 * remove the http::// part in the url
	 */

	function beforeSave() {
		if(isset($this->data['Node']['web'])){
			if(preg_match('(^http://)',$this->data['Node']['web'])){
				$this->data['Node']['web'] = substr($this->data['Node']['web'],7);
			}
		}
		return parent::beforeSave();
	}

	/**
	 * Check if this node has been submited before. To do so we check 
	 * the node's city and address.
	 */

	function notSubmitedBefore() {
		if(!isset($this->data['Node']['city_id'])) return false;
		$exists = $this->find(array('address'=>$this->data['Node']['address'],
			'city_id'=>$this->data['Node']['city_id']), array('id'), null, -1);
		return empty($exists);
	}
	
	/**
	 * get nodes near lat, lng
	 *
	 * @param $lat int
	 * @param $lng int
	 * @param $city_id int
	 * @param $distance int
	 * @param $limit int
	 */

	function getNear($lat, $lng, $city_id = null, $distance = 2, $limit = 10) {
		$this->contain(array('Tag', 'Category', 'City'));
		$fields = array('Node.id', 'Node.address', 'Node.slug', 'Node.city_id', 'Node.name', 'Node.lat', 'Node.lng', '( 6371 * acos( cos( radians("'.$lat.'") ) * cos( radians( lat ) ) * cos( radians( lng ) - radians("'.$lng.'") ) + sin( radians("'.$lat.'") ) * sin( radians( lat ) ) ) ) AS distance', 'count(Vote.id) as Votes', 'SUM(Vote.vote)/count(Vote.id) as Points', 'SUM(Vote.vote) as totalPoints', 'Category.id', 'Category.value', 'Category.slug', 'City.slug');
		$conditions = array('');
		$group = 'Node.id HAVING distance < '.$distance;
		if($city_id) {
			$group.=' and city_id = '.$city_id;			
		}

		$joins = array('LEFT JOIN votes as Vote on Node.id = Vote.node_id');
		$order = array('distance');
		$nodes = $this->find('all', array('conditions'=>$conditions, 
			'fields'=>$fields, 'group'=>$group, 'joins'=>$joins, 'order'=>$order, 'limit'=>$limit));
		
		return $nodes;
	}

	/**
	 * get top rated nodes
	 *
	 * @param $limit int
	 */

	function getTopRated($limit = 10) {
		$this->recursive = -1;
		$this->contain(array('Vote'));
		$fields = array('Node.id', 'Node.name', 'Node.slug', 'Node.notes', 
			'Node.created', 'City.name', 'City.slug', 'Country.name', 'Country.slug', 'User.id',
			'User.username', 'User.slug', 'User.photo', 'count(Vote.id) as Votes', 
			'SUM(Vote.vote)/count(Vote.id) as Points', 'SUM(Vote.vote) as totalPoints');	
		$order = 'Votes DESC, Points DESC';
		$joins = array(
			'LEFT JOIN users AS User on Node.user_id = User.id',			
			'LEFT JOIN cities AS City on Node.city_id = City.id',			
			'LEFT JOIN countries AS Country on Country.id = City.country_id',
			'LEFT JOIN votes AS Vote on Vote.node_id = Node.id');
		$group = 'Node.id';
		
		$top_tmp = $this->find('all', array('fields'=>$fields, 'joins'=>$joins, 
			'group'=>$group, 'order'=>$order, 'limit'=>$limit));

		$top_ids = Set::extract('/Node/id', $top_tmp);
		$this->Photo->recursive = -1;
		$photos = $this->Photo->find('all', array('conditions'=>array('Photo.node_id'=>$top_ids)));
	
		$top = array();
		foreach($top_tmp as $top_node) {
			foreach($photos as $photo) {
				if($photo['Photo']['node_id'] == $top_node['Node']['id']) {
					$top_node['Photo'][] = $photo['Photo'];
				}
			}
			$top[] = $top_node;	
		}
		return $top;
	}
	
	/**
	 * return recently added nodes
	 *
	 * @param $limit int
	 */
	
	function getRecent($limit = 10) {
		$this->contain(array('City'=>array('fields'=>array('slug','name')),
                              'User'=>array('fields'=>array('id', 'photo', 'slug','username')),
                              'Vote'=>array('fields'=>array('votes','rating')),
							  'Photo',
                        ));
		return $this->find('all',array('order'=>'created desc','limit'=>$limit));
	}
}
?>