<div id="admin-content">
<?php 
	echo $form->create('Price', array('action'=>'edit'));
	echo $form->hidden('Price.id');
	echo $form->input('Price.value', array('label'=>__('Price', true)));
	echo $form->end('Submit');
?>
</div>