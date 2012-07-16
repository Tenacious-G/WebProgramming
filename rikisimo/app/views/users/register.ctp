<div id="form-block">	
  <div class="info">
	<p><?php __('Sign up now, it is completely free'); ?>.</p>
  </div>

  <div class="form">
	<?php if(Configure::read('appSettings.fb_app_id')): ?>
	<h1><?php __('Login with Facebook'); ?></h1>
	<p id="facebook-login"><fb:login-button perms="email,publish_stream"></fb:login-button></p> 
	<?php endif; ?>
    <h1><?php __('Sign up'); ?></h1>
    <?php
        if  ($session->check('Message.auth')) $session->flash('auth');
        echo $form->create('User', array('action' => 'register'));
        echo $form->input('username', array('label'=>__('Name',true)));
        echo $form->input('email');
        echo $form->input('password');
        echo $form->end(__('Register',true));
    ?>
  </div>
  <div class="clear"></div>
</div>
<?php
	echo $javascript->codeBlock("
		form = $(\"UserRegisterForm\");
		form.focusFirstElement();
	")
?>