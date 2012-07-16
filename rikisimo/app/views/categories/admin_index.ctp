<div id="admin-content">
<div>
<?php
echo $html->link(__('Add category', true), array('action'=>'add')); 
if(!empty($categories)) {
  echo "<table id=\"admin\">";
  echo "<thead>";
  echo "<tr>";
  echo "<th class=\"name\">".__('Category',true)."</th>";
  echo "<th class=\"icon\">".__('Delete',true)."</th>";
  echo "<th class=\"icon\">".__('Edit',true)."</th>";
  echo "</tr>";
  echo "</thead>";
  
  echo "<tbody>";
  $class = "line1";

  foreach($categories as $category):
  echo "<tr class=\"".$class."\">";
  
  echo "<td class=\"name\">".h($category['Category']['value'])."</td>";
  echo "<td class=\"icon\">".$html->link($html->image('delete.png'),
	array('controller'=>'categories', 'action'=>'delete', $category['Category']['id']),
	array('escape'=>false))."</td>";
  echo "<td class=\"icon\">".$html->link($html->image('edit.png'),
	array('controller'=>'categories', 'action' => 'edit', $category['Category']['id']),
	array('escape'=>false))."</td>";
  echo "</tr>";
      $class = ($class=="line1")? "line2":"line1";
  endforeach;
  echo "</tbody>";
  echo "</table>";
}
?>
</div>
</div>