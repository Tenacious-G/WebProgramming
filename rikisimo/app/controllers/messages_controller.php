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

class MessagesController extends AppController{

	var $name = 'Messages';
	var $uses = array('Message', 'User');
	var $helpers = array('Time', 'Html', 'Form', 'Javascript');
	var $components = array('RequestHandler', 'Email');
    
	var $paginate = array(
		'limit' => 25,
		'order' => array('Message.created' => 'DESC')
	);

	/**
	 * overload beforeFilter
	 */

	function beforeFilter() {
		$this->set('subtitle', 'Messages');
		parent::beforeFilter();
	}

	/**
	 * Show incoming messages.
	 */

	function inbox() {
		$this->__setMessages(true);
		$this->set('title_for_layout', __('Inbox', true));
		$this->render('mailbox');
	}

	/** 
	 * Show sent messages.
	 */

	function outbox() {
		$this->__setMessages(false);	
		$this->set('title_for_layout', __('Outbox', true));		
		$this->render('mailbox');
	}

	/**
	 *  Write and send a message to a user and notify with email.
	 *
	 * @param $user_id int
	 */

	function write($user_id) {
		$user = $this->User->recursive = -1;
		$user = $this->User->find(array('User.id'=>$user_id));
		if(!$user) $this->cakeError('error404');
		$this->set('user',$user);
      
		if(!empty($this->data)) {
			$this->data['Message']['from_user_id'] = $this->Auth->user('id');
			$this->data['Message']['to_user_id'] = $user_id;
			if($this->Message->save($this->data)) {
				if($user['User']['notification']) {
					$this->set('message_id',$this->Message->getInsertID());
					$this->Email->from    = Configure::read('appSettings.systemEmail');
					$this->Email->to      = $user['User']['username'].'  <'.$user['User']['email'].'>';
 					$this->Email->subject = sprintf(__('You have a new message in %s',true),
						Configure::read('appSettings.appName'));
					$this->Email->delivery = 'mail';
					$this->Email->template = 'new_message';
					$this->Email->sendAs = 'text';
					$this->Email->send();
					// debug($this->Session->read('Message.email'));
				}
				$this->Session->setFlash(__('Message saved',true));
				$this->redirect(array('controller'=>'users', 'action'=>'view', $user['User']['slug']));
			}      
		}
		$this->set('title_for_layout', __('Write a message', true));
	}

	/**
	 *  get message if it has been sent to or from the current user.
	 * 
	 * @param $message_id int
	 */

	function read($message_id) {
		$this->Message->contain(array(
			'userFrom'=>array('fields'=>array('id', 'photo', 'username', 'slug')),
			'userTo'=>array(  'fields'=>array('id', 'photo', 'username', 'slug'))));
     
		$me_id = $this->Auth->user('id'); 
		$message = $this->Message->find(array('Message.id'=>$message_id, 
			'or'=>array('userFrom.id'=>$me_id, 'userTo.id'=>$me_id)));
		if(!$message) $this->cakeError('error404');

		if($me_id!=$message['userFrom']['id']) {
			$user =  $message['userFrom'];
			if($message['Message']['new'] == 1) {
				$this->Message->id = $message['Message']['id'];
				$this->Message->saveField('new',0);
				$this->set('messages_number',$this->Message->countUserMessages($me_id));        
			}
		}
		else {
			$user = $message['userTo'];
		}

		$this->set('user',$user);
		$this->set('message',$message);
		$this->set('me',$me_id);	
		$this->set('title_for_layout', __('Read the message', true));			
	}

	/**
	 * deleteMessage method returns the mailbox name so we use it to 
	 * redirect the user back to where he was
	 *
	 * @param $message_id int
	 */

	function delete_message($message_id) {
		$mbox = $this->Message->deleteMessage($message_id,$this->Auth->user('id'));
		if($mbox) {
			$this->Session->setFlash(__('Message deleted',true));
			$this->redirect(array('action'=>$mbox));
		}
		else {
			$this->redirect(array('action'=>'inbox'));
		}
	}

	/**
	 * Private function used to set the user messages depending on the
	 * requested mail box.
	 *
	 * @param $inbox bool 
	 */

	function __setMessages($inbox=true) {    
		$mbox = ($inbox)? 'to_user_id' : 'from_user_id';
		$delm = ($inbox)? 'to_user_delete' : 'from_user_delete';
		$user_id = $this->Auth->user('id');
		$this->paginate['contain'] = array(
					'userFrom'=>array('fields'=> array('id', 'photo', 'username', 'slug')),
					'userTo'=> array('fields'=>array('id', 'photo', 'username', 'slug')));
		$this->set('messages',$this->paginate('Message',array($mbox=>$user_id, $delm=>0)));
	}	
}
?>