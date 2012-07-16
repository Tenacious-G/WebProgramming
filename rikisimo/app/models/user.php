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

class User extends AppModel {

	var $name = 'User';
	var $useTable = 'users';
	var $uses = array('User','UsersUser', 'Node', 'Comment', 'Photo');	
	var $actsAs = array('Containable',
	'Image' => array(
		'settings' => array(
			'titleField' => 'username',
			'fileField' => 'photo',
			'defaultFile' => 'user_photo.jpg'),
		'photos' => array(
			'big' => array(
				'destination' => 'users',
				'size' => array('width' => 50, 'height' => 50)),
		)));
		
	/**
	 * validation rules
	 */

	var $validate = array(
		'username' 	=> array(
			'notEmpty'  => array(
				'rule'      => 'notEmpty',
				'message'   => 'Please write your desired username',
			),
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please write your desired password',
			),		
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Only alphanumeric characters',
				'allowEmpty' => true,
			)
		),
		'email'=> array(
			'email' => array(
				'rule' => 'email',
				'message' => 'Please enter a valid email address',
			),
			'unique' => array(
				'rule'    => 'isUnique',
				'message'    => 'This email is already in use',
				'allowEmpty' => true,                                
		)));
		

	/**
	 * Associations
	 */

	var $hasAndBelongsToMany = array(
		'Friend' => array(
			'className'=>'User',
			'joinTable' => 'users_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'friend_id',
			'unique' => false,
			'fields' => array('id','username','slug','photo')
		));
	
	var $hasMany = array(
		'Node'=>array('className'=>'Node'),
		'messageFrom'=>array(
			'className'=>'Message',
			'foreignKey' => 'from_user_id',
			'dependent' => true,
		),
		'messageTo'=>array(
			'className'=>'Message',
			'foreignKey' => 'to_user_id',
			'dependent' => true,
		),
		'Comment'=>array(
			'className'=>'Comment',
			'dependent' => true,
		),
		'Photo' => array(
			'className'=>'Photo',
			'order'=>'created desc',
		),
		'Vote' => array(
			'className'=>'Vote',
			'dependent' => true,
		)
	); 
  
	/**
	 * hashPasswords for Auth component
	 * Don't use Auth autohash so if there are errors we can populate the password fields in 
	 * the form without having the password hash.
	 *
	 * @access public
	 * @param $data mixed
	 * @param $force bool
	 */

	function hashPasswords($data, $force = false) {
		if (is_array($data) && isset($data[$this->alias])) {
			if ($force && isset($data[$this->alias]['password'])) {
				$data[$this->alias]['password'] = 
					Security::hash($data[$this->alias]['password'], null, true);
			}
		}
		return $data;  
	}

	/**
	 * create the user slug before we save.
	 */

	function beforeSave() {
		if(!empty($this->data['User']['username']) and !isset($this->data['User']['id'])) {
			$this->data['User']['slug'] = $this->__getSlug($this->data['User']['username']);
		}
		if(isset($this->data['User']['password'])) {
			$this->data = $this->hashPasswords($this->data, true);
		}
		return parent::beforeSave();
	}
  
	/**  
	 * Save friend if user is not yet in friend list.
	 *
	 * @param $user_id int
	 * @param $friend_id int
	 */
	 
	function add_friend($user_id,$friend_id) {	
	  if(!$this->is_friend($user_id, $friend_id) or $user_id == $friend_id) return false;
	          
	  $new_friend['UsersUser']['user_id'] = $user_id;
	  $new_friend['UsersUser']['friend_id'] = $friend_id;
	  
	  return $this->UsersUser->save($new_friend);
	}
  
	/**
	 * delete friend
	 *
	 * @param $user_id int
	 * @param $friend_id int
	 */
   
	function delete_friend($user_id,$friend_id) {
		return $this->UsersUser->deleteAll(array('user_id'=>$user_id, 'friend_id'=>$friend_id));
	}
  
	/** 
	 * check if users are friends
	 *
	 * @param $user_id int
	 * @param $friend_id int
	 */
   
	function is_friend($user_id, $friend_id) {
		$is_friend = $this->UsersUser->find(array('user_id'=>$user_id,'friend_id'=>$friend_id));
		return empty($is_friend);
	}
  
	/**
	 * delete user and set their submited nodes and comments to 
	 * anonymous user.
	 *
	 * @param $user_id int
	 */
   
	function delete_user($user_id) {
		$this->recursive = -1;
		$user = $this->Find(array('User.id'=>$user_id));
		if(empty($user)) return false;

		$this->Node->updateAll(array('Node.user_id'=>null),array('Node.user_id'=>$user_id));
		
		$this->Photo->recursive = -1;
		$photos = $this->Photo->find('all', array('conditions'=>array('Photo.user_id'=>$user_id)));
		$this->Photo->deleteAll(array('Photo.id'=>Set::extract('/Photo/id', $photos)));
	
		return $this->delete($user_id);
	}

	/**
	 * get top users ordered by comment votes received.
	 * 
	 * @param $limit int
	 */
	
	function getTopUsers($limit = 3) {
		$this->recursive = -1;
		$this->contain(array('Vote', 'Node', 'Comment'=>array('Commentvote')));
		$top = $this->find('all');
		
		$topUsers = array();
		foreach($top as $user) {
			$user['totalComments'] = count($user['Comment']);
			$user['totalRatings'] = count($user['Vote']);
			$user['totalCommentvotes'] = array_sum(Set::extract('/Comment/Commentvote/vote', $user));
			$user['totalAdds'] = count($user['Node']);
			$topUsers[] = $user;
		}
		usort($topUsers, array("User", "__topSort"));
		
		return array_slice($topUsers, 0, $limit);
	}
	
	/**
	 * generate unique slugs to use in user url
	 *
	 * @param $username string
	 */

	function __getSlug($username) {
		$username_slug = Inflector::slug(strtolower($username),'.');
		if(!empty($username_slug)) $username = $username_slug;
		
		$this->recursive = -1;
		$slugs = $this->find('all',
			array('conditions'=>array('User.slug like '=>'%'.$username),
			'fields'=>array('User.slug')));
		if(!empty($slugs)) {
			$n=1;
			$slugs = Set::extract('/User/slug', $slugs);
			while(in_array(($n.'.'.$username), $slugs)) $n++;
			$username = ($n.'.'.$username);
		}
		return $username;
	}

	/**
	 * custom function to order top users according to totalCommentvotes and totalVotes
	 *
	 * @param $a mixed
	 * @param $b mixed
	 */

	function __topSort($a,$b) {
		if($a['totalCommentvotes'] == $b['totalCommentvotes']) {
			if($a['totalRatings'] == $b['totalRatings']) return 0;
			return ($a['totalRatings'] < $b['totalRatings'])? 1 : -1;
		}
		return ($a['totalCommentvotes'] < $b['totalCommentvotes'])? 1 : -1;
	}
}
?>