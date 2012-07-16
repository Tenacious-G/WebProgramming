<div class="main_width">
	<h1 class="generic_box"><?php __('Search by address'); ?></h1>
	<div id="form-block" class="form">
	<?php
		echo $form->create('Map', array('url'=>array('controller'=>'nodes', 'action'=>'map')));
		echo $form->input('address');

	if(isset($addressError)):
	?>
	<div class="error-message"><?php __('Sorry, this address is unknown.'); ?></div>
	<?php
	endif;
	?>
		<p class="general_info"><?php __('(ex. Via Laietana 5, Barcelona)'); ?></p>

		<div class="submit">
		<?php
			echo $form->end(__('Search', true));
			echo "<p class=\"backlink\">".__('or', true).' '.$html->link(__('cancel', true), 
				array('controller'=>'nodes', 'action'=>'index')).".</p>";
		?>
		</div>
	</div>
</div>

<?php
	echo $javascript->codeBlock("
		form = $(\"MapAddForm\");
		form.focusFirstElement();
	")
?>