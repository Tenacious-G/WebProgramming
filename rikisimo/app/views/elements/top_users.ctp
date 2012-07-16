<?php 
	if(!empty($users)) {
		echo '<h1 class="generic_box">'.__('Top users', true).'</h1>';
		foreach($users as $user) {	
			echo '<div class="top-users-box">';
			echo $html->link($html->image('users/'.$user['User']['photo'],
			array('alt'=>h($user['User']['username']))), 
			array('controller'=>'users', 'action'=>'view', $user['User']['slug']), array('escape'=>false)).' ';
				echo '<p class="general_info">';		
			echo $html->link($user['User']['username'], array('controller'=>'users', 'action'=>'view',
				$user['User']['slug']));
			echo '<br/> '.sprintf(__n('Rated %s listing.', 'Voted %s listings.', 
				count($user['Vote']), true), count($user['Vote']));
			echo '<br/> '.__('Review score', true).': '.$user['totalCommentvotes'];
			echo '</p>';
			echo '<p>';
				echo $this->Element('user_badges', array('user'=>$user));
			echo '</p>';
			echo '<div class="clear"></div>';
			
			echo '</div>';
		}
	}
?>