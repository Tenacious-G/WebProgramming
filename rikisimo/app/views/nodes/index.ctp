<div id="main_content">
				
	<?php 
		if(empty($nodes)):
			echo '<p>'.sprintf(__('There are no listings', true), __(Configure::read('appSettings.node_plural'), true)).'</p>';
		else:
		
	
			if($city_name!='all-cities') {
				echo '<h1 class="new">'.$city_name.'</h1>';
			}
			if($category_name!='all-categories') {
				echo '<h1 class="new">'.$category_name.'</h1>';
			}
			if($current_tag!='all-tags') {
				echo '<h1 class="new">'.$current_tag.'</h1>';
			}
			$ratingInfo['units'] = 5;
			$ratingInfo['unit_width'] = 25;
			$ratingInfo['voted'] = true;

			foreach($nodes as $node):
				$ratingInfo['id'] = $node['Node']['id'];
				$ratingInfo['rating'] = 0;
				$ratingInfo['votes'] = 0;
				$ratingInfo['rating_value'] = 0;

				if($node['Vote']):
					$ratingInfo['rating'] = $node['Vote'][0]['Vote'][0]['rating'];
					$ratingInfo['votes'] = $node['Vote'][0]['Vote'][0]['votes'];
					if($ratingInfo['votes']) $value = $ratingInfo['rating'] / $ratingInfo['votes'];
					else $ratingInfo['votes'] = 0;
					
					$ratingInfo['rating_value'] = 
						@number_format($node['Points'], 2); 
				endif;
				
				echo "<div class=\"crestaurant_row\">";
				echo "<h2>";
				echo $html->link(ucfirst($node['Node']['name']),
					array('controller'=>'nodes', 'action'=>'view', $node['City']['slug'], $node['Node']['slug'])); 

				echo '</h2>';
				echo "<div class=\"fstar\">";
				echo $rFRating->ratingBar($ratingInfo);
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
				echo $html->link(h($node['User']['username']),
					'/users/view/'.$node['User']['slug']);
			}
			else {
				__('Anonymous');
			}
			echo ' '.$time->timeAgoInWords($node['Node']['created']);
			echo ' '.__('in',true).' '.$html->link($node['City']['name'],
				array('controller'=>'nodes', 'action'=>'index', $node['City']['slug'], $category, $tag_slug));
			echo ".";
		
			

			if($node['Category']['value']) {
				echo '<div>';
				echo __('Category', true).': ';
				echo $html->link($node['Category']['value'], array('action'=>'index', $node['City']['slug'], 
					$node['Category']['slug']));
				echo '</div>';
			}
			//  TAGS
	
			if(!empty($node['Tag'])):
				echo "<div class=\"index_tags\">";
				echo __('Tags', true).": ";
				$n = 1;
				foreach($node['Tag'] as $tag):
					echo $html->link(ucfirst($tag['name']),
						array('controller'=>'nodes', 'action'=>'index', $node['City']['slug'],
							$category, $tag['slug']),array('rel'=>'tag'));
					if($n<count($node['Tag'])) echo ", ";
					else echo ".";
					$n++;
				endforeach;
				echo "</div>";
			endif;
				echo "</div>";
			// TAGS END
	
			echo "<div class=\"notes\">";
			echo h($text->truncate($node['Node']['notes'], 200, array('ending'=>'...')));
			echo "</div>";

			if(!empty($node['Photo'])):
				echo "<div>";
				foreach($node['Photo'] as $p):
					echo $html->link($html->image('nodes/small/'.$p['file'], 
						array('alt'=>h($p['title']))), array('controller'=>'photos', 'action'=>'view',
						$node['Node']['slug'], $p['id']), array('escape'=>false)) .' ';
				endforeach;
				echo "</div>";
			endif;
			
		?>
	</div>
	<?php
  		endforeach;
		endif;
		echo $this->Element('paginator');
	?>
</div>

<div style="width:329px;float:right;">
	<?php
		echo $html->link(sprintf(__('Add listing', true)), 
			array('action'=>'add'), array('id' => 'add-button', 'rel'=>'nofollow'));
	?>
	<?php 
		echo $this->Element('cities_block');
		echo $this->Element('categories_block'); 
		echo $this->Element('tags_block');
	?>
</div>
<div style="clear:both"></div>
