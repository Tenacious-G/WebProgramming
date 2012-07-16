<?php if(!empty($categories)) { ?>
<h1 class="generic_box"><?php echo __('Categories', true) ?></h1>
<div id="categories_list">
	<ul class="left">
	<?php 
	$break = ceil(count($categories) / 2);
	$n = 0;
	foreach($categories as $category) { 
		if($n == $break) echo '</ul><ul class="right">';
		$n++;
		echo '<li>'.$this->Html->link($category['Category']['value'], array('action'=>'index', 'all-cities',	
		 	$category['Category']['slug'])).'</li>';
	}
	?>
</ul>
<div class="clear"></div>
</div>
<?php } ?>