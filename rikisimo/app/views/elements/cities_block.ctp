<?php if(!empty($countries)) { ?>
	<h1 class="generic_box"><?php __('Cities'); ?></h1>
	<div id="cities_block">
	<?php 
		$break = ceil(count($countries) / 2);
		$n = 0;
		echo '<ul class="left">';
		foreach($countries as $country => $cities) {
			if($n == $break) echo '</ul><ul class="right">';
			$n++;
			echo '<li><b>'.__($country, true).'</b></li>';
			foreach($cities as $city) {
				echo '<li>'.$this->Html->link($city['name'], array('action'=>'index', $city['slug'])).'</li>';
			}
		}
		echo '</ul>';
	 ?>
	<div class="clear"></div>
	</div>
<?php } ?>