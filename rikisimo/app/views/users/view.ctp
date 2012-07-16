<div id="main_content">
	<h1 class="new"><?php echo h($user['User']['username']); ?></h1>
	<div style="margin-bottom: 20px">
		<div class="groupreply-image">
		<?php 
			echo $html->image('users/'.$user['User']['photo'], 
 				array('alt'=>$user['User']['username'])); 
		?>
		</div>

		<div id="user_info">
			<p class="general_info">
			<?php __('Joined this site')?> <?php echo $time->timeAgoInWords($user['User']['created']);?>
			<br/>
			<?php echo __('Rated', true).': '.$user['totalRatings']; ?>
			<br/>
			<?php echo __('Review score', true).': '.$user['totalCommentvotes']; ?>			
			</p>
			<p>
				<?php 
					echo $this->Element('user_badges', array('user'=>$user));
				 ?>
			</p>
     		<p class="general_info">
			<?php
				if($user['User']['description']) {
					echo nl2br(h($user['User']['description']));
				}
			?>
			</p>
		</div> 
		<div class="clear"></div>

		<div class="bclear">
		<?php
			if(isset($homepage)) {
				echo $html->link("<span>".__('Edit my profile',true)."</span>",
					'/users/edit', array('class'=>'button', 'escape'=>false));
			}
			else {
				echo $html->link("<span>".__('Send a Message',true)."</span>",
					'/messages/write/'.$user['User']['id'],
					array('rel'=>'nofollow', 'class'=>'button', 'escape'=>false));
  				if($is_friend) {
					echo $html->link("<span>".__('Add to My Friend List',true)."</span>",
						'/users/add_friend/'.$user['User']['id'],
						array('class'=>'button', 'escape'=>false));
				}
				else {
					echo $html->link("<span>".__('Remove from My Friend List',true)."</span>",
						'/users/delete_friend/'.$user['User']['id'],
						array('class'=>'button', 'escape'=>false));    
				}
			}
		?>
		</div>
	</div>
	<? 
		$i['units'] = 5;
		$i['unit_width'] = 25;
		$i['voted'] = true;
		$i['votes'] = 1;
	?>
	<h3>
		<?php if(isset($homepage)): ?>
			<?php printf(__('Listings I rated', true)); ?>
		<?php else: ?>
			<?php printf(__('Listings voted by %s', true), h($user['User']['username'])); ?> 
		<?php endif; ?>
	</h3>
	<?php if(!empty($voted)):?>
	<table id="forum">
		<thead>
			<th><?php echo ucfirst(__(Configure::read('appSettings.node_singular'), true)); ?></th>
			<th><?php __('Vote'); ?></th>
			<th><?php __('City'); ?></th>
		</thead>
		<?php 
		foreach($voted as $r):
			$i['id'] = $r['Node']['id'];
			$i['rating'] = $r['Vote']['vote'];
			$i['rating_value'] = @number_format($i['rating']/$i['votes'], 2); 
			echo "<tr>";
			echo "<td>".$html->link($r['Node']['name'], 
				array('controller'=>'nodes', 'action'=>'view', $r['City']['slug'], $r['Node']['slug']));
			echo "</td>";
			echo "<td>".$rfRating->ratingBar($i)."</td>";
			echo "<td>".$html->link($r['City']['name'], 
				array('controller'=>'nodes', 'action'=>'index', $r['City']['slug']))."</td>";
			echo "</tr>";
		endforeach;
	?>
	</table>
	<p><?php echo $this->Element('paginator'); ?></p>
	<?php else: ?>
	<p class="general_info"><?php printf(__('There are no listings.', true)); ?></p>
	<?php endif; ?>
</div>

<div id="main_sidebar">
<?php
if(!empty($user['Friend'])) {
	?>
	<h1 class="generic_box">
		<?php if(isset($homepage)): ?>
			<?php __('My friends'); ?>
		<?php else: ?>
			<?php echo sprintf(__('%s\'s friends', true), h($user['User']['username'])); ?>
		<?php endif; ?>
	</h1>
	<?php
	echo "<div id=\"friend_list\">";
  
	foreach($user['Friend'] as $friend):
		echo $html->link($html->image('users/'.$friend['photo'],
			array('alt'=>h($friend['username']),'title'=>h($friend['username']))),
			'/users/view/'.$friend['slug'], array('escape'=>false)).' ';
	endforeach;
	echo "<div class=\"clear\"></div>";
	echo "</div>";
}

if(!empty($user['Photo'])) {
	?>
	<h1 class="generic_box">
		<?php if(isset($homepage)): ?>
			<?php __('My photos'); ?>
		<?php else: ?>
			<?php echo sprintf(__('%s\'s photos', true), h($user['User']['username'])); ?>
		<?php endif; ?>
	</h1>
	<?php
	echo "<div id=\"small_photos\">";
	foreach($user['Photo'] as $photo):
		echo $html->link($html->image('nodes/small/'.$photo['file'],
		array('alt'=>h($photo['title']))),
			array('controller'=>'photos', 'action'=>'view', $photo['Node']['slug'], $photo['id']),
			array('escape'=>false));
	endforeach;
	echo "</div>";
}
?>
</div>
<div class="clear"></div>