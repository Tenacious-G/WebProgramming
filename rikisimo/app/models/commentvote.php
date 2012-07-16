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

class Commentvote extends AppModel {
	var $name = 'Commentvote';
	var $use = array('Commentvote', 'Comment');
	var $actsAs = array('Containable');
	var $recursive = -1;
       
	var $belongsTo = array('Comment','User');

	/**
	 * do not save vote if user already voted for this comment
	 *
	 */

	function beforeSave() {
		$exists = $this->find(array('comment_id'=>$this->data['Commentvote']['comment_id'],
			'user_id'=>$this->data['Commentvote']['user_id']));
		if($exists) return false;
		return parent::beforeSave();
	}
	
	/**
	 * vote for a comment after making sure it is a valid comment.
	 * users can't vote their own comments.
	 *
	 * @param $user_id int
	 * @param $comment_id int
	 * @param $vote int
     */
	
	function vote($user_id, $comment_id, $vote) {
		$comment = $this->Comment->find(array('Comment.id'=>$comment_id));
		if(empty($comment) or $comment['Comment']['user_id']==$user_id) return false;
		$cvote['vote'] = $vote;
		$cvote['user_id'] = $user_id;
		$cvote['comment_id'] = $comment_id;
		return $this->save($cvote);
	}

    /**
     * remove vote from node, we need to update rating and votes fields
     * in nodes table.
     *
     * @param $user_id int
     * @param $comment_id int
     */
        
	function removeVote($user_id, $comment_id) {
		return $this->deleteAll(array('user_id'=>$user_id, 'comment_id'=>$comment_id));
    }
}
?>