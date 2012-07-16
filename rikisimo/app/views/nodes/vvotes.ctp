<div class="main_width">
<h1 class="new"><?php echo h($node['Node']['name']); ?></h1>
<?php 
if(!empty($node['userVote'])):
$i['units'] = 5;
$i['unit_width'] = 25;
$i['voted'] = true;
$i['votes'] = 1;

?>
<table id="forum">
	<thead>
		<th><?php __('User'); ?></th>
		<th><?php __('Vote'); ?></th>
		<th></th>
	</thead>
	<?php
	foreach($node['userVote'] as $vote):
	
	$i['id'] = $node['Node']['id'];
	$i['rating'] = $vote['vote'];
	$i['rating_value'] = @number_format($i['rating']/$i['votes'], 2);
	
		echo "<tr>";
		echo "<td>";
		echo $html->link($html->image('users/'.$vote['User']['photo'], array('alt'=>h($vote['User']['username']), 'class'=>'small-user')).' '.h($vote['User']['username']), array('controller'=>'users', 'action'=>'view', $vote['User']['slug']), array('escape'=>false));
		echo "</td>";
		echo "<td>";
		echo $rfRating->ratingBar($i);
		echo "</td>";
		echo "<td>";
		echo "</td>";
		echo "</tr>";
	endforeach;
		
	?>
</table>

<div class="bclear">
<?php echo $html->link('<span>'.__('Back', true).'</span>', array('controller'=>'nodes', 'action'=>'view', $node['City']['slug'], $node['Node']['slug']), array('class'=>'button', 'escape'=>false)); ?>
</div>

<?php endif; ?>
</div>