<div id="admin-content">
<?php 
	echo $form->create('Price', array('action'=>'add'));
	echo $form->input('Price.value', array('label'=>__('Price', true)));
	echo $form->end(__('Submit', true));
?>
</div>