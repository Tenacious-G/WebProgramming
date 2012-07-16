<div id="form-block">
	<div class="info">
	<?php
		__('If you forgot your password tell us your email address and we\'ll help you recover it.');
	?>
	</div>

	<div class="form">
		<h1 class="generic_box"><?php __('Password recover'); ?></h1>
		<div>
			<?php
				if(isset($facebook_user)) {
					__('Your account is wired to your Facebook account. Use the facebook button to sign in.');
				}
				elseif(isset($recover)){
  					if($recover==true) {					
	    					__('We sent you an email');							
  					}
  					else {
    					__('Sorry this email is not being used for any user');
  					}
				}
				else {
  					echo $form->create('User',array('action'=>'recover'));
  					echo $form->input('email');
			?>
	
			<div class="submit">
				<?php
				echo $form->end(array(__('Submit',true), 'div'=>false));
				echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('cancel', true), array('action'=>'login')).".</p>";
				?>
				<div class="clear"></div>	
			</div>
			<?php
				echo $javascript->codeBlock("
					form = $(\"UserRecoverForm\");
					form.focusFirstElement();
					");
				}
			?>
		</div>
	</div>
	<div class="clear"></div>
</div>