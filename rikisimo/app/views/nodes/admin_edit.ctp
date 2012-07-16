<div id="admin-content">
	<div style="width: 500px">
<?php 
	echo $form->create('Node',array('action'=>'edit', $this->data['Node']['id']));
    echo $this->Element('node_form');	
?>
	</div>
</div>