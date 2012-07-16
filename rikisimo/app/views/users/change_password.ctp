<div id="form-block">
	<div class="info">
		<?php __('Here you can change your password.'); ?>
	</div>
	<div class="form">
		<h1><?php __('Change User Password'); ?></h1>
		<?php
    		echo $form->create('User', array('action' => 'change_password', 'id'=>"UserChangePassword"));
    		echo $form->input('password',array('label'=>__('New password',true)));
    		echo $form->label('password_confirm',__('New Password Confirmation',true));
    		echo $form->password('password_confirm');
		?>
		
		<div class="submit">
			<?php
				echo $form->end(array('label'=>__('Change password', true), 'div'=>false));
				echo "<p class=\"backlink\"> or ".$html->link(__('cancel',true), array('action'=>'view')).".</p>";
			?>
			<div class="clear"></div>	
		</div>
	</div>

	<div class="clear"></div>
</div>
<?php
	echo $javascript->codeBlock("
		form = $(\"UserChangePassword\");
		form.focusFirstElement();
	");
?>