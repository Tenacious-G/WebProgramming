<div class="main_width">
	<h1 class="generic_box"><?php __('Password recover'); ?></h1>
		<?php
			if($code_login==true) {
				echo '<p>';
   				__('You are now logged in, now you can change your password.');
				echo '</p>';
				echo '<div class="bclear">';
  				echo $html->link(__('Change password', true), array('controller'=>'users',
 					'action'=>'change_password'));
				echo '</div>';
			}
			else {
				echo '<p>';
				echo __('Sorry, the login code is not valid', true);
				echo '</p>';
			}
		?>
</div>