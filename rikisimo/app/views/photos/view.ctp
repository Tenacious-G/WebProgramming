<!-- main content start -->
<div id="main_content">
	<h1 class="generic_box">
	<?php echo __('Photos of',true).' '.h($photo['Node']['name']); ?>
	</h1>	
	<?php
		echo $html->image('nodes/'.$photo['Photo']['file'],
			array('alt'=>h($photo['Node']['name'].' '.$photo['Photo']['id']))); 
		echo "<div class=\"general_info\">".__('Photo of',true).' ';
		echo $html->link($photo['Node']['name'],
		array('controller'=>'nodes', 'action'=>'view',$photo['Node']['City']['slug'],$photo['Node']['slug']));
		echo " ".__('submited by',true);
		echo " ".$html->link($photo['User']['username'],
		array('controller'=>'users', 'action'=>'view', $photo['User']['slug']));

		if($photo['User']['id'] and $me==$photo['User']['id']) {
			echo "<br/> (".$html->link(__('delete this photo',true),
			array('controller'=>'photos', 'action'=>'delete',$photo['Photo']['id'])).")";
		}
		echo "</div>";
		if($photo['Photo']['description']) {
			echo '<p>';
			echo nl2br(h($photo['Photo']['description']));
			echo '</p>';
		}
		
	?>
</div>
<!-- main content end -->

<!-- sidebar start -->
<div id="main_sidebar">
	<div id="small_photos">
		<?php
			foreach($photo['Node']['Photo'] as $small_photo):
				echo $html->link($html->image('nodes/small/'.$small_photo['file'],
					array('alt'=>h($small_photo['title']))),
					array('controller'=>'photos', 'action'=>'view', $photo['Node']['slug'], $small_photo['id']),
					array('escape'=>false));
			endforeach;
		?>
		<div class="clear"></div>
	</div>
</div>
<!-- sidebar end -->

<div class="clear"></div>