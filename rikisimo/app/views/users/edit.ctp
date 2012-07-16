<div id="form-block">

	<div class="info">
		<?php __('Here you can change your user name, email, photo and description.');?>
	</div>
	<div class="form">
		<div style="float:left;width:80px;margin-right:20px;">
		<?php echo $html->image('users/'.$user['User']['photo'], array('alt'=>$user['User']['username'])); ?>
		</div>
		<div class="user-info" style="float:left; width: 300px;">
			<h3><?php __('Upload your photo'); ?></h3>
			<form method="post" enctype="multipart/form-data" action="<?php echo $html->url('/users/upload')?>">
				
				<div style="font-size:12px;">
					<?php 
						echo $form->file('User.filedata'); 
					?>
				</div>
		
				<div class="submit">
				<?php
					echo $form->end(array(__('Upload my photo',true), 'div'=>false));
					echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('delete my photo', true), array('action'=>'delete_photo')).".</p>";
				?>
					<div class="clear"></div>	
				</div>
			</form>
		</div>

		<div class="clear"></div>
		<?php
			echo $form->create('User',array('action'=>'edit'));
		?>
	</div>

	<div class="clear"></div>

	<div class="info">
		<?php __('Here you can enter some personal information.'); ?>
	</div>
	<div class="form">
		<?php
			echo $form->input('User.description', array('type'=>'textarea'));
			echo $form->input('email');	
			
			echo $form->input('notification',
				array('type'=>'checkbox', 'label'=>__('Send me an email when someone sends me a private message',true)));

		?>
		<div class="submit">
		<?php
			echo $form->end(array(__('Submit',true), 'div'=>false));
			echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('cancel', true), array('action'=>'view')).".</p>";
		?>
			<div class="clear"></div>	
		</div>
		
	</div>
	
	<div class="clear"></div>
	<div class="info">
		<?php __('Change your user password.'); ?>
	</div>
	<div class="form">
		<div class="bclear">
		<?php 
			echo $html->link("<span>".__('Change password',true)."</span>",
				'/users/change_password', array('class'=>'button', 'escape'=>false));

		?>
		</div>
	</div>
	
	<div class="clear" style="margin-top: 200px"></div>
	<div class="info">
		<?php __('Delete your account. This action can not be undone.'); ?>
	</div>
	<div class="form">
		<div class="bclear">
		<?php 
		echo $html->link("<span>".__('Delete this account',true)."</span>",
			'/users/delete_user', array('class'=>'button', 'escape'=>false)); 
		?> 
		</div>
	</div>
	<div class="clear"></div>
</div>
</div>