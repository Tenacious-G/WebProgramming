<div class="main_width">
	<h1><?php __('You are going to delete your account'); ?></h1>
	<p>
		<?php __('You are going to delete your user account, your private messages and personal data will be deleted.'); ?>
	</p>
		<?php 
		if($facebook_user) {
			echo '<p>';
		__('You are using a Facebook account, so you may want to remove this applicattion from your Facebook application settings at this moment.', true);	
			echo '</p>';
		}
		?>
	</p>
	<p>
		<?php __('Do you want to continue?'); ?>
	</p>
	
	<div class="bclear">
	<?php
		echo $html->link("<span>".__('No, I don\'t want to delete my account',true)."</span>",
			'/users/view', array('class'=>'button', 'escape'=>false));

		$options = array('class'=>'button', 'escape'=>false);
		if($facebook_user) $options['onclick'] = 'FB.logout();';
	
		echo $html->link("<span>".__('Yes, delete my account',true)."</span>",
			'/users/delete_user/confirmation', 
			$options);
	?>
		<div class="clear"></div>
	</div>
</div>