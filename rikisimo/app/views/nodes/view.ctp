	<div id="main_content">
		<h1 class="new"><?php echo h($node['Node']['name']); ?></h1>
    	<div class="vnode">
      		<div class="general_info" >
        		<?php 
					if($node['User']['username']) {
						echo $html->image('users/'.$node['User']['photo'],
							array('alt'=>h($node['User']['username'])));						
					}
					else {
						echo $html->image('users/user_photo.jpg', array('alt'=>__('Anonymous', true)));							
					}
          			echo __('Added', true).' '.$time->timeAgoInWords($node['Node']['created']);
          			echo ' '.__('by',true).' ';
          			if($node['User']['username']==null) {
            			__('Anonymous');
          			}
          			else {
            			echo $html->link($node['User']['username'],
                            '/users/view/'.$node['User']['slug']);      
          			}
					echo ".";
					if($me!=null and $me==$node['Node']['user_id']):
						echo " ";
						echo '('.$html->link(__('Edit',true),
							array('controller'=>'nodes', 'action'=>'edit',$node['Node']['id'])).')';
					endif;
			
					if($time->format('dmy',$node['Node']['created']) != 
						$time->format('dmy',$node['Node']['modified'])):
						echo "<br/>".__('Last update',true)." ";
						echo $time->timeAgoInWords($node['Node']['modified']);
						echo ".";
					endif;
				?>
			</div>
				<?php if($node['Node']['price_id']): ?>
	        	<div>
	        		<?php echo "<b>".__('Price',true).':</b> '.h($node['Price']['value']); ?>
	        	</div>
	      		<?php endif; ?>

				<?php if($node['Node']['category_id']): ?>
	        	<div>
	        		<?php echo "<b>".__('Category',true).':</b> ';
					echo $html->link($node['Category']['value'], array('controller'=>'nodes', 'action'=>'index', $node['City']['slug'].'/'.$node['Category']['slug'])); 

					?>
	        	</div>
	      		<?php endif; ?>
				<?php if(!empty($node['Tag'])): ?>
				<div class="index_tags">
					<b><?php __('Tags') ?>:</b>
		  			<?php
						if(!$node['Category']['slug']) $node['Category']['slug'] = 'all-categories';
						$n = 1;
		      			foreach($node['Tag'] as $tag):
		        			echo $html->link($tag['name'],
								array('controller'=>'nodes', 'action'=>'index',$node['City']['slug'],
								$node['Category']['slug'], $tag['slug']), array('rel'=>'tag'));

							if($n<count($node['Tag'])) echo ", ";
							else echo ".";
							$n++;
		      			endforeach;
		  			?>
				</div>
				<?php endif; ?>
				
			<?php
				/* ratingInfo set by calling action,
 				 * use rfRating helper to output display 
 				 */

				echo $rfRating->ratingBar($ratingInfo,true, $user_voted);
			?>

      		<p class="description">
        		<?php echo nl2br(h($node['Node']['notes'])); ?>
      		</p>

			<div class="node-info">
 				<p class="adr">
					<b><?php echo __('Address',true).': '; ?></b>
					<span class="location street-address">
						<?php echo h($node['Node']['address']); ?></span>, 
          			<span class="locality">
          			<?php 
            			echo $html->link($node['City']['name'],
							array('controller'=>'nodes', 'action'=>'index',$node['City']['slug'])); 
          			?></span>, 
          			<span class="country-name">
            			<?php __(h($node['City']['Country']['name'])); ?></span>.
      			</p>
      
				<p class="phone">
        			<?php echo "<b>".__('Phone',true).':</b> '.h($node['Node']['phone']); ?>
      			</p>
      			<p class="url">
      				<b><?php echo __('Web', true).':'; ?></b>
        			<?php
          				if(!empty($node['Node']['web'])):
            				echo $html->link($node['Node']['web'],
								'http://'.$node['Node']['web'],
                                 array('rel'=>'nofollow'));
						endif;
					?>
				</p>
			</div>

			<div id="view_map_full">
				<div id="divMap" style="overflow:hidden;">
					<?php
					if($node['Node']['lat'] and $node['Node']['lng']) {
						$default = array('type'=>'0','zoom'=>2,
							'lat'=>$node['Node']['lat'],'long'=>$node['Node']['lng']);    
	      				echo $googleMap->map($default, $style = 'width:620px; height: 250px' );
	      			
						if(!empty($near)) {
							foreach($near as $n) {
								echo $googleMap->addNearMarker($n, $node['City']['slug']);
							}
						}
						
						echo $googleMap->showLatLng($node['Node']['lat'],$node['Node']['lng']);
	    			}
	    			else {
	      				$default = array('type'=>'0','zoom'=>2,'lat'=>0,'long'=>0);
	      				echo $googleMap->map($default, $style = 'width:620px; height: 250px' );
	      				echo $googleMap->showAddress(h($node['Node']['address']).", ".
							h($node['City']['name']).', '.h(__($node['City']['Country']['name'],true)));
	    			}
					?>
				</div>

				</div>
				<div class="general_info streetlink">
					<?php  echo $googleMap->addStreetView(); ?>
				</div>

	  			<!-- bookmarks -->
	  			<div id="social_bookmarks" class="sbookmarks"> 
	    			<?php echo $bookmark->getBookMarks(h($node['Node']['name']), 
							null, h($node['Node']['shorturl'])); ?>
	  			</div>
	  			<div class="clear"></div>

				<div class="bclear">
				<?php
					// add comment link
					echo $html->link('<span>'.__('Add review', true).'</span>',
						array('controller'=>'comments', 'action'=>'write', $node['Node']['id']),
						array('rel'=>'nofollow', 'class'=>'button', 'escape'=>false));

				?>
				</div>
				
				<div id="comment-block">
				<?php
				if(!empty($node['Comment'])):
					foreach($node['Comment'] as $comment) {
						echo '<div class="comment-box">';	
						echo "<div class=\"comment-box-image\">";

						if(empty($comment['User'])) {
  							echo $html->image('user_photo.jpg', array('class'=>'small'));
						}
						else {
 							echo $html->link($html->image('users/'.$comment['User']['photo'], 
								array('alt'=>h($comment['User']['username']))),
								array('controller'=>'users', 'action'=>'view', 
									$comment['User']['slug']), array('escape'=>false));
						}
						echo "</div>";

						echo "<div class=\"comment-box-text\">";
						echo '<a id="comment-'.$comment['id'].'"></a>';
						echo "<p class=\"general_info\">";
						echo __('Submited by')." ";

						if(empty($comment['User'])) {
  							echo __('Anonymous',true);
						}
						else {
 							echo $html->link($comment['User']['username'],
								array('controller'=>'users', 'action'=>'view', $comment['User']['slug']));
						}
						echo ' '.$time->timeAgoInWords($comment['created']).' ';
						echo '</p>';
						echo '<p id="comment-vote-'.$comment['id'].'" class="general_info">';
						$users_id = Set::extract('/Commentvote/user_id', $comment);
						echo sprintf(__n('%s people like this', '%s people like this', 
							count($comment['Commentvote']), true), count($comment['Commentvote']));

						if($me and !in_array($me, $users_id) and $comment['user_id']!=$me) {
							echo ' · '.$ajax->link(__('Like', true), '/comments/vote/'.$comment['id'], array('update'=>'comment-vote-'.$comment['id'])).' ';
						}						
						echo "</p>";

						echo nl2br(h($comment['comment']));

						echo "</div>";
						echo '<div class="clear"></div>';
						echo "</div>";
					}
				else:
					echo '<p class="general_info">'.__('There are no reviews', true).'</p>';
				endif;
				?>
				</div>
			</div>
		</div>

		<div style="width:329px;float:right;">	
		<h1 class="generic_box"><?php __('Photos'); ?></h1>

		<div class="bclear">
  			<?php
  				echo ' '.$html->link("<span>".__('Upload a photo',true)."</span>",
					array('controller'=>'photos', 'action'=>'upload', $node['Node']['id']),
					array('rel'=>'nofollow', 'class'=>'button','escape'=>false));
			?>
				<div class="clear"></div>
		</div>

  		<div id="small_photos">		 
 		<?php
    		if(!empty($node['Photo'])):
 
  			foreach($node['Photo'] as $photo):
  				echo $html->link($html->image('nodes/small/'.$photo['file'],
					array('alt'=>h($photo['title']))),
					array('controller'=>'photos', 'action'=>'view', $node['Node']['slug'], $photo['id']),	
					array('escape'=>false));
  			endforeach;
			echo "<div class=\"clear\"></div>";
			endif; 
		?>
  		</div>
		
		<?php if(!empty($near)): ?>

		<h1 class="generic_box"><?php echo __('Nearest', true); ?></h1>
		<div id='near-list'>
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
					echo $html->link(h($n['Node']['name']), 
						array('action'=>'view', $n['City']['slug'], $n['Node']['slug']));
		
					echo '<div class="general_info">'.__(sprintf(__('%.2f Km away', true),
						$n[0]['distance']), true);
					echo '</div></div>';
					echo '<div class="near-rating">'.$rfRating->ratingBar($i).'</div>';
					echo '<div class="clear"></div>';
					echo '</div>';
				endforeach;
			
			?>
		</div>
		<?php endif; ?>
		
	</div>

<div style="clear:both"></div>