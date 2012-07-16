<div id="admin-content">
<div>
<?php 
if(!empty($users)) {
  
  echo "<table id=\"admin\">";
  echo "<thead>";
  echo "<tr>";
  echo "<th class=\"name\">".__('Name',true)."</th>";
  echo "<th class=\"icon\">".__('Delete',true)."</th>";
  echo "<th class=\"icon\">".__('Password',true)."</th>";
  echo "</tr>";
  echo "</thead>";
  
  echo "<tbody>";
  $class = "line1";

  foreach($users as $user):
  echo "<tr class=\"".$class."\">";
  
  echo "<td class=\"name\">".h($user['User']['username'])."</td>";
  echo "<td class=\"icon\">".$html->link($html->image('delete.png'),'/admin/users/delete_user/'.$user['User']['id'],
array('escape'=>false))."</td>";
  echo "<td class=\"icon\">".$html->link($html->image('edit.png'),'/admin/users/change_password/'.$user['User']['id'],
array('escape'=>false))."</td>";
  echo "</tr>";
      $class = ($class=="line1")? "line2":"line1";
  endforeach;
  echo "</tbody>";
  echo "</table>";
  
}

	echo $this->Element('paginator');
?>
</div>
</div>