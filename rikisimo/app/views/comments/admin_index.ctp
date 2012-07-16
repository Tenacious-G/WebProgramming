<div id="admin-content">
<?php
if(empty($comments)) {
	echo __('There are no reviews', true);
}
else {
	foreach($comments as $comment) {
		echo '<div class="comment-box">';
		echo '<div class="comment-info">';
		echo __('Posted',true).' '.$time->niceShort($comment['Comment']['created']).' ';
		echo __('by',true).' ';
		if(empty($comment['User']['username'])) {
			echo __('Anonymous',true);
		}
		else {
			echo $html->link($comment['User']['username'],
				array('admin'=>false, 'controller'=>'users', 'action'=>'view', $comment['User']['slug']));
		}


		echo '</div>';
		echo '<div class="comment">';
		echo nl2br(h($comment['Comment']['comment']));
		echo '</div>';
		echo '<p>';
		echo '('.$html->link($html->image('delete.png').' '.__('Delete review',true),
			array('controller'=>'comments', 'action'=>'delete', $comment['Comment']['id']),
		 	array('escape'=>false)).')';
		echo '</p>';
		echo '</div>';
	}
	echo $this->Element('paginator');
}
?>
</div>