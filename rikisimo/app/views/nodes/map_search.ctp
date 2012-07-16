<div id="view_restaurant_full">
	<h1 class="generic_box"><?php printf(__('Nearest listings', true)); ?></h1>
<div id="view_map_full">
	<div id="divMap" style="overflow:hidden; border: 1px solid #ccc; margin-bottom: 50px">
		<?php

			$default = array('type'=>'0','zoom'=>2,'lat'=>$coordinates[1],'long'=>$coordinates[0]);
			echo $googleMap->map($default, $style = 'width:100%; height: 450px' );
			echo $googleMap->showLatLng($coordinates[1],$coordinates[0]);
					
			if(!empty($near)) {
				foreach($near as $n) {
					echo $googleMap->addNearMarker($n, $n['City']['slug']);
				}
			}
		?>
	</div>

	</div>
	</div>
	
		<div id="restaurant-side">
			<?php if(!empty($near)): ?>

			<div class='crestaurant_row'>
				<?php
					foreach($near as $n):
						$i['units'] = 5;
						$i['unit_width'] = 25;
						$i['voted'] = true;
						$i['votes'] = $n[0]['Votes'];
						$i['id'] = $n['Node']['id'];
						$i['rating'] = $n[0]['totalPoints'];
						$i['rating_value'] = @number_format($i['rating']/$i['votes'], 2);

						echo '<div class="near-restaurant">';
						echo '<div class="near-restaurant-info">';
						echo $html->link($n['Node']['name'], 
							array('action'=>'view', $n['City']['slug'], $n['Node']['slug']));

						echo '<div class="general_info">'.__(sprintf('%.2f Km away', $n[0]['distance']), true);
						echo '</div></div>';
						echo '<div class="near-rating">'.$rfRating->ratingBar($i).'</div>';
						echo '<div class="clear"></div>';
						echo '</div>';
					endforeach;

				?>
			</div>
			<?php endif; ?>
			
	<div class="bclear">
	<?php
		echo $html->link('<span>'.__('Search again', true).'</span>', 
			array('action'=>'map'), array('class'=>'button', 'rel'=>'nofollow', 'escape'=>false));
	?>
	</div>
</div>
<div class="clear"></div>
</div>