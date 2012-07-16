<div id="admin-content">
<?php 
	echo $form->create('Category', array('action'=>'add'));
	echo $form->input('Category.value', array('label'=>__('Category', true)));
	echo $form->end(__('Submit', true));
?>
</div>