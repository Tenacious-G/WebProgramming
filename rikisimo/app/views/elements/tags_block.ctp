<?php
if(!empty($tags)) {
?>
<h1 class="generic_box"><?php __('Tags'); ?></h1>	
<div id="tags_list">
	<ul class="left">
<?php
	$break = ceil(count($tags) / 2);
	$n = 0;
	foreach($tags as $tag) {
		if($n == $break) echo '</ul><ul class="right">';
		$n++;
		echo '<li>'.$this->Html->link($tag['Tag']['name'], array('action'=>'index', 'all-cities', 'all-categories',
		 	$tag['Tag']['slug'])).'</li>';
	}
	?>
	</ul>
	<div class="clear"></div>
</div>
<?php
}
?>
