<?php
$users_id = Set::extract('/Commentvote/user_id', $comment);
echo sprintf(__n('%s people like this', '%s people like this', 
	count($comment['Commentvote']), true), count($comment['Commentvote']));

if(!in_array($me, $users_id) and $comment['Comment']['user_id']!=$me) {
	echo ' · '.$ajax->link(__('Like', true), 
		array('controller'=>'comments', 'action'=>'vote', $comment['Comment']['id']),
		array('update'=>'comment-vote-'.$comment['Comment']['id'])).' ';
}
?>