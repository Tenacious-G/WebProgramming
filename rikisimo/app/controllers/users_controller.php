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

class UsersController extends AppController {

	var $name = 'Users';    
	var $components = array('Email'); 
	var $helpers = array('Html', 'Form', 'Time', 'Ajax', 'Javascript', 
						'Text', 'RfRating');
	var $uses = array('User', 'Node', 'Country', 'City', 'Comment','Vote');
	var $paginate = array('limit' => 30, 'order' => array('User.created' => 'DESC'));    	

	/**
	 * overload beforeFilter
	 */

	function beforeFilter() {     
		if($this->params['action']=='register' or $this->params['action']=='change_password') {
			$this->Auth->authenticate = $this->User;	
		}		
		$this->Auth->allow('login', 'logout', 'view', 'register','recover',
			'code_login', 'index');
		return parent::beforeFilter();
	}
    
	/**
	 * User login. Redirect user to home page if he is already logged in.
	 */
     
	function login() {
		if($this->Auth->user('id')) $this->redirect(array('controller'=>'nodes', 'action'=>'home'));
		$this->set('title_for_layout', __('Sign in', true));
	}
    
	/**
	 * User logout and redirect to home.
	 */

	function logout() {
		$this->redirect($this->Auth->logout());
	}

	/**
	 * pagiante users ordered by score
	 */

	function index() {
		$this->set('title_for_layout', __('Users', true));
		$this->paginate['contain'] = (array('Vote', 'Node', 'Comment' => array('Commentvote')));

		$this->paginate['order'] = 'User.created DESC';
		$users = $this->paginate('User');
		$_users = array();
		foreach($users as $user) {
			$user['totalRatings'] = count($user['Vote']);
			$user['totalAdds'] = count($user['Node']);
			$user['totalComments'] = count($user['Comment']);
			$user['totalCommentvotes'] = array_sum(Set::extract('/Comment/Commentvote/vote', $user));
			$_users[] = $user;
		}
		
		$this->set('users', $_users);
	}
	
	/**
	 * User registration. 
	 * First registered user will have admin permissions.
	 */

	function register() {
		if($this->Auth->user('id')) $this->redirect(array('controller'=>'nodes', 'action'=>'index'));

		if($this->data) {
			$this->User->recursive = -1;
			if(!$this->User->find('count')) $this->data['User']['admin'] = true;
			else $this->data['User']['admin'] = false;

			if($this->User->save($this->data)) {
				$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
				$this->Auth->login($this->data);
				if($this->data['User']['admin']) $this->redirect(array('admin'=>true, 'controller'=>'settings'));
				else $this->redirect(array('controller'=>'users', 'action'=>'view'));
			}
		}

		$this->set('title_for_layout', __('Sign up', true));
	}

	/**
	 * View user's profile.
	 * Get all user's friends, photos and comments.
	 * If $user_slug is null try to display the current authenticated user profile.
	 *
	 * @param string $user_slug
	 */

	function view($user_slug=null) {
		if(!$user_slug and !($user_slug = $this->Auth->user('slug'))) $this->cakeError('error404');
      
		$this->User->contain(array('Friend', 'Node', 'Photo'=>array('limit'=>5,'Node.slug'),
								 'Vote', 'Comment'=>array('Commentvote')));
		$user = $this->User->find(array('User.slug'=>$user_slug));
		if(empty($user)) $this->cakeError('error404');

		$this->paginate['fields'] = array('Node.id', 'Node.name', 'Node.slug', 'City.name', 'City.slug',
		 	'Country.name', 'Country.slug', 'Vote.vote');
		$this->paginate['recursive'] = -1;
		$this->paginate['joins'] = array('LEFT JOIN votes as Vote on Node.id = Vote.node_id',
			'LEFT JOIN cities AS City on Node.city_id = City.id',			
			'LEFT JOIN countries AS Country on City.country_id = Country.id',			
		);
		$this->paginate['order'] = 'Vote.vote DESC';
		$this->paginate['limit']=10;
		$voted = $this->paginate('Node', array('Vote.user_id'=>$user['User']['id']));

		if($user['User']['id']==$this->Auth->user('id')) {
			$this->set('homepage',true);
		}

		$user['totalRatings'] = count($user['Vote']);
		$user['totalAdds'] = count($user['Node']);
		$user['totalComments'] = count($user['Comment']);
		$user['totalCommentvotes'] = array_sum(Set::extract('/Comment/Commentvote/vote', $user));

		$this->set('user',$user);
		$this->set('voted', $voted);
		$this->set('is_friend',$this->User->is_friend($this->Auth->user('id'),$user['User']['id']));
		$this->set('title_for_layout', ucfirst($user['User']['username']));
	}

	/**
	 * Edit current user's profile
	 */

	function edit() {
		$this->User->recursive = -1;
		$user = $this->User->find(array('User.id'=>$this->Auth->user('id')));

		if(!empty($this->data)) {
			$this->data['User']['id'] = $this->Auth->user('id');
			$this->data['User']['admin'] = $user['User']['admin'];

			if($this->User->save($this->data)) {
				$this->Session->setFlash(__('Profile saved',true));
				$this->redirect(array('action'=>'view'));
			}
		}
		else {      
			$this->data = $user;
		}
		$this->set('user',$user);
		$this->set('title_for_layout', __('Edit user data', true));
	}

	/**
	 * Change user's password.
	 */

	function change_password() {
		$this->set('title_for_layout', __('Change password', true));		
		if(!empty($this->data)) {
			$this->User->recursive = -1;
			$this->data['User']['id'] = $this->Auth->user('id');
			if($this->User->save($this->data)) {
				$this->Session->setFlash(__('New password saved',true));
				$this->redirect(array('action'=>'view'));
			}
		}
	}

	/**
	 * Upload user profile photo with the upload component.
	 */

	function upload() {	
		if(!empty($this->data)) {
			$this->data['User']['id'] = $this->Auth->user('id');
			$this->data['User']['username'] = $this->Auth->user('username');
			if($this->User->save($this->data)) {
				$this->Session->setFlash(__('Photo saved',true));
				$this->redirect(array('action'=>'view', $this->Auth->user('slug')));
			}
			$this->redirect(array('action'=>'edit'));
		}
		$this->set('title_for_layout', __('Upload profile photo', true));
	}

	/**
	 * Delete user profile photo.
	 */

	function delete_photo() {
		$data['User']['filedata'] = '';
		$data['User']['id'] = $this->Auth->user('id');
		if($this->User->save($data)) {
			$this->Session->setFlash(__('Photo deleted',true));
			$this->redirect(array('controller'=>'users', 'action'=>'view'));
		}
		else {
			$this->redirect(array('controller'=>'users', 'action'=>'edit'));
		}
	}
		  
	/**
	 * Add user to the friend list. Param $friend_id is the id of the
	 * user to be addded as friend.
	 *
	 * @param int $friend_id
	 */

	function add_friend($friend_id) {
		$this->User->recursive = -1;
		$friend = $this->User->find(array('id'=>$friend_id),array('slug'));
		if(empty($friend)) $this->cakeError('error404');
        
		if($this->User->add_friend($this->Auth->user('id'), $friend_id)) {
			$this->Session->setFlash(__('Friend added to friend list',true));
			$this->redirect(array('controller'=>'users', 'action'=>'view', $friend['User']['slug']));
		}
		else {
			$this->redirect(array('action'=>'view', $friend['User']['slug']));		    
		}
	}

	/**
	 * remove friend from friend list
	 *
	 * @param int $friend_id
	 */

	function delete_friend($friend_id) {
		$this->User->recursive = -1;
		$user = $this->User->find(array('id'=>$friend_id),array('slug'));
		if(!empty($user)) {
			$this->User->delete_friend($this->Auth->user('id'),$friend_id);
			$this->Session->setFlash(__('User removed from friend list',true));
			$this->redirect(array('action'=>'view', $user['User']['slug']));
		}
		else {
			$this->redirect(array('action'=>'view'));
		}
	}
		   
	/**
	 * Delete user account after confirmation.
	 * 
	 * @param $confirmation string
	 */

	function delete_user($confirmation=null) {
		if($confirmation) {
			$this->User->delete_user($this->Auth->user('id'));
			$this->redirect($this->Auth->logout());
		}
	}
		    
	/**
	 * If user forgot the password we create a new url for him
	 * to access his account.
	 */

	function recover() {
		$this->set('title_for_layout', __('Password recovery', true));		
		if(!empty($this->data)) {
			$email = $this->data['User']['email'];
			$this->User->recursive = -1;
			$user = $this->User->find(array('email'=>$email),array('id', 'username','email')); 
			if(!empty($user)) {
				$validation_code = sha1(rand().'-'.time());
				$user['User']['validation_code'] = $validation_code;
				if($this->User->save($user)) {
					$this->set('recover',true);
					$this->set('code',$validation_code);
					$this->set('user',$user);
					$this->Email->from = Configure::read('appSettings.systemEmail');
					$this->Email->to = $user['User']['username'].'  <'.$user['User']['email'].'>';
					$this->Email->subject = sprintf(__('Password Recovery for %s',true),
						Configure::read('appSettings.appName'));
					$this->Email->delivery = 'mail';
					$this->Email->template = 'password_recovery';
					$this->Email->sendAs = 'text';
					$this->Email->send();
					// debug($this->Session->read('Message.email'));		            
				}
			}
			else {
				$this->set('recover',false);
			}
		}
	}
		     
	/**
	 * login a user without using password with the random code 
	 * created with the recover action.
	 *
	 * @param $code string
	 */

	function code_login($code = null) {
		if($code===null) $this->cakeError('error404');

		$this->set('title_for_layout', __('Recover your password', true));		
		$user = $this->User->find(array('validation_code'=>$code), array('id', 'email', 'username','password'));
		if(!empty($user)) {
			$this->Auth->login($user);
			$this->set('messages_number',$this->Message->countUserMessages($this->Auth->user('id')));
			$this->set('code_login',true);
			$remove_code['User']['id'] = $user['User']['id'];
			$remove_code['User']['validation_code'] = null;
			$this->User->save($remove_code);
		}
		else{
			$this->set('code_login',false);		      
		} 
	}
		     
	////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////  ADMIN
	////////////////////////////////////////////////////////////////
		   
	/**
	 * admin index paginates a list of users
	 */
		    
	function admin_index() {
		$this->set('title_for_layout', __('Manage users', true));
		$this->set('users',$this->paginate('User'));
	}

	/**
	 * Admin function to delete users.
	 *
	 * @param $user_id int
	 */

	function admin_delete_user($user_id) {
		$this->User->delete_user($user_id);
		$this->flash(__('User deleted', true), array('action'=>'index'));
	}
		   
	/**
	 * Admin change user password
	 *
	 * @param $user_id int
	 */
		    
	function admin_change_password($user_id) {
		if(!empty($this->data)) {
			$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
			$this->data['User']['password_confirm'] =
				$this->Auth->password($this->data['User']['password_confirm']);
			$this->data['User']['id'] = $user_id;
			if($this->User->save($this->data)) {
				$this->flash(__('New password saved',true),'/admin/users/index');
			}
		}
		$this->set('user_id',$user_id);
		$this->set('title_for_layout', __('Edit password', true));
	}
}
?>