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

class Message extends AppModel {
	var $name = 'Message';
	var $useTable = 'messages';
	var $actsAs = array('Containable');

	/**
	 * validation
	 */

	var $validate = array(
		'subject' 		=> array(
                      'notEmpty'  => array(
                        'rule'      => 'notEmpty',
                        'message'   => 'Pleas write the subject',
                       )),
		'message' => array(
                     'notEmpty' => array(
                        'rule' => 'notEmpty',
                        'message' => 'Please write your message',
                     )));

	/**
	 * associations
	 */

	var $belongsTo = array(
		'userFrom'=>array(
			'className' => 'User',
	        'foreignKey' => 'from_user_id',
	     ),  
	     'userTo'=>array(
			'className' => 'User',
	      	'foreignKey' => 'to_user_id',
	     ));
	
	/**
	 * see how many new messages a user have.
	 *
	 * @param $user_id int
	 */

	function countUserMessages($user_id) {
		return $this->find('count',array('conditions'=>
			array('Message.to_user_id' => $user_id, 'Message.new'=> 1,'Message.to_user_delete' => 0)));
	}
  
	/**
	 * Delete messages only if both users, sender and reciver, have deleted the message.
	 *
	 * @param $message_id int
	 * @param $user_id int
	 */

	function deleteMessage($message_id, $user_id) {
		$this->recursive = -1;
		$message = $this->find(array('Message.id'=>$message_id));
		if(empty($message)) return false;
    
		if($message['Message']['from_user_id'] == $user_id) {
			$delete = 'from_user_delete';
			$mbox = 'outbox';
		}
		else {
			$delete = 'to_user_delete';
			$mbox = 'inbox'; 
		}
    
		$message['Message'][$delete] = 1;
		if($message['Message']['from_user_delete'] and $message['Message']['to_user_delete']) {
			$this->delete($message_id);
		}
		else {
			$this->save($message);
		}
		return $mbox;
	}
}
?>