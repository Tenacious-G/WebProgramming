<div id="form-block">
	<div class="info">
		<?php __('Write and send a private message.'); ?>
	</div>
	<div class="form">
		<h1 class="generic_box"><?php echo __('Write a Message to',true).' '.h($user['User']['username']); ?></h1>
		<?php
			echo $form->create('Message', array('url'=>
				array('action'=>'write', $user['User']['id'])), array('id'=>"MessageWrite"));

			echo $form->input('subject',
				array('label'=>$html->image('message_small_icon.png').' '.__('Subject',true)));
			echo $form->input('message', array('label'=>__('Message', true)));
		?>
		<div class="submit">
			<?php
				echo $form->end(array(__('Submit',true), 'div'=>false));
				echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('cancel', true), 
					array('controller'=>'users', 'action'=>'view', $user['User']['slug'])).".</p>";
			?>
			<div class="clear"></div>	
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php
	echo $javascript->codeBlock("
		$('MessageSubject').activate();
	");
?>