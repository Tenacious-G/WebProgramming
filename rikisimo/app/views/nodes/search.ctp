<?php if(!empty($data)):?>
<table id="search">
	<thead>
		<th><?php echo ucfirst(__(Configure::read('appSettings.node_singular'), true)); ?></th>
		<th><?php __('City'); ?></th>
	</thead>
<?php
foreach($data as $r):
	echo "<tr>";
	echo "<td>";
	echo $html->link($r['Node']['name'], 
		array('controller'=>'nodes', 'action'=>'view', $r['City']['slug'], $r['Node']['slug']));
	echo "</td>";
	echo "<td>";
	echo $html->link($r['City']['name'], 
		array('controller'=>'nodes', 'action'=>'index', $r['City']['slug']));
	echo "</td>";
	echo "</tr>";
endforeach;
?>
</table>
<?php endif; ?>