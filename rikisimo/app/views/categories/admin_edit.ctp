<div id="admin-content">
<?php 
	echo $form->create('Category', array('action'=>'edit'));
	echo $form->hidden('Category.id');
	echo $form->input('Category.value', array('label'=>__('Category', true)));
	echo $form->end(__('Submit', true));
?>
</div>