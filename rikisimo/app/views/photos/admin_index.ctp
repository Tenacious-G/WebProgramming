<div id="admin-content">
<?php
if(empty($photos)) {
	echo __('There are no photos', true);
}
else {
	foreach($photos as $photo):
		echo "<p style=\"width:100px;float:left;margin-bottom:10px;\">";
		echo $html->image('nodes/small/'.$photo['Photo']['file']);
		echo "</p>";

		echo "<p style=\"width:200px;float:left;\">";
		echo $html->link($html->image('delete.png').' '.__('delete photo',true),
		array('controller'=>'photos', 'action'=>'delete',$photo['Photo']['id']), array('escape'=>false));
		echo "</p>";
		echo "<div class=\"clear\"></div> ";
	endforeach;
	echo $this->Element('paginator');
}
?>
</div>