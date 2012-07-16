<div id="admin-content">
<div>
<?php
echo $html->link(__('Add price tag', true), array('action'=>'add')); 
if(!empty($prices)) {
  echo "<table id=\"admin\">";
  echo "<thead>";
  echo "<tr>";
  echo "<th class=\"name\">".__('Price',true)."</th>";
  echo "<th class=\"icon\">".__('Delete',true)."</th>";
  echo "<th class=\"icon\">".__('Edit',true)."</th>";
  echo "</tr>";
  echo "</thead>";
  
  echo "<tbody>";
  $class = "line1";

  foreach($prices as $price):
  echo "<tr class=\"".$class."\">";
  
  echo "<td class=\"name\">".h($price['Price']['value'])."</td>";
  echo "<td class=\"icon\">".$html->link($html->image('delete.png'),
	array('controller'=>'prices', 'action'=>'delete', $price['Price']['id']),
array('escape'=>false))."</td>";
  echo "<td class=\"icon\">".$html->link($html->image('edit.png'),
	array('controller'=>'prices', 'action'=>'edit', $price['Price']['id']),
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