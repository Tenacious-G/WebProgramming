<div id="form-block">
	<div class="info">
		<p><?php __('Sign in with your email and password.') ?></p>
		<p>
		<?php 
		echo __('Don\'t you have an account?').' ';
		if(Configure::read('appSettings.fb_app_id')):
			echo sprintf(__('%s now or use your Facebook account to log in.', 
				true), $html->link(__('Sign up', true), array('action'=>'register')));	
		else:
			echo $html->link(__('Sign up', true), array('action'=>'register'));
		endif;
		?>
		</p>
	</div>
	<div class="form">
		<h1 class="generic_box"><?php __('Login'); ?></h1>
		<?php
			echo $form->create('User', array('action' => 'login'));
			echo $form->input('email',
				array('label'=>$html->image('message_small_icon.png').' '.__('Email',true)));
			echo $form->input('password');
			echo $html->link(__('Forgot your password?',true),'/users/recover',array('rel'=>'nofollow')); 
			echo $form->end(__('Login',true));
		?> 
	</div>
	<div class="clear"></div>
</div>
<?php
echo $javascript->codeBlock("
	form = $(\"UserLoginForm\");
	form.focusFirstElement();
");
?>