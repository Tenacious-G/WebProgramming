<div id="form-block">
	<div class="info">
		<?php __('Let others know what you think.'); ?>
	</div>
	<div class="form">
		<h1 class="generic_box"><?php echo h($node['Node']['name']); ?></h1>
		<?php
			echo $form->create('Comment', array('url'=>
				array('action'=>'write', $node['Node']['id'])));
			
			echo $form->input('comment',
				array('label'=> __('Write a review',true)));
		?>
		<div class="submit">
			<?php
				echo $form->end(array(__('Submit', true), 'div'=>false));
				echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('cancel', true), 
				array('controller'=>'nodes', 'action'=>'view', $node['City']['slug'],
					$node['Node']['slug'])).".</p>";
			?>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php
	// autofocus form field
	echo $javascript->codeBlock("
		$('CommentComment').activate();
	");
?>