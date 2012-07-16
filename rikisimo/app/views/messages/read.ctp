<div class="main_width">
	<h1 class="generic_box"><?php echo h($message['Message']['subject']); ?></h1>
	<div class="groupreply">
		<div class="groupreply-image">
			<?php echo $html->image('users/'.$user['photo'], array('alt'=>$user['username'])); ?>
		</div>

		<div id="user_info">
			<p class="general_info">
			<?php 
				echo __('Message sent by', true).' '.$html->link($user['username'],
					array('controller'=>'users', 'action'=>'view', 
					$user['slug'])).' '.$time->timeAgoInWords($message['Message']['created']); 
			?>
			</p>
			<?php echo nl2br(h($message['Message']['message'])); ?>
		</div>
		<div style="clear:both"></div>
	</div>

	<div class="bclear">
		<?php 
			echo $html->link("<span>".__('Reply',true).'</span>', 
				array('action'=>'write', $user['id']), array('class'=>'button', 'escape'=>false)); 

			echo $html->link("<span>".__('Delete Message',true)."</span>", 
				array('action'=>'delete_message', $message['Message']['id']), 
				array('class'=>'button', 'escape'=>false));

			if($me!=$message['userFrom']['id']) {
  				echo $html->link("<span>".__('Back',true)."</span>",
					array('action'=>'inbox'), array('class'=>'button', 'escape'=>false)); 
			}
			else {
  				echo $html->link("<span>".__('Back',true)."</span>",
					array('action'=>'outbox'), array('class'=>'button', 'escape'=>false)); 
			}
		?>
	</div>
</div>