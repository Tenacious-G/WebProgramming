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

class CommentsController extends AppController {

	var $name = 'Comments';    
	var $helpers = array('Html', 'Form', 'Time', 'Ajax', 'Javascript');
	var $uses = array('User', 'Node', 'Comment', 'Commentvote');
	var $paginate = array('limit' => 15, 'order' => array('Comment.created' => 'DESC'));

	/** 
	 * write a comment for a listing
	 *
	 * @param int $node_id
	 */

	function write($node_id = null) {
		$this->Node->recursive = 0;
		$node = $this->Node->find(array('Node.id'=>$node_id));
		if(empty($node) || $node_id === null) $this->cakeError('error404');
		if(!empty($this->data)) {
			$this->data['Comment']['node_id'] = $node_id;
			$this->data['Comment']['user_id'] =  $this->Auth->user('id');

			if($this->Comment->save($this->data)) {
				$this->Session->setFlash(__('Review saved',true));
				$this->redirect(array('controller'=>'nodes', 
					'action'=>'view', $node['City']['slug'], $node['Node']['slug']));
			}
		}
		$this->set('node',$node);
		$this->set('title_for_layout', __('Write a review', true));
	}

	/**
	 * vote for a comment giving one comment score point and render ajax view
	 *
	 * @param int $comment_id
	 * @param int $vote
	 */
	
	function vote($comment_id, $vote=1) {
		$vote = 1;	// fix the vote score to 1
		$this->Comment->contain(array('Commentvote'));
		$comment = $this->Comment->find(array('Comment.id'=>$comment_id));
		if(empty($comment)) $this->cakeError('error404');
	
		$this->layout = 'ajax';	
		$this->Commentvote->vote($this->Auth->user('id'), $comment_id, $vote);
		$this->Comment->contain(array('Commentvote'));
		$comment = $this->Comment->find(array('Comment.id'=>$comment_id));
		
		$this->set('me', $this->Auth->user('id'));
		$this->set('comment', $comment);
	}

	////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////  ADMIN
	////////////////////////////////////////////////////////////////////

	/**
	 * Show all comments
	 */

	function admin_index() {
		$this->set('title_for_layout', __('Manage comments', true));
		$this->paginate['contain'] = array('User');
		$this->set('comments', $this->paginate('Comment'));
	}

	/**
	 * Delete one comment.
	 *
	 * @param $comment_id int
	 */

	function admin_delete($comment_id) {
		$this->Comment->delete($comment_id);
		$this->flash(__('Review deleted', true), array('action'=>'index'));
	}
}
?>