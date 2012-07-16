<h1 class="generic_box"><?php __('Users'); ?></h1>
<?php if(!empty($users)): 
?>
<div class="small_photos" style="width: 300px">
	<?php
		foreach($users as $user):			
			echo '<div class="top-users-box">';
			echo $html->link($html->image('users/'.$user['User']['photo'],
			array('alt'=>h($user['User']['username']))), 
			array('action'=>'view', $user['User']['slug']), array('escape'=>false)).' ';
				echo '<p class="general_info">';		
			echo $html->link($user['User']['username'], array('action'=>'view', $user['User']['slug']));
			echo '<br/> '.sprintf(__n('Rated %s listing.', 'Voted %s listings.', 
				count($user['Vote']), true), count($user['Vote']));
			echo '<br/> '.__('Review score', true).': '.$user['totalCommentvotes'];
			echo '</p>';
			echo '<p>';
				echo $this->Element('user_badges', array('user'=>$user));
			echo '</p>';
			echo '<div class="clear"></div>';
			
			echo '</div>';
		endforeach;
	?>
</div>

<p style="margin-top: 20px;">
	<?php
		echo $this->Element('paginator');
	?>
</p>
<?php else: ?>
	<p><?php __('There are no users.'); ?></p>
<?php endif; ?>