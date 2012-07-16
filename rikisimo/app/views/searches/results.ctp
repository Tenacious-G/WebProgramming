<div id="main_content">
		<h1 class="generic_box"><?php __('Search'); ?></h1>
		<?php echo $this->Element('search_form'); ?>


<?php 
		if(!empty($nodes)):

			$ratingInfo['units'] = 5;
			$ratingInfo['unit_width'] = 25;
			$ratingInfo['voted'] = true;

			foreach($nodes as $node):
				$ratingInfo['id'] = $node['Node']['id'];
				$ratingInfo['rating'] = 0;
				$ratingInfo['votes'] = 0;
				$ratingInfo['rating_value'] = 0;

				if(!empty($node[0])):
					$ratingInfo['rating'] = $node[0]['totalPoints'];
					$ratingInfo['votes'] = $node[0]['Votes'];
					$ratingInfo['rating_value'] = 
						@number_format($node[0]['Points'], 2); 
				endif;

				echo "<div class=\"crestaurant_row\">";
				echo "<h2>";
				echo $html->link($node['Node']['name'], array('controller'=>'nodes', 
					'action'=>'view', $node['City']['slug'], $node['Node']['slug'])); 

				echo '</h2>';
				echo "<div class=\"fstar\">";
				echo $rfRating->ratingBar($ratingInfo);
				echo "</div>";
		?>
		<div class="clear"></div>
		<?php
			echo "<div class=\"general_info\">";
			if(!$node['User']['id']) $node['User']['photo'] = 'user_photo.jpg';
			echo $html->image('users/'.$node['User']['photo'],
				array('alt'=>h($node['User']['username']))).' ';
			echo __('Added by',true).' ';
			if($node['User']['username']) {
				echo $html->link($node['User']['username'], array('controller'=>'users', 'action'=>'view',
				$node['User']['slug']));
			}
			else {
				__('Anonymous');
			}
			echo ' '.$time->timeAgoInWords($node['Node']['created']);
			echo ' '.__('in',true).' '.$html->link($node['City']['name'],
				array('controller'=>'nodes', 'action'=>'index', $node['City']['slug']));
			echo ".";
			echo "</div>";
	
			echo "<div class=\"notes\">";
			echo ucfirst($text->truncate($node['Node']['notes'], 200, array('ending'=>'...')));
			echo "</div>";

		?>
	</div>
	<?php
  		endforeach;

		echo $this->Element('paginator');	
	else:
			echo '<p>'.__('There are no results', true).'</p>';
	endif;
	?>
</div>
<div class="clear"></div>