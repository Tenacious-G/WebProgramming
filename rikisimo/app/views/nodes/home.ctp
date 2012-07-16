<!--
<div id="home-cover">
	<h1><?php  printf(__('Welcome to %s', true), Configure::read('appSettings.appName'))?></h1>
	<p><?php echo Configure::read('appSettings.appName');?></p>
</div>
-->
<div id="main_content">
	<?php 
		if(empty($users)) {
			echo '<p class="notice">'.__('First registered user will have admin permissions.', true).'</p>';
		}
		if(!empty($topRated)) {
			echo '<h1 class="title top">'.__('Top rated', true).'</h1>';
			$nodeList->getNodeList($topRated, false, true); 
		}

		if(!empty($nodes)) {
			echo '<h1 class="title new">'.__('Recently added', true).'</h1>';
			$nodeList->getNodeList($nodes); 
		}
	?>

</div>
<div style="width:329px;float:right;">
	<?php
		echo $html->link(sprintf(__('Add listing', true)), 
			array('action'=>'add'), array('id' => 'add-button', 'rel'=>'nofollow'));
	?>
	<?php 
		echo $this->Element('top_users');
		echo $this->Element('cities_block');
		echo $this->Element('categories_block'); 
		echo $this->Element('tags_block');
	?>
</div>
<div class="clear"></div>