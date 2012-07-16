<div id="admin-content">
<div>
<?php 
if(!empty($nodes)) {
  echo "<table id=\"admin\">";
  echo "<thead>";
  echo "<tr>";
  echo "<th class=\"name\">".__('Name',true)."</th>";
  echo "<th class=\"icon\">".__('Delete',true)."</th>";
  echo "<th class=\"icon\">".__('Edit',true)."</th>";
  echo "</tr>";
  echo "</thead>";
  
  echo "<tbody>";
  $class = "line1";
  foreach($nodes as $node):
    echo "<tr class=\"".$class."\">";
    echo "<td class=\"name\">".h($node['Node']['name'])."</td>";
    
    echo "<td class=\"icon\">".$html->link($html->image('delete.png'),'/admin/nodes/delete/'.$node['Node']['id'],
array('escape'=>false))."</td>";
    echo "<td class=\"icon\">".$html->link($html->image('edit.png'),'/admin/nodes/edit/'.$node['Node']['id'],
array('escape'=>false))."</td>";
    echo "</tr>";
    $class = ($class=="line1")? "line2":"line1";
  endforeach;
  echo "</tbody>";
  echo "</table>";
}
else {
	echo sprintf(__('There are no %s', true), __(Configure::read('appSettings.node_plural'), true));
}
?>
</div>

<?php
	echo $this->Element('paginator');
?> 
</div>